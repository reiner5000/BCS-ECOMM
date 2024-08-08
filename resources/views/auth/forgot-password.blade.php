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

                <div class="login-form-group">
                <div class="login-form-hint">Enter the email registered with the Bandung Choral Society and we will send you a link to reset your password </div>
                </div>

                <div class="login-form-group">
                    <label for="email">Email</label>
                    <div class="login-input-group">
                        <input class="required" id="email" name="email" placeholder="Email" required/>
                    </div>
                    <div class="input-hint" style="font-size: 18px; margin: 4px 0 0 0;">*Required*</div>
                </div>

                <div class="login-form-group">
                    <button class="login-button" onclick="forgotPasswordAction()">Confirm</button>
                </div>
            </div>
            <div class="login-banner-col">
                <img src="{{ asset('assets/images/bcs_logo.png') }}" />
                <div class="login-banner-title">Belanja Musisi: Pilih, Beli, Berkreasi</div>
            </div>
        </div>

        <div class="login-page hide-first">
            <div class="login-form-col">
                <div class="login-form-title">Check Email</div>
                <div class="login-form-sub"></div>

                <div class="login-form-group">
                <div class="login-form-hint">We have sent a link to reset the password. If you can't find it, check your spam folder</div>
                </div>

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

    function forgotPasswordAction(){
        if (inputRequirementCheck() == false){
            document.querySelector('.show-first').style.display = 'none';
            document.querySelector('.hide-first').style.display = 'flex';
            
            var email = document.getElementById('email').value; 

            fetch("{{ route('send-forgot-password') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}" 
                },
                body: JSON.stringify({ email: email })
            })
            .then(response => {
                if (response.ok) {
                    return response.json(); 
                }
                throw new Error('Gagal mengirim email reset password.');
            })
            .then(data => {
                console.log('Email reset password telah dikirim', data);
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    }
</script>