<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="popup-nav-container" id="nav-mobile">
    <div class="popup-nav">
        <div class="modal-row sticky-title pb-20">
        </div>

        <div class="modal-nav-links">
            <a class="upper-nav-link {{ request()->routeIs('homepage') ? 'active' : '' }}" onclick="javascript:location.href='{{ route('homepage') }}'">Home</a>
            <a class="upper-nav-link {{ request()->routeIs('publisher') || request()->routeIs('publisher-detail') ? 'active' : '' }}" onclick="javascript:location.href='{{ route('publisher') }}'">Publisher</a>
            <a class="upper-nav-link {{ request()->routeIs('composer') || request()->routeIs('composer-detail') ? 'active' : '' }}" onclick="javascript:location.href='{{ route('composer') }}'">Composer</a>
            <a class="upper-nav-link {{ request()->routeIs('collection') || request()->routeIs('collection-detail') ? 'active' : '' }}" onclick="javascript:location.href='{{ route('collection') }}'">Collection</a>
            <a class="upper-nav-link" href="https://www.bandungchoral.com/contact-us">Contact Us</a>
        </div>

        <div class="modal-nav-login">
            <hr/>
            @guest('customer')
                <div class="row justify-content-end mb-20">
                    <button class="btn btn-muted" type="button" onclick="javascript:location.href='{{ route('login') }}'">LOGIN</button>
                    <button class="btn btn-white border-muted-black" type="button" onclick="javascript:location.href='{{ route('register') }}'">Register</button>
                </div>
            @endguest

            @auth('customer')
            <div class="row justify-content-between mb-20">
                <div class="button-line">
                    <button class="nav-profile align-items-center gap-10" type="button" onclick="javascript:location.href='{{ route('profile') }}'">
                    @if(Auth::guard('customer')->user()->photo_profile == null)
                        <img src="{{ asset('public/uploads/default.jpg') }}" />
                    @else
                        <img src="{{ asset('public/'.Auth::guard('customer')->user()->photo_profile) }}" />
                    @endif
                    <div class="profile-name"><b>{{ Auth::guard('customer')->user()->name }}</b></div>
                    </button>
                </div>

                <button type="submit" class="btn btn-white border-muted-black" onclick="logout()">Logout</button>
            </div>
            @endauth
        </div>
    </div>
</div>

<script>
    
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('checkboxAll').addEventListener('change', function() {
            const isChecked = this.checked;
            const detailCheckboxes = document.querySelectorAll('.cart-detail input[type="checkbox"]');
            const cartIds = document.querySelectorAll('input[name="idCart[]"]');

            detailCheckboxes.forEach(function(checkbox) {
                checkbox.checked = isChecked;
            });

            const ids = Array.from(cartIds).map(input => input.value);

            updateCartCompetitionStatus(ids, isChecked);
        });

        function updateCartItem(id, type, data) {
            fetch('{{route('cart.updateDetail')}}', { 
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ id: id, type: type, ...data })
            })
            .then(response => response.json())
            .then(data => {
                console.log('Update successful:', data);
            })
            .catch(error => {
                console.error('Error updating cart item:', error);
            });
        }

        function updateCartCompetitionStatus(cartIds, status) {
            const data = {
                ids: cartIds,
                forCompetition: status
            };
            
            fetch('{{route('cart.updateCompetition')}}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                console.log('Success:', data);
            })
            .catch((error) => {
                console.error('Error:', error);
            });
        }
        
        document.getElementById('selectAll').addEventListener('change', function() {
            const selectedChoirId = this.value;
            const cartIds = Array.from(document.querySelectorAll('input[name="idCart[]"]')).map(input => input.value);

            const otherSelectElements = document.querySelectorAll('.cart-choir-select');
            otherSelectElements.forEach(function(selectElement) {
                selectElement.value = selectedChoirId;
            });

            updateCartChoirs(cartIds, selectedChoirId);
        });

        function updateCartChoirs(ids, choirId) {
            const data = { ids: ids, choirId: choirId };

            fetch('{{ route('cart.updateChoir') }}', { 
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('Success:', data);
            })
            .catch((error) => {
                console.error('Error:', error);
            });
        }

        document.querySelector('.cart-modal').addEventListener('click', function(event) {
            // Periksa jika target event adalah tombol + atau -
            const isIncrementButton = event.target.closest('button') && event.target.closest('button')
                .textContent === '+';
            const isDecrementButton = event.target.closest('button') && event.target.closest('button')
                .textContent === '-';

            if (isIncrementButton) {
                changeQuantity(event, true);
            } else if (isDecrementButton) {
                changeQuantity(event, false);
            }
        });

        // function changeQuantity(event, isIncrement) {
        //     const qtyGroup = event.target.closest('.qty-group');
        //     if (qtyGroup) {
        //         const input = qtyGroup.querySelector('input[type="number"]');
        //         const setok = qtyGroup.querySelector('input[type="hidden"]');
        //         const currentValue = parseInt(input.value, 10);
        //         let valnext = isIncrement ? currentValue + 1 : Math.max(0, currentValue - 1);
                
        //         const cartDetailVoicetype = qtyGroup.closest('.cart-detail').querySelector('.cart-detail-voicetype').textContent.toLowerCase();
        //         const isSoftcopy = cartDetailVoicetype.includes('softcopy');
        //         const isHardcopy = cartDetailVoicetype.includes('hardcopy');

        //         if (isSoftcopy || isHardcopy) {
        //             // Increment atau decrement tanpa memeriksa stok untuk softcopy dan hardcopy
        //             if (isIncrement) {
        //                 input.value = valnext;
        //             } else {
        //                 if (valnext >= 1) {
        //                     input.value = valnext;
        //                 }
        //             }
        //         } else {
        //             // Increment atau decrement dengan memeriksa stok untuk selain softcopy dan hardcopy
        //             if (!isIncrement) {
        //                 if (valnext >= 1) {
        //                     input.value = valnext;
        //                 }
        //             } else {
        //                 if (valnext <= parseInt(setok.value, 10)) {
        //                     input.value = valnext;
        //                 }
        //             }
        //         }

        //         calculateAndUpdateSubtotal();
        //     }
        // }
        
         function changeQuantity(event, isIncrement) {
            const qtyGroup = event.target.closest('.qty-group');
            if (qtyGroup) {
                const input = qtyGroup.querySelector('input[type="number"]');
                const setok = qtyGroup.querySelector('input[type="hidden"]');
                const currentValue = parseInt(input.value, 10) || {{ $item->minimum_order ?? 20 }}; // Default ke 20 jika input tidak valid
                let valnext = isIncrement ? currentValue + 1 : Math.max({{ $item->minimum_order ?? 20 }}, currentValue - 1); // Batasi minimum 20
        
                const cartDetailVoicetype = qtyGroup.closest('.cart-detail').querySelector('.cart-detail-voicetype').textContent.toLowerCase();
                const isSoftcopy = cartDetailVoicetype.includes('softcopy');
                const isHardcopy = cartDetailVoicetype.includes('hardcopy');
        
                if (isSoftcopy || isHardcopy) {
                    // Increment atau decrement tanpa memeriksa stok untuk softcopy dan hardcopy
                    input.value = valnext;
                } else {
                    // Increment atau decrement dengan memeriksa stok untuk selain softcopy dan hardcopy
                    if (isIncrement) {
                        if (valnext <= parseInt(setok.value, 10)) {
                            input.value = valnext;
                        }
                    } else {
                        input.value = valnext;
                    }
                }
        
                // Set nilai minimal ke 20 jika di bawah 20
                if (parseInt(input.value, 10) < {{ $item->minimum_order ?? 20 }}) {
                    input.value = {{ $item->minimum_order ?? 20 }};
                }
                
                calculateAndUpdateSubtotal();
            }
        }



        const fetchCartData = () => {
            fetch('{{ route('cart.data') }}')
                .then(response => response.json())
                .then(cartItems => {
                    updateCartModal(cartItems);
                })
                .catch(error => console.error('Error fetching cart data:', error));
        };

        const updateCartModal = (cartItems) => {
            const cartContainer = document.querySelector('.cart-col');
            cartContainer.innerHTML = ''; // Clear existing items

            let subtotal = 0;

            cartItems.forEach(item => {
                // console.log('Item:', item);

                subtotal += Number(item.harga) * Number(item.total_quantity);
                var choirs = @json($choir);

                let optionsHtml = choirs.map(choir => {
                    const isSelected = choir.id === item.choir_id ? 'selected' : '';
                    return `<option value="${choir.id}" ${isSelected}>${choir.name}</option>`;
                }).join('');
                    
                const checkedAttribute = item.competition == 1 ? 'checked' : '';

                const cartDetail = document.createElement('div');
                cartDetail.classList.add('cart-detail');

                const choirStyle = item.partiturdet_id == 0 ? 'style="display: none;"' : '';

                cartDetail.innerHTML = `
                <div class="cart-detail-img">
                    <img src="${item.file_image}" />
                </div>

                <div class="cart-detail-desc">
                    <div class="modal-row">
                        <input type="hidden" name="idCart[]" value="${item.id}">
                        <input type="hidden" name="type[]" value="${item.partiturdet_id==0?'merchandise':'sheetmusic'}">
                        <div class="cart-detail-title">${item.name}</div>
                        <button class="modal-exit" data-item-id="${item.id}"><i class="fa-solid fa-trash-can"></i></button>
                    </div>
                    <div class="cart-detail-voicetype">${item.file_type}</div>
                    <div class="modal-row">
                        <div class="cart-detail-price" data-item-price="${item.harga}" >Rp ${formatHarga(item.harga)}</div>

                        <div class="qty-container">
                            <div class="qty-group">
                                <button class="btn-min">-</button>
                                <input type="hidden" id="stock-7" name="stock[]" value="${item.stok}" />
                                <input type="number" id="det-7" name="qty[]" value="${item.total_quantity}" min="{{ $item->minimum_order ?? 20 }}" />
                                <button class="btn-plus">+</button>
                            </div>
                            <div class="qty-hint">&nbsp;</div>
                        </div>
                    </div>

                    <div class="modal-row" ${choirStyle}>
                        <label class="custom-checkbox competition-hidden">
                            <input type="checkbox" class="checkbox-competition" id="checkbox1" name="checkbox1" ${checkedAttribute}>
                            <span class="checkmark"></span>
                            Competition
                        </label>

                        <select class="cart-choir-select select-choir" id="selectchoir" name="selectchoir">
                        ${optionsHtml}
                        </select>
                    </div>
                </div>
            `;
                cartContainer.appendChild(cartDetail);

                cartDetail.querySelector('.modal-exit').addEventListener('click', function(event) {
                    const itemId = event.currentTarget.getAttribute('data-item-id');
                    console.log('Delete item:', itemId);
                    deleteCartItem(itemId);
                });

                cartDetail.querySelectorAll('.checkbox-competition').forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        const cartId = this.closest('.cart-detail').querySelector('input[name="idCart[]"]').value;
                        const type = this.closest('.cart-detail').querySelector('input[name="type[]"]').value;
                        updateCartItem(cartId, type, { forCompetition: this.checked });
                    });
                });

                cartDetail.querySelectorAll('.select-choir').forEach(select => {
                    select.addEventListener('change', function() {
                        const cartId = this.closest('.cart-detail').querySelector('input[name="idCart[]"]').value;
                        const type = this.closest('.cart-detail').querySelector('input[name="type[]"]').value;
                        updateCartItem(cartId, type, { choirId: this.value });
                    });
                });

                cartDetail.querySelectorAll('.btn-min').forEach(btnmin => {
                    btnmin.addEventListener('click', function() {
                        const cartId = this.closest('.cart-detail').querySelector('input[name="idCart[]"]').value;
                        const closestInput = this.closest('.cart-detail').querySelector('input[name="qty[]"]').value;
                        const type = this.closest('.cart-detail').querySelector('input[name="type[]"]').value;
                        const closestStok = this.closest('.cart-detail').querySelector('input[name="stock[]"]').value;
                        let newQty = closestInput;
                        if(newQty>{{ $item->minimum_order ?? 20 }}){
                        updateCartItem(cartId, type, { quantitymin: newQty,stok :closestStok}); 
                        }
                    });
                });

                cartDetail.querySelectorAll('.btn-plus').forEach(btnmax => {
                    btnmax.addEventListener('click', function() {
                        const cartId = this.closest('.cart-detail').querySelector('input[name="idCart[]"]').value;
                        const closestInput = this.closest('.cart-detail').querySelector('input[name="qty[]"]').value;
                        const type = this.closest('.cart-detail').querySelector('input[name="type[]"]').value;
                        const closestStok = this.closest('.cart-detail').querySelector('input[name="stock[]"]').value;
                        let newQty = closestInput;
                        updateCartItem(cartId, type, { quantityplus: newQty, stok :closestStok}); 
                    });
                });
                
                
                
                const inputNumber = cartDetail.querySelector('input[name="qty[]"]');

                // Tambahkan event listener untuk input keyboard agar tidak bisa kurang dari 20
                inputNumber.addEventListener('change', function() {
                        const cartId = this.closest('.cart-detail').querySelector('input[name="idCart[]"]').value;
                        const closestInput = this.closest('.cart-detail').querySelector('input[name="qty[]"]').value;
                        const type = this.closest('.cart-detail').querySelector('input[name="type[]"]').value;
                        const closestStok = this.closest('.cart-detail').querySelector('input[name="stock[]"]').value;
                        let newQty = closestInput;
                        if(newQty>{{ $item->minimum_order ?? 20 }}){
                            updateCartItem(cartId, type, { quantity: newQty, stok :closestStok }); 
                        }else{
                            let newQty = {{ $item->minimum_order ?? 20 }};
                            this.value = {{ $item->minimum_order ?? 20 }};
                            updateCartItem(cartId, type, { quantity: newQty, stok :closestStok }); 
                        }
                        calculateAndUpdateSubtotal();
                });

            });



            const subtotalElement = document.querySelector('.totalsemua'); // Memperbaiki selector ini
            if (subtotalElement) {
                subtotalElement.textContent = `Rp ${formatHarga(subtotal)}`;
            }

            calculateAndUpdateSubtotal();

            // cartItems.forEach(item => {
            //     const customerId = item
            //         .customer_id; // Asumsikan setiap item memiliki customer_id, atau gunakan default
            //     fetchChoirs(customerId, item.choir_id);
            // });
        };

        // Trigger fetchCartData when opening the modal or based on your application logic
        // Example: document.querySelector('#yourModalOpenButton').addEventListener('click', fetchCartData);
        fetchCartData();
        const customerId = '{{ auth()->guard('customer')->user()->id??"" }}';
        fetchChoirs(customerId);

        // Add event listeners to your inc/dec buttons
        document.querySelectorAll('.qty-group2 button').forEach(button => {
            button.addEventListener('click', event => {
                if (button.textContent === '+') {
                    incClosestInput(event);
                } else {
                    decClosestInput(event);
                }
            });
        });

        function capitalize(s)
        {
            return s[0].toUpperCase() + s.slice(1);
        }

        // Include your incClosestInput and decClosestInput functions as well
        function incClosestInput(event) {
            // console.log('test1');
            const parentContainer = event.currentTarget.closest('.qty-group');
            const closestInput = parentContainer.querySelector('input[type="number"]');
            const closestInputStock = parentContainer.querySelector('input[type="hidden"]').value;
            closestInput.value++;
            calculateAndUpdateSubtotal(); // Memperbarui subtotal setelah peningkatan
            let newQty = closestInput.value;
            const cartId = cartDetail.querySelector('input[name="idCart[]"]').value;
            const type = cartDetail.querySelector('input[name="type[]"]').value;
            updateCartItem(cartId, type, { quantity: newQty,stok :closestInputStock }); // Kirim update ke server
        }

        function decClosestInput(event) {
            // console.log('test2');
            const parentContainer = event.currentTarget.closest('.qty-group');
            const closestInput = parentContainer.querySelector('input[type="number"]');
            if (closestInput.value > closestInput.min) {
                closestInput.value--;
                calculateAndUpdateSubtotal(); // Memperbarui subtotal setelah pengurangan
                
                let newQty = closestInput.value;
                const cartId = cartDetail.querySelector('input[name="idCart[]"]').value;
                const type = cartDetail.querySelector('input[name="type[]"]').value;
                updateCartItem(cartId, type, { quantity: newQty,stok :closestInputStock }); // Kirim update ke server
            }
        }


        function fetchChoirs(customerId,choirId=null) {
            const getAddressByIdUrl = '{{ route("get.address.by.id", ["id" => "__id__"]) }}';
            fetch(getAddressByIdUrl.replace('__id__', customerId))
                .then(response => response.json())
                .then(data => {
                    const selectElements = document.querySelectorAll('.selectAll');
                    selectElements.forEach(select => {
                        select.innerHTML = ''; // Bersihkan pilihan sebelumnya
                        data.choirs.forEach(choir => {
                            const option = new Option(choir.name, choir.id);
                            select.appendChild(option);
                        });
                    });
                })
                .catch(error => console.error('Error fetching choir data:', error));
        }

        function calculateAndUpdateSubtotal() {
            const cartItems = document.querySelectorAll('.cart-col > .cart-detail');
            let subtotal = 0;
            cartItems.forEach(item => {
                // Menghapus karakter non-numerik kecuali titik desimal untuk harga.
                // Asumsikan bahwa `textContent` dari `.cart-detail-price` mengandung harga per unit item.
                let hargaText = item.querySelector('.cart-detail-price').textContent;
                let harga = parseFloat(hargaText.replace(/[^\d,]/g, '').replace(/,/g, '.').replace(/\./g, '')); // Hapus semua kecuali angka dan titik.

                let totalQuantity = item.querySelector('input[type="number"]').value;
                totalQuantity = parseInt(totalQuantity, 10); // Pastikan itu adalah integer valid.

                if (!isNaN(harga) && !isNaN(totalQuantity)) {
                    subtotal += harga * totalQuantity;
                }
            });

            const subtotalElement = document.querySelector('.totalsemua');
            if (subtotalElement) {
                subtotalElement.textContent = `Rp ${formatHarga(subtotal)}`;
            }
        }

        function deleteCartItem(itemId) {
            const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
            if (!csrfTokenMeta) {
                console.error('CSRF token not found');
                return;
            }

            const csrfToken = csrfTokenMeta.getAttribute('content');
            const getAddressByIdUrl = '{{ route("cart.delete", ["id" => "__id__"]) }}';
            fetch(getAddressByIdUrl.replace('__id__', itemId), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        id: itemId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    // console.log('Item deleted:', data);
                    fetchCartData(); // Reload cart data to update the display
                })
                .catch(error => console.error('Error deleting cart item:', error));
        }

        document.getElementById('checkoutButton').addEventListener('click', function(e) {
            // e.preventDefault(); // Hentikan perilaku default jika dalam form

            // // Ambil data yang perlu dikirim, misalnya ID item dan kuantitasnya
            // const cartData = {
            //     items: Array.from(document.querySelectorAll('.cart-col > .cart-detail')).map(
            //         item => {
            //             return {
            //                 id: item.querySelector('.modal-exit').getAttribute('data-item-id'),
            //                 harga: item.querySelector('.cart-detail-price').getAttribute(
            //                     'data-item-price'),
            //                 quantity: parseInt(item.querySelector('input[type="number"]').value,
            //                     10),
            //                 forCompetition: item.querySelector('input[type="checkbox"]')
            //                     .checked,
            //                 choirId: item.querySelector('.cart-choir-select').value
            //             };
            //         })
            // };


            // const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // fetch('/cart/update', {
            //         method: 'POST',
            //         headers: {
            //             'Content-Type': 'application/json',
            //             'X-CSRF-TOKEN': csrfToken
            //         },
            //         body: JSON.stringify(cartData)
            //     })
            //     .then(response => {
            //         if (response.ok) {
                        // Redirect ke halaman checkout jika pembaruan sukses
                        window.location.href = '{{ route('checkout') }}';
                //     } else {
                //         throw new Error('Network response was not ok.');
                //     }
                // })
                // .catch(error => console.error('Error:', error));
        });




    });
</script>
