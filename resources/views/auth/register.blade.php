@include('layouts.import')

<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>

    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" type="image/x-icon">
    <link rel="icon" href="{{ asset('assets/images/favicon.png') }}" type="image/x-icon">
</head>

<body>
    <form action="{{ route('register') }}" method="POST">
        @csrf
        <div class="login-page mobile-col">
            <div class="login-form-col mobile-w-100">
                <div class="login-form-title">Sign Up</div>
                <div class="login-form-sub">Bandung Choral Society</div>

                @if(session('error'))
                    <div style="color: red;">
                        {{ session('error') }}
                    </div>
                @endif
                
                <div class="login-form-group">
                    <label for="email">Email</label>
                    <div class="login-input-group">
                        <input id="email" name="email" placeholder="Email" required/>
                    </div>
                </div>

                <div class="login-form-group">
                    <label for="nama">Name</label>
                    <div class="login-input-group">
                        <input id="nama" name="nama" placeholder="Name" required/>
                    </div>
                </div>

                <div class="login-form-group">
                    <label for="password">Password</label>
                    <div class="login-input-group">
                        <input type="password" id="password" name="password" placeholder="Password" minlength="8" required/>
                        <i class="fa-regular fa-eye-slash" id="toggle-password-visibility"></i>
                    </div>
                </div>

                <div class="login-form-group">
                    <label for="konfirmasi-password">Confirm Password</label>
                    <div class="login-input-group">
                        <input type="password" id="konfirmasi-password" name="password_confirmation"
                            placeholder="Password" minlength="8" required/>
                        <i class="fa-regular fa-eye-slash" id="toggle-confirm-password-visibility"></i>
                    </div>
                </div>

                <div class="login-form-group">
                    <button class="login-button" type="submit">Register</button>

                    <div class="devider third-party-login-hide">
                        <div class="line third-party-login-hide"></div>Or<div class="line"></div>
                    </div>
                    <button class="login-with-button third-party-login-hide"><i class="fa-brands fa-google"></i> Sign Up with Google</button>

                    <button class="login-with-button third-party-login-hide"><i class="fa-brands fa-facebook"></i> Sign Up with
                        Facebook</button>
                </div>

                <div class="login-form-sub">Already have a BCS account? <a href="{{ route('login') }}">Log In</a></div>
            </div>
            <div class="login-banner-col mobile-hide">
                <img src="{{ asset('assets/images/bcs_logo.png') }}" />
                <div class="login-banner-title">Belanja Musisi: Pilih, Beli, Berkreasi</div>
            </div>
        </div>
    </form>
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
</script>
