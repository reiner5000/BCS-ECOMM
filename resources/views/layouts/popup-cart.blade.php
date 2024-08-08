<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="popup-right mobile-popup-background" id="right-cart">
    <div class="cart-modal mobile-popup">
        <div class="modal-row sticky-title align-items-center bg-white">
            <div class="modal-title">My Cart</div>
            <button class="modal-exit popup-trigger" target-popup="right-cart"><i class="fa-solid fa-x"></i></button>
        </div>

        <div class="cart-col">

        </div>
        <div class="modal-col-custom">
            <div class="modal-row">
                <div class="modal-hint">Organize choirs for all products</div>
                <select class="cart-choir-select selectAll" id="selectAll">
                </select>
            </div>
            <div class="modal-row" style="margin-top: 5px;">
                <label class="custom-checkbox competition-hidden">
                    <input type="checkbox" id="checkboxAll" name="checkboxAll">
                    <span class="checkmark"></span>
                    Arrange all products for use in competitions
                </label>
            </div>

            <div class="modal-row" style="margin-top: 20px; margin-bottom: 20px;">
                <div class="cart-detail-title">Subtotal</div>
                <div class="cart-detail-price totalsemua">Rp 0</div>
            </div>
            <div class="modal-row right-flex pb-30">
                <button class="btn btn-black popup-trigger" target-popup="right-cart">Cancel</button>
                <button class="btn-white btn" id="checkoutButton">Checkout</button>
            </div>
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

        function updateCartItem(id, data) {
            fetch('/cart/updateDetail', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify({
                        id: id,
                        ...data
                    })
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

            fetch('/cart/update-competition', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
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
            const cartIds = Array.from(document.querySelectorAll('input[name="idCart[]"]')).map(input =>
                input.value);

            const otherSelectElements = document.querySelectorAll('.cart-choir-select');
            otherSelectElements.forEach(function(selectElement) {
                selectElement.value = selectedChoirId;
            });

            updateCartChoirs(cartIds, selectedChoirId);
        });

        function updateCartChoirs(ids, choirId) {
            const data = {
                ids: ids,
                choirId: choirId
            };

            fetch('/cart/update-choir', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
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

        function changeQuantity(event, isIncrement) {
            const qtyGroup = event.target.closest('.qty-group2');
            if (qtyGroup) {
                const input = qtyGroup.querySelector('input[type="number"]');
                if (input) {
                    const currentValue = parseInt(input.value, 10);
                    input.value = isIncrement ? currentValue + 1 : Math.max(0, currentValue - 1);
                    calculateAndUpdateSubtotal
                        ();
                }
            }
        }

        const fetchCartData = () => {
            fetch('/cart-data')
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
                console.log('Item:', item);

                subtotal += Number(item.harga) * Number(item.total_quantity);
                var choirs = @json($choir);

                let optionsHtml = choirs.map(choir => {
                    const isSelected = choir.id === item.choir_id ? 'selected' : '';
                    return `<option value="${choir.id}" ${isSelected}>${choir.name}</option>`;
                }).join('');

                const checkedAttribute = item.competition === 1 ? 'checked' : '';

                // const sizeColorHtml = item.file_type === "Merchandise" ?
                //     `<div>Size: ${item.size}, Color: ${item.color}</div>` : '';

                const cartDetail = document.createElement('div');
                cartDetail.classList.add('cart-detail');

                let html = `
                <div class="cart-detail-img">
                    <img src="{{ asset('public/${item.file_image}') }}" />
                </div>

                <div class="cart-detail-desc">
                    <div class="modal-row">
                        <input type="hidden" name="idCart[]" value="${item.partiturdet_id}">
                        <div class="cart-detail-title">${item.name}</div>
                        <button class="modal-exit" data-item-id="${item.partiturdet_id}"><i class="fa-solid fa-trash-can"></i></button>
                    </div>
                    <div class="cart-detail-voicetype">${item.file_type}</div>
                    <div class="modal-row">
                        <div class="cart-detail-price" data-item-price="${item.harga}" >Rp ${item.harga.toLocaleString()}</div>

                        <div class="qty-container">
                            <div class="qty-group2">
                                <button class="btn-min">-</button>
                                <input type="hidden" id="stock-7" name="stock[]" value="${item.stok}" />`;
                                html += `<input type="number" id="det-7" name="qty[]" value="${item.total_quantity}" readonly />
                                <button class="btn-plus">+</button>
                            </div>
                            <div class="qty-hint">&nbsp;</div>
                        </div>
                    </div>
                    <div class="modal-row">
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
            cartDetail.innerHTML = html;
                cartContainer.appendChild(cartDetail);

                cartDetail.querySelector('.modal-exit').addEventListener('click', function(event) {
                    const itemId = event.currentTarget.getAttribute('data-item-id');
                    console.log('Delete item:', itemId);
                    deleteCartItem(itemId);
                });

                cartDetail.querySelectorAll('.checkbox-competition').forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        const cartId = this.closest('.cart-detail').querySelector(
                            'input[name="idCart[]"]').value;
                        updateCartItem(cartId, {
                            forCompetition: this.checked
                        });
                    });
                });

                cartDetail.querySelectorAll('.select-choir').forEach(select => {
                    select.addEventListener('change', function() {
                        const cartId = this.closest('.cart-detail').querySelector(
                            'input[name="idCart[]"]').value;
                        updateCartItem(cartId, {
                            choirId: this.value
                        });
                    });
                });

                cartDetail.querySelectorAll('.btn-min').forEach(btnmin => {
                    btnmin.addEventListener('click', function() {
                        const cartId = this.closest('.cart-detail').querySelector(
                            'input[name="idCart[]"]').value;
                        const closestInput = this.closest('.cart-detail')
                            .querySelector('input[name="qty[]"]').value;
                        let newQty = closestInput;
                        updateCartItem(cartId, {
                            quantitymin: newQty
                        });
                    });
                });

                cartDetail.querySelectorAll('.btn-plus').forEach(btnmax => {
                    btnmax.addEventListener('click', function() {
                        const cartId = this.closest('.cart-detail').querySelector(
                            'input[name="idCart[]"]').value;
                        const closestInput = this.closest('.cart-detail')
                            .querySelector('input[name="qty[]"]').value;
                        let newQty = closestInput;
                        updateCartItem(cartId, {
                            quantityplus: newQty
                        });
                    });
                });

                const inputNumber = cartDetail.querySelector('input[type="number"]');
                inputNumber.addEventListener('change', calculateAndUpdateSubtotal);

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
        const customerId = '{{ auth()->guard('customer')->user()->id ?? '' }}';
        fetchChoirs(customerId);

        // Add event listeners to your inc/dec buttons
        document.querySelectorAll('.qty-group button').forEach(button => {
            button.addEventListener('click', event => {
                if (button.textContent === '+') {
                    incClosestInputNew(event);
                } else {
                    decClosestInputNew(event);
                }
            });
        });

        function capitalize(s) {
            return s[0].toUpperCase() + s.slice(1);
        }

        // Include your incClosestInput and decClosestInput functions as well
        function incClosestInputNew(event) {
            // console.log('test1');
            const parentContainer = event.currentTarget.closest('.qty-group');
            const closestInput = parentContainer.querySelector('input[type="number"]');
            closestInput.value++;
            calculateAndUpdateSubtotal(); // Memperbarui subtotal setelah peningkatan
            let newQty = closestInput.value;
            const cartId = cartDetail.querySelector('input[name="idCart[]"]').value;
            updateCartItem(cartId, {
                quantity: newQty
            }); // Kirim update ke server
        }

        function decClosestInputNew(event) {
            // console.log('test2');
            const parentContainer = event.currentTarget.closest('.qty-group');
            const closestInput = parentContainer.querySelector('input[type="number"]');
            if (closestInput.value > closestInput.min) {
                closestInput.value--;
                calculateAndUpdateSubtotal(); // Memperbarui subtotal setelah pengurangan

                let newQty = closestInput.value;
                const cartId = cartDetail.querySelector('input[name="idCart[]"]').value;
                updateCartItem(cartId, {
                    quantity: newQty
                }); // Kirim update ke server
            }
        }


        async function fetchChoirs(customerId, choirId = null) {
            try {
                const getAddressByIdUrl = '{{ route('get.address.by.id', ['id' => '__id__']) }}';
                const response = await fetch(getAddressByIdUrl.replace('__id__', customerId));
                const data = await response.json();

                const selectElements = document.querySelectorAll('.selectAll');
                selectElements.forEach(select => {
                    select.innerHTML = '';

                    data.choirs.forEach(choir => {
                        const option = new Option(choir.name, choir.id);
                        select.appendChild(option);
                    });
                });
            } catch (error) {
                console.error('Error fetching choir data:', error);
            }
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
            fetch(`{{ route('cart.delete', ['id' => '__itemId__']) }}`.replace('__itemId__', itemId), {
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
