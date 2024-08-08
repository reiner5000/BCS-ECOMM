<div class="popup-center align-items-center justify-content-center" id="lengkapi-login">
    <div class="normal-modal" style="min-height:0vh !important">
        <div class="modal-row">
            <div class="modal-title"><br>Login first</div>
        </div>
        <div class="modal-content">
            <img src="{{ asset('assets/images/login.png') }}">
            <a href="{{ route('login') }}" class="btn btn-white popup-trigger" target-popup="lengkapi-login">Login</a>
            <br>
        </div>
    </div>
</div>
<style>
    .modal-title {
        text-align: center;
        width: 100%;
    }

    .normal-modal img {
        display: block;
        margin: 20px auto;
        width: 50%;
    }
</style>
