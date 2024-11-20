<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Facades\Auth;
// use model composer
use App\Models\Composer;
// use model partitur
use App\Models\Partitur;
// use model partitur_detail
use App\Models\PartiturDetail;
// use model cart
use App\Models\Cart;

// use choir
use App\Models\Choir;
use App\Models\Shipment;
use App\Models\Voucher;

use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
// use model Merchandise
use App\Models\Merchandise;
use Midtrans\Notification;

// use model Order and order detail
use App\Models\Order;
use App\Models\OrderItem;

use Carbon\Carbon;
use setasign\Fpdi\Fpdi;

class CheckoutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function checkTransactionStatus($orderId)
    {   
        try {
            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            \Midtrans\Config::$isProduction = config('midtrans.is_production');
    
            $status = \Midtrans\Transaction::status($orderId);
    
            if ($status->transaction_status === 'settlement') {
                // Update the order in the database
                DB::table('orders')
                    ->where('id', $orderId)
                    ->update(['payment_id' => 1, 'updated_at' => now()]);
    
                \Log::info("Order $orderId marked as paid.");
            }
    
            return response()->json($status);
    
        } catch (\Exception $e) {
            \Log::error('Error fetching transaction status: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    // public function createPayment(Request $request)
    // {
    //     // dd($request->all());
    //     $params = [
    //         'transaction_details' => [
    //             'order_id' => rand(),
    //             'gross_amount' => $request->total,
    //         ],
    //         // Tambahan data customer, item, dan lain-lain
    //         'customer_details' => [
    //             'first_name' => auth()->guard('customer')->user()->name,
    //             'email' => auth()->guard('customer')->user()->email,
    //             'phone' => auth()->guard('customer')->user()->phone,
    //         ],
    //     ];

    //     try {
    //         $paymentUrl = Snap::createTransaction($params)->redirect_url;
    //         return redirect($paymentUrl);
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => $e->getMessage()]);
    //     }
    // }

    public function downloadPDF($id)
    {
        $item = OrderItem::find($id);

        if(!$item){
            return redirect()->route('homepage');
        }   
        $filePath = public_path($item->partiturDetail->partitur_ori);

        $pdf = new Fpdi();
        $pdf->setSourceFile($filePath);
        $pageCount = $pdf->setSourceFile($filePath);

        $pdf->SetAutoPageBreak(false);
        
        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $pdf->AddPage();
            $templateId = $pdf->importPage($pageNo);
            $pdf->useTemplate($templateId, ['adjustPageSize' => true]);

            $pdf->SetY(8); 
            $pdf->SetFont('Helvetica', '',9);
            $textWidth = $pdf->GetStringWidth('BCS Publisher - Licensed to ') + 
                         $pdf->GetStringWidth(' , Order: ') +
                         $pdf->GetStringWidth(' , ' . date('d/m/Y') . ', limit ');
                         
            $pdf->SetFont('Helvetica', 'B',9);
            $textWidth += $pdf->GetStringWidth($item->choir->name) +
                          $pdf->GetStringWidth($item->order->no_resi == null ? '-' : $item->order->no_resi) +
                          $pdf->GetStringWidth($item->quantity . ' copies');
    
            // Hitung posisi X start untuk memusatkan
            $startX = ($pdf->GetPageWidth() - $textWidth) / 2;
    
            // Setel posisi X
            $pdf->SetX($startX);
    
            // Cetak bagian pertama (biasa)
            $pdf->SetFont('Helvetica', '',9);
            $pdf->Write(0, 'BCS Publisher - Licensed to ');
    
            // Cetak bagian yang tebal
            $pdf->SetFont('Helvetica', 'B',9);
            $pdf->Write(0, $item->choir->name);
    
            // Lanjutkan dengan teks biasa
            $pdf->SetFont('Helvetica', '',9);
            $pdf->Write(0, ', Order: ');
    
            // Cetak bagian yang tebal
            $pdf->SetFont('Helvetica', 'B',9);
            $pdf->Write(0, $item->order->no_resi == null ? '-' : $item->order->no_resi);
    
            // Lanjutkan dengan teks biasa
            $pdf->SetFont('Helvetica', '',9);
            $pdf->Write(0, ', ' . date('d/m/Y') . ', limit ');
    
            // Cetak bagian yang tebal
            $pdf->SetFont('Helvetica', 'B',9);
            $pdf->Write(0, $item->quantity . ' copies');
        }

        // Output PDF
        $filename = str_replace('uploads/partitur/ori/','',$item->partiturDetail->partitur_ori);
        return response($pdf->Output('I', $filename), 200, [
            'Content-Type' => 'application/pdf'
        ]);
    }


    function generateInvoiceNumber() {
        $date = Carbon::now()->format('Ymd'); 
        $countToday = Order::whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()])->count() + 1;
        $number = str_pad($countToday, 3, '0', STR_PAD_LEFT); 
        $no_invoice = "INV/{$date}/{$number}";  

       // dd($no_invoice);
        
        return $no_invoice; 
    }
    

    public function storeOrder($data) {
        // find voucher by data voucher_name
        $voucher_id = 0;
        $voucher = 0;
        if (!empty($data['voucher_name'])) {
            $voucher = Voucher::whereRaw('LOWER(name) = ?', strtolower($data['voucher_name']))->first();
            // get voucher id
            if($voucher != null){
                $voucher_id = $voucher->id;
                $voucher = $voucher->potongan;
            }else{
                $voucher = 0;
            }
        }

        // find shipment by guard customer id and is_default = 1
        $shipment = Shipment::where('customer_id',auth()->guard('customer')->user()->id)->where('is_default',1)->first();
        // get shipment id
        $shipment_id = $data['shipping_id'];

        $order = new Order();
        $order->total = $data['total'];
        $order->shipment_fee = $data['shippingcost'];
        $order->shipment_id = $shipment_id;
        // $order->voucher = $data['voucher_name'];
        $order->voucher_id = $voucher_id;
        $order->voucher = $voucher;
        $order->date = \Carbon\Carbon::now();
        $order->customer_id = auth()->guard('customer')->user()->id;
        // irder no_invoice generate with bcs/inv
        $order->no_invoice = $this->generateInvoiceNumber();

        // order no resi null
        $order->no_resi = null;
        // order status pending
        $order->status = '0';
        $order->payment_id = '0';
        // create at
        $order->created_at = \Carbon\Carbon::now();
        // create by
        $order->created_by = auth()->guard('customer')->user()->id;
        $order->save();

        $intnum = 0;
        foreach ($data['cartItems'] as $item) {
            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->merchandise_id = $data['merchandise_id'][$intnum];
            $orderItem->size = $data['size'][$intnum];
            $orderItem->color = $data['color'][$intnum];
            $orderItem->partitur_id = $data['partiturdet_id'][$intnum];
            $orderItem->quantity = $data['total_quantity'][$intnum];
            if($data['partiturdet_id'][$intnum] == 0){
                $orderItem->subtotal = $data['harga'][$intnum]*$data['total_quantity'][$intnum];
            }else{
                if($data['competition_status'][$intnum] == 1){
                    $orderItem->subtotal = $data['harga'][$intnum]*$data['total_quantity'][$intnum]+$data['competition_fee'][$intnum];
                }else{
                    $orderItem->subtotal = $data['harga'][$intnum]*$data['total_quantity'][$intnum];
                }
            }
            $orderItem->total_harga = $data['harga'][$intnum]*$data['total_quantity'][$intnum];
            $orderItem->for_competition = $data['competition_status'][$intnum];
            if($data['competition_status'][$intnum] == 1 && $data['merchandise_id'][$intnum] == 0){
                $orderItem->competition_fee = $data['competition_fee'][$intnum];
            }else{
                $orderItem->competition_fee = 0;
            }
            $orderItem->choir_id = $data['choir_id'][$intnum];
            // create at
            $orderItem->created_at = \Carbon\Carbon::now();
            // create by
            $orderItem->created_by = auth()->guard('customer')->user()->id;
            $orderItem->save();

            if($data['partiturdet_id'][$intnum] == 0){
                $merchandise = Merchandise::find($data['merchandise_id'][$intnum]);
                $merchandise->stok -= $data['total_quantity'][$intnum];
                $merchandise->updated_by = auth()->guard('customer')->user()->id;
                $merchandise->save();
            }

            $intnum++;
        }

        return $order;
    }

    // public function createPayment(Request $request)
    // {
    //     // dd($request->all());
    //     $order = $this->storeOrder($request->all());  

    //     // set variable shipment where customer_id = auth()->guard('customer')->user()->id and is_default = 1
    //     $shipment = Shipment::where('customer_id',auth()->guard('customer')->user()->id)->where('is_default',1)->first();
    //     // shipment nama_penerima split for first name and last name
    //     $nama_penerima = explode(" ", $shipment->nama_penerima);
    //     // set first name and last name
    //     $first_name = $nama_penerima[0];
    //     $last_name = isset($nama_penerima[1]) ? $nama_penerima[1] : '';

    //     $params = [
    //         'transaction_details' => [
    //             'order_id' => $order->id,
    //             'gross_amount' => $request->total,
    //         ],
    //         'customer_details' => [
    //             'first_name' => auth()->guard('customer')->user()->name,
    //             'email' => aut'h()->guard('customer')->user()->email,
    //             'phone' => auth()->guard('customer')->user()->phone_number,  
    //         ],
    //         'billing_address' => [
    //             'first_name' => $first_name,
    //             'last_name' => $last_name,
    //             'address' => $shipment->informasi_tambahan,
    //             'city' => $shipment->kota,
    //             'postal_code' => $shipment->kode_pos,
    //             'phone' => $shipment->phone_number,
    //             'country_code' => $shipment->negara
    //         ],
    //         'shipping_address' => [
    //             'first_name' => $first_name,
    //             'last_name' => $last_name,
    //             'address' => $shipment->informasi_tambahan,
    //             'city' => $shipment->kota,
    //             'postal_code' => $shipment->kode_pos,
    //             'phone' => $shipment->phone_number,
    //             'country_code' => $shipment->negara
    //         ],
    //     ];

    //     try {
    //         $paymentUrl = Snap::createTransaction($params)->redirect_url;
    //         return redirect($paymentUrl);
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => $e->getMessage()]);
    //     }
    // }

    public function createPayment(Request $request)
    {
        // dd($request->all());
        $order = $this->storeOrder($request->all());  

        // Persiapan parameter untuk dikirim ke VT-Web Midtrans
        $params = [
            'transaction_details' => [
                'order_id' => $order->id,
                'gross_amount' => (int) $request->total, // pastikan jumlah dalam bentuk integer
            ],
            'customer_details' => [
                'first_name' => auth()->guard('customer')->user()->name,
                'email' => auth()->guard('customer')->user()->email,
                'phone' => auth()->guard('customer')->user()->phone_number,  
            ],
            'vtweb' => []
        ];

        try {
            // Membuat transaksi VT-Web dan mendapatkan URL redirect-nya
            $paymentUrl = Snap::createTransaction($params)->redirect_url;
            return redirect($paymentUrl);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function notification(Request $request)
{
    try {
        $payload = $request->all();

        \Log::info('Midtrans Notification Payload:', $payload);

        $orderId = $payload['order_id'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;
        $paymentType = $payload['payment_type'] ?? 'unknown';
        $grossAmount = $payload['gross_amount'] ?? '0.00';

        if (!$orderId) {
            \Log::warning('Missing order_id in notification');
            return response()->json(['error' => 'Missing order_id'], 400);
        }

        $order = Order::where('id', $orderId)->first(); // Or use no_invoice if matching differently
        if (!$order) {
            \Log::warning("Order with ID $orderId not found.");
            return response()->json(['message' => 'Order not found'], 404);
        }

        // Validate gross amount
        if (bccomp($order->total, $grossAmount, 2) !== 0) {
            \Log::warning("Gross amount mismatch for Order ID $orderId. Expected {$order->total}, received {$grossAmount}.");
            return response()->json(['error' => 'Gross amount mismatch'], 400);
        }

        // Update order based on transaction status
        switch ($transactionStatus) {
            case 'capture':
            case 'settlement':
                $order->payment_id = 1; // Paid
                break;

            case 'pending':
                $order->payment_id = 2; // Pending
                break;

            case 'deny':
            case 'expire':
            case 'cancel':
                $order->payment_id = 5; // Failed
                break;
        }

        $order->payment_type = $paymentType; // Track payment type
        $order->save();

        \Log::info("Order $orderId updated successfully with status $transactionStatus.");

        return response()->json(['message' => 'Notification processed successfully'], 200);
    } catch (\Exception $e) {
        \Log::error('Error processing Midtrans notification: ' . $e->getMessage());
        return response()->json(['error' => $e->getMessage()], 500);
    }
}



    // private function updateOrderStatus($order, $payment_id, $type)
    // {
    //     switch ($payment_id) {
    //         case 'capture': // for credit card transaction
    //             if ($type == 'credit_card') {
    //                 $order->payment_id = 1; // success
    //             }
    //             break;
    //         case 'settlement':
    //             $order->payment_id = 1; // success
    //             break;
    //         case 'pending':
    //             $order->payment_id = 2; // pending
    //             break;
    //         case 'deny':
    //             $order->payment_id = 3; // denied
    //             break;
    //         case 'expire':
    //             $order->payment_id = 4; // expired
    //             break;
    //         case 'cancel':
    //             $order->payment_id = 5; // canceled
    //             break;
    //     }
    //     $order->save();
    // }




    public function index()
    {
        // Temporarily disable ONLY_FULL_GROUP_BY
        DB::statement("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");
        // First, retrieve partitur items with partiturdet_id != 0
        $partiturItems = Cart::select(DB::raw('SUBSTRING_INDEX(partitur.file_image, ",", 1) as file_image'), 
                                    'partitur_detail.name as name', 
                                    'cart.id', 
                                    'cart.size', 
                                    'cart.color', 
                                    'cart.partiturdet_id', 
                                    'cart.merchandise_id', 
                                    'cart.choir_id', 
                                    'cart.competition', 
                                    'partitur_detail.file_type', 
                                    'partitur_detail.harga', 
                                    'cart.customer_id', 
                                    DB::raw('sum(cart.qty) as total_quantity'))
                            ->join('partitur_detail', 'partitur_detail.id', '=', 'cart.partiturdet_id')
                            ->join('partitur', 'partitur.id', '=', 'partitur_detail.partitur_id')
                            ->where('customer_id', auth()->guard('customer')->user()->id)
                            ->where('cart.partiturdet_id', '<>', 0)
                            ->groupBy('cart.partiturdet_id', 'cart.customer_id');

        // Second, retrieve merchandise items with merchandise_id != 0 and partiturdet_id == 0
        $merchandiseItems = Cart::select(
                                DB::raw('SUBSTRING_INDEX(merchandise.photo, ",", 1) as file_image'), 
                                'merchandise.name as name',
                                'cart.id', 
                                'cart.size', 
                                'cart.color', 
                                'cart.partiturdet_id', 
                                'cart.merchandise_id', 
                                'cart.choir_id', 
                                'cart.competition', 
                                DB::raw('
                                    CASE 
                                        WHEN cart.size IS NOT NULL AND cart.color IS NOT NULL THEN CONCAT(cart.size, " | ", cart.color)
                                        WHEN cart.size IS NOT NULL AND cart.color IS NULL THEN cart.size
                                        WHEN cart.size IS NULL AND cart.color IS NOT NULL THEN cart.color
                                        ELSE ""
                                    END AS file_type'
                                ),
                                'merchandise.harga', 
                                'cart.customer_id', 
                                DB::raw('sum(cart.qty) as total_quantity')
                            )
                            ->join('merchandise', 'cart.merchandise_id', '=', 'merchandise.id')
                            ->where('customer_id', auth()->guard('customer')->user()->id)
                            ->where('cart.merchandise_id', '<>', 0)
                            ->where('cart.partiturdet_id', '=', 0)
                            ->groupBy('cart.merchandise_id', 'cart.customer_id', 'file_type');

        // Combine the two queries with union
        $cartItems = $partiturItems->union($merchandiseItems)->get();

            // dd($cartItems);

        $shipment = Shipment::where('customer_id',Auth::guard('customer')->user()->id)->where('is_default',1)->first();

        $shippingId = (isset($shipment) && $shipment->count() > 0) ? $shipment->id : 0;

        $shippingCost = 0;
        if($shipment) {
            $shippingCost = $this->calculateShippingCost($shipment->provinsi,$shipment->negara);
        }

        // dd($this->calculateShippingCost($shipment->provinsi,$shipment->negara));

        return view('checkout', compact('cartItems','shipment','shippingCost', 'shippingId'));
    }

    public function checkoutCustom($id)
    {
        // Temporarily disable ONLY_FULL_GROUP_BY
        DB::statement("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");
        // First, retrieve partitur items with partiturdet_id != 0
        $partiturItems = Cart::select(DB::raw('SUBSTRING_INDEX(partitur.file_image, ",", 1) as file_image'), 
                                    'partitur_detail.name as name', 
                                    'cart.id', 
                                    'cart.size', 
                                    'cart.color', 
                                    'cart.partiturdet_id', 
                                    'cart.merchandise_id', 
                                    'cart.choir_id', 
                                    'cart.competition', 
                                    'partitur_detail.file_type', 
                                    'partitur_detail.harga', 
                                    'cart.customer_id', 
                                    DB::raw('sum(cart.qty) as total_quantity'))
                            ->join('partitur_detail', 'partitur_detail.id', '=', 'cart.partiturdet_id')
                            ->join('partitur', 'partitur.id', '=', 'partitur_detail.partitur_id')
                            ->where('customer_id', auth()->guard('customer')->user()->id)
                            ->where('cart.partiturdet_id', '<>', 0)
                            ->groupBy('cart.partiturdet_id', 'cart.customer_id');

        // Second, retrieve merchandise items with merchandise_id != 0 and partiturdet_id == 0
        $merchandiseItems = Cart::select(
                                DB::raw('SUBSTRING_INDEX(merchandise.photo, ",", 1) as file_image'), 
                                'merchandise.name as name',
                                'cart.id', 
                                'cart.size', 
                                'cart.color', 
                                'cart.partiturdet_id', 
                                'cart.merchandise_id', 
                                'cart.choir_id', 
                                'cart.competition', 
                                DB::raw('
                                    CASE 
                                        WHEN cart.size IS NOT NULL AND cart.color IS NOT NULL THEN CONCAT(cart.size, " | ", cart.color)
                                        WHEN cart.size IS NOT NULL AND cart.color IS NULL THEN cart.size
                                        WHEN cart.size IS NULL AND cart.color IS NOT NULL THEN cart.color
                                        ELSE ""
                                    END AS file_type'
                                ),
                                'merchandise.harga', 
                                'cart.customer_id', 
                                DB::raw('sum(cart.qty) as total_quantity')
                            )
                            ->join('merchandise', 'cart.merchandise_id', '=', 'merchandise.id')
                            ->where('customer_id', auth()->guard('customer')->user()->id)
                            ->where('cart.merchandise_id', '<>', 0)
                            ->where('cart.partiturdet_id', '=', 0)
                            ->groupBy('cart.merchandise_id', 'cart.customer_id', 'file_type');

        // Combine the two queries with union
        $cartItems = $partiturItems->union($merchandiseItems)->get();

            // dd($cartItems);
        $validation =  Shipment::where('customer_id',Auth::guard('customer')->user()->id)->where('id',$id)->first();

        if($validation){ 
            $shipment = $validation;
        } else {
            $shipment = Shipment::where('customer_id',Auth::guard('customer')->user()->id)->where('is_default',1)->first();
        }

        $shippingCost = 0;
        if($shipment) {
            $shippingCost = $this->calculateShippingCost($shipment->provinsi,$shipment->negara);
        }

        $shippingId = (isset($shipment) && $shipment->count() > 0) ? $shipment->id : 0;

        // dd($this->calculateShippingCost($shipment->provinsi,$shipment->negara));

        return view('checkout', compact('cartItems','shipment','shippingCost', 'shippingId'));
    }

    public function changeShipping(){
        $data = Shipment::where('customer_id',Auth::guard('customer')->user()->id)->orderBy('is_default', 'desc')->get();

        return view('choose-shipment', ['shipment'=>$data]);
    }

    private function calculateShippingCost($province, $countryCode)
    {
        $client = new Client();
        
        // Tentukan apakah pengiriman internasional berdasarkan countryCode
        $isInternational = $countryCode != 'Indonesia'; // Anggap 'ID' sebagai kode negara Indonesia
        
        // Pilih endpoint berdasarkan apakah pengiriman internasional atau domestik
        $url = $isInternational ? 'https://pro.rajaongkir.com/api/v2/internationalCost' : 'https://pro.rajaongkir.com/api/cost';

        // dd($province,$countryCode);
        if(!$isInternational){
            // convert $province to id dari table provinces where province = $province
            $province = DB::table('provinces')->where('province',$province)->first()->province_id;
        }

        // buat variable $country_id untuk menampung id dari table countries where country_name = $countryCode
        $country_id = DB::table('countries')->where('country_name',$countryCode)->first()->country_id;

        $formParams = $isInternational ? [
            'origin' => '22', 
            'destination' => $country_id, 
            'weight' => 1400, 
            'courier' => 'pos' 
        ] : [
            'origin' => '9', 
            'originType' => 'subdistrict', 
            'destination' => $province, 
            'destinationType' => 'subdistrict', 
            'weight' => 1400, 
            'courier' => 'pos' 
        ];

        // dd($formParams, $url);

        try {
            $response = $client->request('POST', $url, [
                'headers' => [
                    'key' => env('RAJAONGKIR_API_KEY'), // Gunakan API Key dari file .env
                ],
                'form_params' => $formParams
            ]);

            $body = $response->getBody();
            $result = json_decode($body);
            // return  $result;
            $shippingCost = $isInternational
                ? $result->rajaongkir->results[0]->costs[0]->cost
                : $result->rajaongkir->results[0]->costs[0]->cost[0]->value;

            return $shippingCost;
        } catch (\Exception $e) {
            // return $e for error
            return $e;
        }
    }


    public function getCartData()
    {
        // Temporarily disable ONLY_FULL_GROUP_BY
        DB::statement("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");

        // First, retrieve partitur items with partiturdet_id != 0
        $partiturItems = Cart::select(DB::raw('SUBSTRING_INDEX(partitur.file_image, ",", 1) as file_image'), 
                                    'partitur_detail.name as name', 
                                    'cart.id', 
                                    'cart.partiturdet_id', 
                                    DB::raw('0 as stok'),
                                    'cart.merchandise_id', 
                                    'cart.choir_id', 
                                    'cart.competition', 
                                    'partitur_detail.file_type', 
                                    'partitur_detail.harga', 
                                    'cart.customer_id',
                                    DB::raw('sum(cart.qty) as total_quantity'))
                            ->join('partitur_detail', 'partitur_detail.id', '=', 'cart.partiturdet_id')
                            ->join('partitur', 'partitur.id', '=', 'partitur_detail.partitur_id')
                            ->where('customer_id', auth()->guard('customer')->user()->id)
                            ->where('cart.partiturdet_id', '<>', 0)
                            ->groupBy('cart.partiturdet_id', 'cart.customer_id');

        // Second, retrieve merchandise items with merchandise_id != 0 and partiturdet_id == 0
        $merchandiseItems = Cart::select(
                                DB::raw('SUBSTRING_INDEX(merchandise.photo, ",", 1) as file_image'), 
                                'merchandise.name as name',
                                'cart.id', 
                                'cart.partiturdet_id', 
                                'merchandise.stok', 
                                'cart.merchandise_id', 
                                'cart.choir_id', 
                                'cart.competition', 
                                DB::raw('
                                    CASE 
                                        WHEN cart.size IS NOT NULL AND cart.color IS NOT NULL THEN CONCAT(cart.size, " | ", cart.color)
                                        WHEN cart.size IS NOT NULL AND cart.color IS NULL THEN cart.size
                                        WHEN cart.size IS NULL AND cart.color IS NOT NULL THEN cart.color
                                        ELSE ""
                                    END AS file_type'
                                ),
                                'merchandise.harga', 
                                'cart.customer_id', 
                                DB::raw('sum(cart.qty) as total_quantity')
                            )
                            ->join('merchandise', 'cart.merchandise_id', '=', 'merchandise.id')
                            ->where('customer_id', auth()->guard('customer')->user()->id)
                            ->where('cart.merchandise_id', '<>', 0)
                            ->where('cart.partiturdet_id', '=', 0)
                            ->groupBy('cart.merchandise_id', 'cart.customer_id', 'file_type');

        // Combine the two queries with union
        $cartItems = $partiturItems->union($merchandiseItems)->get();

        $cartItems = $cartItems->map(function ($item) {
            $fileImagePath = 'public/' . $item->file_image;
            $item->file_image = file_exists($fileImagePath) && $item->file_image ? asset($fileImagePath) : asset('assets/images/favicon.png');
            return $item;
        });

        return response()->json($cartItems);
    }



    public function updateDetail(Request $request)
{
    // Validasi request
    $request->validate([
        'id' => 'required|integer',
        'forCompetition' => 'sometimes|boolean',
        'choirId' => 'sometimes|integer',
        'quantitymin' => 'sometimes|integer|min:1',
        'quantityplus' => 'sometimes|integer|min:1',
        'type' => 'required|string',
        'stok' => 'required|integer',
        'quantity' => 'sometimes|integer|min:1'
    ]);

    // Ambil item keranjang berdasarkan ID dan customer
    $cart = Cart::where('customer_id', Auth::guard('customer')->user()->id)->where('id', $request->id)->first();

    if (!$cart) {
        return response()->json(['message' => 'Cart item not found.'], 404);
    }

    // Update field yang ada dalam request
    if ($request->has('forCompetition')) {
        $cart->competition = $request->forCompetition;
    }

    if ($request->has('choirId')) {
        $cart->choir_id = $request->choirId;
    }

    // Simpan perubahan pada detail keranjang
    $cart->save();

    // Validasi dan update quantity
    if ($request->has('quantitymin') || $request->has('quantityplus')) {
        if ($request->type == 'merchandise' && $request->has('quantityplus') && $cart->qty + 1 > $request->stok - 1) {
            return response()->json(['message' => 'Quantity cannot exceed stock.'], 400);
        }
        if ($request->has('quantitymin')) {
            $cart->qty--;
        }
        if ($request->has('quantityplus')) {
            $cart->qty++;
        }
        $cart->save();
    }
    
    if ($request->has('quantity')) {
        if ($request->type == 'merchandise' && $cart->qty + 1 > $request->stok - 1) {
            return response()->json(['message' => 'Quantity cannot exceed stock.'], 400);
        }
        $cart->qty = $request->quantity; // Update quantity dari input
        $cart->save();
    }

    return response()->json(['message' => 'Cart updated successfully.']);
}


    // getaddressbyid
    public function getAddressById($id)
    {
        $address = Choir::where('customer_id', $id)
                        ->get(['id', 'name', 'is_default']); 
        return response()->json(['choirs' => $address]);
    }

    public function updateCompetitionStatus(Request $request)
    {
        // Validasi request
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'required|integer', 
            'forCompetition' => 'required|boolean',
        ]);

        // Mengambil data dari request
        $ids = $request->ids;
        $status = $request->forCompetition;

        // Update status kompetisi untuk cart items
        Cart::where('customer_id',Auth::guard('customer')->user()->id)->update(['competition' => $status]);

        // Respons sukses
        return response()->json([
            'message' => 'Cart status updated successfully.'
        ]);
    }

    public function updateChoirStatus(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'required|integer',
            'choirId' => 'required|integer', 
        ]);

        $ids = $request->ids;
        $choirId = $request->choirId;

        Cart::where('customer_id',Auth::guard('customer')->user()->id)->update(['choir_id' => $choirId]);
        
        return response()->json([
            'message' => 'Choir updated successfully for selected carts.'
        ]);
    }
    
    public function addToCart(Request $request)
    {
        if (auth()->guard('customer')->check()) {
        if($request->merchandise){
            $productId = $request->productId;
            $quantity = $request->quantity;
            $size = $request->size;
            $color = $request->color;
            // Retrieve the Merchandise details from the database
            $merchandise = Merchandise::find($productId);

            if (!$merchandise) {
                return response()->json(['status' => 'error', 'message' => 'Merchandise not found.'], 404);
            }

            // Insert the cart data into the Cart model
            Cart::create([
                'customer_id' => auth()->guard('customer')->user()->id,
                'partiturdet_id' => 0,
                'merchandise_id' => $productId,
                'choir_id' => 0, 
                'competition' => false, 
                'qty' => $quantity,
                'size' => $size,
                'color' => $color,
                'subtotal' => 0,
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => null, 
                'deleted_at' => null, 
                'created_by' => auth()->guard('customer')->user()->id, 
                'updated_by' => null, 
                'deleted_by' => null, 
            ]);

            return response()->json(['message' => 'Product added to cart successfully!']);
        }else{
            
                $productId = $request->productId;
                $quantity = $request->quantity;

                // Retrieve the Partitur details from the database
                $partitur = PartiturDetail::find($productId);
                $namaPartitur = $partitur->name; 
                // dd($partitur);
                if (!$partitur) {
                    return response()->json(['status' => 'error', 'message' => 'Partitur not found.'], 404);
                }

                $choir = Choir::where('customer_id',Auth::guard('customer')->user()->id)->where('is_default',1)->first();
                if(!$choir){
                    $choirId = 0;
                }else{
                    $choirId = $choir->id;
                }
                // Insert the cart data into the Cart model
                Cart::create([
                    'customer_id' => auth()->guard('customer')->user()->id,
                    'partiturdet_id' => $productId,
                    'merchandise_id' => 0,
                    'choir_id' => $choirId, 
                    'competition' => false, 
                    'qty' => $quantity,
                    'subtotal' => 0,
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => null, 
                    'deleted_at' => null, 
                    'created_by' => auth()->guard('customer')->user()->id, 
                    'updated_by' => null, 
                    'deleted_by' => null, 
                ]);

                return response()->json(['message' => 'Product added to cart successfully!']);
            
        }
        } else {
                return response()->json(['status' => 'login_required', 'message' => 'Login is required to add items to the cart.']);
            }

        
    }

    public function delete($id)
    {
        // Logika untuk menghapus item dari keranjang
        // Ini bisa berupa penghapusan item dari session, database, atau storage lainnya tergantung implementasi Anda
        // Cart::where('partiturdet_id', $id)->forceDelete();

        $cart = Cart::find($id);
        if($cart->partiturdet_id == 0){
            // merchandise
            Cart::where('customer_id',auth()->guard('customer')->user()->id)->where('merchandise_id', $cart->merchandise_id)->where('size', $cart->size)->where('color', $cart->color)->forceDelete();
        }else{
            // sheet music
            Cart::where('customer_id',auth()->guard('customer')->user()->id)->where('partiturdet_id', $cart->partiturdet_id)->forceDelete();
        }

        return response()->json(['success' => true, 'message' => 'Item deleted successfully']);
    }

    public function update(Request $request)
    {

        // dd($request->all());
        // Logika untuk memperbarui keranjang

        // Misalnya, Anda bisa mengambil data item dari request dan memperbarui keranjang
        $cartItems = $request->input('items');
        foreach ($cartItems as $item) {
            // Update cart items logic here
            Cart::where('partiturdet_id', $item['id'])->update([
                'qty' => $item['quantity'],
                'subtotal' => $item['quantity'] * $item['harga'],
                'competition' => $item['forCompetition'] ? 1 : 0,
                'choir_id' => $item['choirId'],
                'updated_at' => \Carbon\Carbon::now(),
                'updated_by' => auth()->guard('customer')->user()->id,
            ]);
        }

        // Setelah pembaruan, redirect ke halaman checkout
        return redirect()->route('checkout');
    }

    public function voucher(Request $request)
    {
        $voucherCode = $request->input('voucher');
        $voucherExists = Voucher::where('name', $voucherCode);
        $disc = 0;

        if($voucherExists->exists()){
            $disc = $voucherExists->first()->potongan;
        }
        
        return response()->json(['exists' => $voucherExists->exists(), 'disc'=>$disc]);
    }

}