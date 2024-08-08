@include('layouts.import')

<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>

    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" type="image/x-icon">
    <link rel="icon" href="{{ asset('assets/images/favicon.png') }}" type="image/x-icon">
</head>

<body>
    <form method="POST" action="{{ route('customer.login') }}">
        @csrf
        <div class="login-page mobile-col">
            <div class="login-form-col mobile-w-100">
                <div class="login-form-title">Log In</div>
                <div class="login-form-sub">Bandung Choral Society</div>

                <!-- Display error message for email -->
                @error('error')
                    <div style="color: red;">
                        {{ $message }}
                    </div>
                @endif

                <div class="login-form-group">
                    <label for="email">Email</label>
                    <div class="login-input-group">
                        <input id="email" name="email" placeholder="Email" required/>
                    </div>
                </div>

                <div class="login-form-group">
                    <label for="password">Password</label>
                    <div class="login-input-group">
                        <input type="password" id="password" name="password" placeholder="Password" required/>
                        <i class="fa-regular fa-eye-slash" id="toggle-password-visibility"></i>
                    </div>
                </div>

                <div class="login-form-group">
                    <label><a href="{{ route('forgot-password') }}">Forgot Password</a></label>
                    <label class="notes">* Can log in using an account registered at BandungChoral.com</label>
                </div>

                <div class="login-form-group">
                    <button class="login-button" type="submit">Login</button>

                    <div class="devider third-party-login-hide">
                        <div class="line"></div>Or<div class="line"></div>
                    </div>

                    <button class="login-with-button third-party-login-hide"><i class="fa-brands fa-google"></i> Sign In with Google</button>

                    <button class="login-with-button third-party-login-hide"><i class="fa-brands fa-facebook"></i> Sign In with
                        Facebook</button>
                </div>
                <div class="login-form-sub">Don't have a BCS account yet? <a href="{{ route('register') }}">Sign Up</a></div>
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
    });
</script>
