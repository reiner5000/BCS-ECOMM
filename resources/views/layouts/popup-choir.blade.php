<div class="popup-center" id="right-choir">
    <div class="cart-modal" style="min-height:0vh !important">
        <div class="modal-row pt-30">
            <div class="modal-title">Add New Choir</div>
            <button class="modal-exit popup-trigger" target-popup="right-choir"><i class="fa-solid fa-x"></i></button>
        </div>
        
        <form action="{{route('save-choir')}}" method="POST" id="choirForm">
            @csrf
            <div class="modal-col">
                <div class="form-col">
                    <div class="col-form-group">
                        <label for="nama-choir">Choir Name</label>
                        <input class="required" name="nama-choir" placeholder="Choir Name" required/>
                        <div class="input-hint">*Required to fill*</div>
                    </div>

                    <div class="col-form-group">
                        <label for="alamat-choir">Address</label>
                        <input class="required" name="alamat-choir" placeholder="Address" required/>
                        <div class="input-hint">*Required to fill*</div>
                    </div>

                    <div class="col-form-group">
                        <label for="nama-konduktor">Conductor Name</label>
                        <input class="required" name="nama-konduktor" placeholder="Conductor Name" required/>
                        <div class="input-hint">*Required to fill*</div>
                    </div>
                </div>
            </div>
            <br>
            <div class="modal-row right-flex margin-top-auto pb-30">
                <button type="button" class="btn btn-black popup-trigger" target-popup="right-choir">Cancel</button>
                <button type="button" id="submitBtn2" class="btn-white btn" onclick="disableButton2()">Save</button>
            </div>
        </form>
    </div>
</div>