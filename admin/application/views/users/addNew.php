<style>
 .devider {
    margin: 7px 0;
    border-top: 1px solid #fff;
    opacity: .1;
}
</style>
<div class="page-breadcrumb border-bottom">
                <div class="row">
                    <div class="col-lg-3 col-md-4 col-xs-12 align-self-center">
                        <h5 class="font-medium text-uppercase mb-0">Add New Member</h5>
                    </div>
                    <div class="col-lg-9 col-md-8 col-xs-12 align-self-center">
<!--                        <button class="btn btn-danger text-white float-right ml-3 d-none d-md-block">Buy Ample Admin</button>-->
                        <nav aria-label="breadcrumb" class="mt-2 float-md-right float-left">
                            <ol class="breadcrumb mb-0 justify-content-end p-0">
                                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Add New Member</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>      
<div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="col-md-12">
                <?php
                    $this->load->helper('form');
                    $error = $this->session->flashdata('error');
                    if($error)
                    {
                ?>
                <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $this->session->flashdata('error'); ?>                    
                </div>
                <?php } ?>
                <?php  
                    $success = $this->session->flashdata('success');
                    if($success)
                    {
                ?>
                <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $this->session->flashdata('success'); ?>
                </div>
                <?php } ?>
                
                <div class="row">
                    <div class="col-md-12">
                        <?php echo validation_errors('<div class="alert alert-danger alert-dismissable">', ' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>'); ?>
                    </div>
                </div>
            </div>
<!--                                <h4 class="card-title">Register Member</h4>-->
<!--                                <h6 class="card-subtitle"> All with bootstrap element classies </h6> /.box-header -->
                    <!-- form start -->
                    <?php $this->load->helper("form"); ?>
                    <form role="form" id="addUser" action="<?php echo base_url() ?>addNewUser" method="post" role="form">
                        <div class="box-body">
                                <div class="row">
                            <div class="col-md-6">
                                                              <div class="col-md-12">                                
                                    <div class="form-group">
                                        <label for="fname">First Name</label>
                                        <input type="text" class="form-control required" value="<?php echo set_value('fname'); ?>" id="fname" name="fname" maxlength="100">
                                    </div>
                                    
                                </div>
                                <div class="col-md-12">                                
                                    <div class="form-group">
                                        <label for="lname">Last Name</label>
                                        <input type="text" class="form-control required" value="<?php echo set_value('lname'); ?>" id="lname" name="lname" maxlength="100">
                                    </div>
                                    
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="mobile">Mobile Number</label>
                                        <input type="text" class="form-control required digits" id="mobile" value="<?php echo set_value('mobile'); ?>" name="mobile" maxlength="10">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="altmobile">Alternate Number</label>
                                        <input type="text" class="form-control required digits" id="altmobile" value="<?php echo set_value('altmobile'); ?>" name="altmobile" maxlength="10">
                                    </div>
                                </div>
                                        <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input type="password" class="form-control required" id="password" name="password" maxlength="20">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="cpassword">Confirm Password</label>
                                        <input type="password" class="form-control required equalTo" id="cpassword" name="cpassword" maxlength="20">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="email">Email address</label>
                                        <input type="text" class="form-control required email" id="email" value="<?php echo set_value('email'); ?>" name="email" maxlength="128">
                                    </div>
                                </div>
<!--                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="username">Username</label>
                                        <input type="text" class="form-control required" value="<?php //echo set_value('username'); ?>" id="username" name="username" maxlength="100">
                                    </div>
                                    
                                </div>-->
                            </div>
                             

                            <div class="col-md-6">
                                
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="type">User Type</label>
                                        <select class="form-control required" id="type" name="type">
                                            <option value="">Select User Type</option>
                                            <?php
                                            if(!empty($types))
                                            {
                                                foreach ($types as $ty)
                                                {
                                                    ?>
                                                    <option value="<?php echo $ty->roleId ?>" <?php if($ty->roleId == set_value('type')) {echo "selected=selected";} ?>><?php echo $ty->role ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>  
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="parent">Select Parent</label>
                                        <select class="form-control required" id="parent" name="parent">
                                            <option value="">Select Parent</option>
                                            <?php
                                            if(!empty($parents))
                                            {
                                                foreach ($parents as $pr)
                                                {
                                                    ?>
                                                    <option value="<?php echo $pr->roleId ?>" <?php if($pr->roleId == set_value('parent')) {echo "selected=selected";} ?>><?php echo $pr->role ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div> 
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="commission">Commission Type</label>
                                        <select class="form-control required" id="commission" name="commission" onchange="getPackages(this.value)">
                                            <option value="">Select Commission Type</option>
                                            <?php
                                            if(!empty($commissions))
                                            {
                                                foreach ($commissions as $cms)
                                                {
                                                    ?>
                                                    <option value="<?php echo $cms->commission_id ?>" <?php if($cms->commission_id == set_value('commission')) {echo "selected=selected";} ?>><?php echo $cms->commission_type ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="package">Select Package</label>
                                        <select class="form-control required" id="package" name="package">
                                            
                                     
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="state">Select State</label>
                                        <select class="form-control required" id="state" name="state" class="state" onchange="getCities(this.value)">
                                            <option value="">Select State</option>
                                            <?php
                                            if(!empty($states))
                                            {
                                                foreach ($states as $st)
                                                {
                                                    ?>
                                                    <option value="<?php echo $st->state_id ?>" <?php if($st->state_id == set_value('state')) {echo "selected=selected";} ?>><?php echo $st->state_name ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                    <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="city">Select City</label>
                                        <select class="form-control required" id="city" name="city">
                                            
                                      
                                        </select>
                                    </div>
                                </div>
                                <div class="row col-md-12">
                                 <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="address">Address</label>
                                        <input type="text" class="form-control required digits" id="address" value="<?php echo set_value('address'); ?>" name="address" >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="zipcode">Zip Code</label>
                                        <input type="text" class="form-control required digits" id="zipcode" value="<?php echo set_value('zipcode'); ?>" name="zipcode" maxlength="5">
                                    </div>
                                </div>
                               </div>
                            </div>
                            </div>

    
                        <div class="box-footer">
                            <input type="submit" class="btn btn-success" value="Submit" />
                            <input type="reset" class="btn btn-dark" value="Reset" />
                        </div>
               </div>
                        </div>
                    </div>
                </div>
            
        </div>    
    </section>
    
</div>

<script src="<?php echo base_url(); ?>assets/js/addUser.js" type="text/javascript"></script>
<script type="text/javascript">

 function getCities(state_id) { 
var id = state_id;
         $.ajax({
            url: "<?php echo base_url() ?>citiesList",
            data: {state_id: id},
            type: "POST",
            dataType: 'json',
            success: function (result) {
                var html = '';
                for (i = 0; i < result.length; i++) {
                    html += '<option value="' + result[i]['city_id'] + '"';
                    html += '>' + result[i]['city_name'] + '</option>';
                    }
                    html +='<option value="">Select City</option>';
                    $("#city").html(''); 
                    $("#city").html(html); 
            }
        });
  }
  
  
 function getPackages(commission_id) { 
var id = commission_id;
         $.ajax({
            url: "<?php echo base_url() ?>packagesList",
            data: {commission_id: id},
            type: "POST",
            dataType: 'json',
            success: function (result) {
                var html = '';
                for (i = 0; i < result.length; i++) {
                    html += '<option value="' + result[i]['package_id'] + '"';
                    html += '>' + result[i]['package_name'] + '</option>';
                    }
                    html +='<option value="">Select Package</option>';
                    $("#package").html(''); 
                    $("#package").html(html); 
            }
        });
  }
  
</script>