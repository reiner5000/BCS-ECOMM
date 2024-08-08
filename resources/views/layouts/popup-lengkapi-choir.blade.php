<div class="popup-center align-items-center justify-content-center" id="lengkapi-choir">
    <div class="normal-modal" style="min-height:0vh !important">
        <div class="modal-row">
            <div class="modal-title"><br>Choir not registered yet</div>
        </div>
        <img src="{{ asset('assets/images/choir.png') }}">
        <a href="{{ route('choir') }}" class="btn btn-white popup-trigger" target-popup="lengkapi-choir">Add Choir</a>
        <br>
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
