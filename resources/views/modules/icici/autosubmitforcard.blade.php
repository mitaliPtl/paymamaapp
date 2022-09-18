
<!doctype html>
<html lang="en">
  <head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <!-- CSRF Token Meta Added -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Laravel 5.8 Ajax Form Submit with Validation - W3Adda</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
   <style>
   .error{ color:red; } 
  </style>
</head>
 
<body>
 
<div class="container">
    <h2 style="margin-top: 10px;">Laravel 5.8 Ajax Form Submit with Validation - W3Adda</h2>
    <br>
    <br>
   
    <form id="contact_us" method="post" action="https://qa.bankx.money:9092/yespay/api/v1/customer/add">
      @csrf

      <div class="alert alert-success d-none" id="msg_div">
              <span id="res_message"></span>
         </div>
      <div class="form-group">
        <label for="name">Name</label>
        <input type="text" name="firstName" class="form-control" id="name" value="{{ $datas->first_name }}" placeholder="Please enter name">
      </div>
      <div class="form-group">
        <label for="name">Last Name</label>
        <input type="text" name="lastName" class="form-control" id="name" value="Budhathoki" placeholder="Please enter name">
      </div>
      <div class="form-group">
        <label for="phone">Mobile No.</label>
        <input type="text" name="mobileNumber" class="form-control" id="phone" value="mobileno" placeholder="Please enter mobile number" maxlength="10">
        <span class="text-danger">{{ $errors->first('phone') }}</span>
      </div>
      <div class="form-group">
        <label for="phone">Gender</label>
        <input type="text" name="gender" class="form-control" id="phone" value="male" placeholder="Please enter mobile number"            maxlength="10">
        <span class="text-danger">{{ $errors->first('phone') }}</span>
      </div>
      <div class="form-group">
        <label for="phone">Address</label>
        <input type="text" name="address" class="form-control" id="phone" value="mobileno" placeholder="Please enter mobile number" maxlength="10">
        <span class="text-danger">{{ $errors->first('phone') }}</span>
      </div>
      <div class="form-group">
        <label for="email">Email Id</label>
        <input type="text" name="email" class="form-control" id="email" placeholder="Please enter email id">
        <span class="text-danger">{{ $errors->first('email') }}</span>
      </div>      
      <div class="form-group">
        <label for="phone">Dateof Birth</label>
        <input type="text" name="dateOfBirth" class="form-control" id="phone" value="mobileno" placeholder="Please enter mobile number" maxlength="10">
        <span class="text-danger">{{ $errors->first('phone') }}</span>
      </div>
      <div class="form-group">
        <label for="phone">State</label>
        <input type="text" name="state" class="form-control" id="phone" value="gujarat" placeholder="Please enter mobile number" maxlength="10">
        <span class="text-danger">{{ $errors->first('phone') }}</span>
      </div>
      <div class="form-group">
        <label for="phone">City</label>
        <input type="text" name="city" class="form-control" id="phone" value="rajkot" placeholder="Please enter mobile number" maxlength="10">
        <span class="text-danger">{{ $errors->first('phone') }}</span>
      </div>
      <div class="form-group">
        <label for="phone">Pincode</label>
        <input type="text" name="pincode" class="form-control" id="phone" value="360005" placeholder="Please enter mobile number" maxlength="10">
        <span class="text-danger">{{ $errors->first('phone') }}</span>
      </div>
      
      <div class="form-group">
       <button type="submit" id="send_form" class="btn btn-success">Submit</button>
      </div>
    </form>
 
</div>

</body>
</html>
<script>
//-----------------
$(document).ready(function(){

  $("#contact_us").submit();
});
/*window.onload = function(){
  document.forms['#contact_us'].submit();
}*/
//-----------------
</script>