@include('layouts.import')

<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Forgot Password</title>

        <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" type="image/x-icon">
        <link rel="icon" href="{{ asset('assets/images/favicon.png') }}" type="image/x-icon">
    </head>

    <body>
        <div class="login-page show-first">
            <div class="login-form-col">
                <div class="login-form-title">Forgot Password</div>
                <div class="login-form-sub">Bandung Choral Society</div>

                @error('error')
                    <div style="color: red;">
                        {{ $message }}
                    </div>
                @endif
                <!-- <div class="login-form-group">
                <div class="login-form-hint">Buat password baru yang akan anda gunakan untuk menggantikan password lama anda.</div>
                </div> -->
                <input type="hidden" id="token" name="token" value="{{$token}}"/>

                <div class="login-form-group">
                    <label for="password">Password</label>
                    <div class="login-input-group">
                        <input class="required" type="password" id="password" name="password" placeholder="Password" required/>
                        <i class="fa-regular fa-eye-slash" id="toggle-password-visibility"></i>
                    </div>
                    <div class="input-hint" style="font-size: 18px; margin: 4px 0 0 0;">*Required*</div>
                </div>

                <div class="login-form-group">
                    <label for="konfirmasi-password">Confirm Password</label>
                    <div class="login-input-group">
                        <input class="required" type="password" id="konfirmasi-password" name="konfirmasi-password" placeholder="Confirm Password" required/>
                        <i class="fa-regular fa-eye-slash" id="toggle-confirm-password-visibility"></i>
                    </div>
                    <div class="input-hint" style="font-size: 18px; margin: 4px 0 0 0;">*Required*</div>
                </div>

                <div class="login-form-group">
                <button class="login-button" onclick="resetPasswordAction()">Confirm</button>
                </div>
            </div>
            <div class="login-banner-col">
                <img src="{{ asset('assets/images/bcs_logo.png') }}" />
                <div class="login-banner-title">Belanja Musisi: Pilih, Beli, Berkreasi</div>
            </div>
        </div>

        <div class="login-page hide-first">
            <div class="login-form-col">
                <div class="login-form-title">Password Changed Successfully!</div>
                <div class="login-form-sub"></div>

                <!-- <div class="login-form-group">
                <div class="login-form-hint">Silahkan login kembali menggunakan password baru anda.</div>
                </div> -->

                <div class="login-form-group">
                <button class="login-button" onclick="location.href = '{{ route('login') }}';">Close</button>
                </div>
            </div>
            <div class="login-banner-col">
                <img src="{{ asset('assets/images/bcs_logo.png') }}" />
                <div class="login-banner-title">Belanja Musisi: Pilih, Beli, Berkreasi</div>
            </div>
        </div>
    </body>
</html>

<script>
    // Function to toggle password visibility
    function togglePasswordVisibility(passwordInputId, iconId) {
        var passwordInput = document.getElementById(passwordInputId);
        var eyeIcon = document.getElementById(iconId);
        
        // Check if password input type is password or text
        if (passwordInput.type === "password") {
        passwordInput.type = "text";
        eyeIcon.classList.add("fa-eye");
        eyeIcon.classList.remove("fa-eye-slash");
        } else {
        passwordInput.type = "password";
        eyeIcon.classList.add("fa-eye-slash");
        eyeIcon.classList.remove("fa-eye");
        }
    }
    
    // Add event listeners to the eye icons
    document.addEventListener('DOMContentLoaded', function() {
        // For the password field
        document.getElementById('toggle-password-visibility').addEventListener('click', function() {
        togglePasswordVisibility('password', 'toggle-password-visibility');
        });
    
        // For the confirmation password field
        document.getElementById('toggle-confirm-password-visibility').addEventListener('click', function() {
        togglePasswordVisibility('konfirmasi-password', 'toggle-confirm-password-visibility');
        });
    });

    const requiredInput = document.querySelectorAll('input.required');

    function inputRequirementCheck(){
        let errorInput = 0;
        requiredInput.forEach((element) => {
            const parentFormGroup = element.closest('.login-form-group');
            const inputHint = parentFormGroup.querySelector('.input-hint');

            if (element.value == "") {
                inputHint.classList.add('active');
                errorInput++;
            } else {
                inputHint.classList.remove('active');
            }
        });

        return errorInput !== 0;
    }

    function resetPasswordAction(){
        if (inputRequirementCheck() == false){
            var password = document.getElementById('password').value;
            var confirmPassword = document.getElementById('konfirmasi-password').value;
            var token = document.getElementById('token').value;

            if(password !== confirmPassword){
                Swal.fire({
                    icon: 'error',
                    title: 'Passwords do not match.',
                    showConfirmButton: false,
                    timer: 1500
                });
                return;
            }

            // Create the payload
            var formData = new FormData();
            formData.append('password', password);
            formData.append('confirm_password', confirmPassword);
            formData.append('token', token);

            // Send the request
            fetch('{{ route("save-reset-password") }}', {
                method: 'POST',
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => {
                if(response.ok){
                    return response.json(); 
                }
                throw new Error('Network response was not ok.');
            })
            .then(data => {
                document.querySelector('.show-first').style.display = 'none';
                document.querySelector('.hide-first').style.display = 'flex';
            })
            .catch(error => {
                console.error('There has been a problem with your fetch operation:', error);
            });
        }
    }
</script>