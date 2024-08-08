<style>
.gender-options label {
  margin-right: 15px; 
}
input[type='radio'] {
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
  width: 20px;
  height: 20px;
  border: 2px solid #999; 
  border-radius: 50%;
  margin-right: 5px; 
  outline: none; 
  display: inline-block;
  position: relative;
  vertical-align: middle; 
}

input[type='radio']:checked {
  background-color: #ff0000;
  border-color: #ff0000; 
}

input[type='radio']:checked::before {
  content: '';
  position: absolute;
  top: 4px; 
  left: 4px;
  width: 10px;
  height: 10px;
  background-color: #ff0000; 
  border-radius: 50%;
}
</style>
<div class="popup-center" id="edit-profile">
    <div class="cart-modal" style="min-height:0vh !important">
        <div class="modal-row pt-30">
            <div class="modal-title">Edit Profile</div>
            <button class="modal-exit popup-trigger" target-popup="edit-profile"><i class="fa-solid fa-x"></i></button>
        </div>
        
        <form action="{{route('save-profile')}}" method="POST">
            @csrf
            <div class="modal-col">
                <div class="form-col">
                    <input type="hidden" name="customer-id"/>
                    <div class="col-form-group">
                        <label for="nama-customer">Name</label>
                        <input class="required nama-customer" name="nama-customer" placeholder="Name" required/>
                        <div class="input-hint active">*Required*</div>
                    </div>
                    
                    <div class="col-form-group">
                        <label for="gender-customer">Gender</label>
                        <div class="gender-options">
                            <input type="radio" id="male" name="gender-customer" value="Male">
                            <label for="male">Male</label>
                            <input type="radio" id="female" name="gender-customer" value="Female">
                            <label for="female">Female</label>
                        </div>
                    </div>

                    <div class="col-form-group">
                        <label for="email-customer">Email</label>
                        <input type="email" class="required email-customer" name="email-customer" placeholder="Email" required/>
                        <div class="input-hint active">*Required*</div>
                    </div>

                    <div class="col-form-group">
                        <label for="phone-customer">Phone Number</label>
                        <input type="number" class="required phone-customer" name="phone-customer" placeholder="Phone Number"/>
                    </div>

                </div>
            </div>
            <br>
            <div class="modal-row right-flex margin-top-auto pb-30">
                <button class="btn btn-black popup-trigger" target-popup="edit-profile" type="button">Cancel</button>
                <button type="submit" class="btn-white btn">Save</button>
            </div>
        </form>
    </div>
</div>