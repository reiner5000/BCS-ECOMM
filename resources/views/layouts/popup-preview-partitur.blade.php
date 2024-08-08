<div class="popup-center" id="preview_partitur">
    <div class="cart-modal py-l border-radius-4" style="min-height:0vh !important">
        <div class="modal-row align-items-center">
            <div class="modal-title" style="font-size:18px !important">Nama Produk (Partitur)</div>
            <button class="modal-exit modal-exit-preview popup-trigger" target-popup="preview_partitur" style="padding-left:20px"><i class="fa-solid fa-x"></i></button>
        </div>
        <div class="modal-content">
            <div class="preview_partitur_customer"></div>
        </div>
    </div>
</div>
<style>
    .preview_partitur_customer{
        width: 100%;
    }
    .preview_partitur_customer audio{
        width: 100%;
    }
    .preview_partitur_customer img{
        width: 100%;
        max-width:450px;
    }
    .preview_partitur_customer video{
        width: 100%;
        max-width:450px;
    }
</style>
<script>
    $(document).ready(function() {
        $('.modal-exit-preview').on('click', function() {
            $('.preview_partitur_customer').empty();
        });
    });
</script>