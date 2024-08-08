<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <style>
        .email-container {
            font-family: Arial, sans-serif;
            color: #333333;
            max-width: 600px;
            margin: auto;
            border: 1px solid #ddd;
            padding: 20px;
        }
        .button {
            background-color: #0c0c0c;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 4px;
            border: none;
        }
        .footer {
            font-size: 12px;
            color: #666666;
            margin-top: 20px;
            text-align: center;
        }
        .social-icons {
            margin-top: 10px;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="email-container">
    <p>Hey {{$email}}, did you want to reset your password?</p>
    <p>Someone (hopefully you) has asked us to reset the password for your BCS account. Please click the button below to do so. If you didn't request this password reset, you can go ahead and ignore this email!</p>
    <div style="text-align: center; margin: 20px 0;">
        <a href="{{ $resetPasswordUrl }}" style="color: white;" class="button">Reset Password</a>
    </div>
    <p class="footer">
        © 2024 Bandung Choral Society, All Rights Reserved
    </p>
    <div class="social-icons">
        <!-- Insert social media icons here -->
    </div>
</div>

</body>
</html>
