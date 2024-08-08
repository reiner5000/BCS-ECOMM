<div class="popup-center" id="edit-choir">
    <div class="cart-modal" style="min-height:0vh !important">
        <div class="modal-row pt-30">
            <div class="modal-title">Edit Choir</div>
            <button class="modal-exit popup-trigger" target-popup="edit-choir"><i class="fa-solid fa-x"></i></button>
        </div>
        
        <form action="{{route('update-choir')}}" method="POST" id="choirFormEdit">
            @csrf
            <div class="modal-col">
                <div class="form-col">
                    <input type="hidden" name="choir-id"/>
                    <div class="col-form-group">
                        <label for="nama-choir">Choir Name</label>
                        <input class="required nama-choir" name="nama-choir" placeholder="Choir Name" />
                        <div class="input-hint">*Required to fill*</div>
                    </div>

                    <div class="col-form-group">
                        <label for="alamat-choir">Address</label>
                        <input class="required alamat-choir" name="alamat-choir" placeholder="Address" />
                        <div class="input-hint">*Required to fill*</div>
                    </div>

                    <div class="col-form-group">
                        <label for="nama-konduktor">Conductor Name</label>
                        <input class="required nama-konduktor" name="nama-konduktor" placeholder="Conductor Name" />
                        <div class="input-hint">*Required to fill*</div>
                    </div>
                </div>
            </div>
            <br>
            <div class="modal-row right-flex margin-top-auto pb-30">
                <button type="button" class="btn btn-black popup-trigger" target-popup="edit-choir" type="button">Cancel</button>
                <button type="button" class="btn-white btn" onclick="submitChoirEdit()">Save</button>
            </div>
        </form>
    </div>
</div>

<script>
function submitChoirEdit() {
    let form = document.getElementById('choirFormEdit');
    let inputs = form.querySelectorAll('.required');
    let allFilled = true;
    let firstEmptyInput = null;

    inputs.forEach(input => {
        let hint = input.nextElementSibling;
        if (input.value.trim() === '') {
            hint.classList.add('active');
            if (firstEmptyInput === null) {
                firstEmptyInput = input;
            }
            allFilled = false;
        } else {
            hint.classList.remove('active');
        }
    });

    if (allFilled) {
        form.submit();
    } else if (firstEmptyInput !== null) {
        firstEmptyInput.focus();
    }
}
</script>