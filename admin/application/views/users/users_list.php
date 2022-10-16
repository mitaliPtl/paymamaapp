
<div class="page-breadcrumb border-bottom">
                <div class="row">
                    <div class="col-lg-3 col-md-4 col-xs-12 align-self-center">
                        <h5 class="font-medium text-uppercase mb-0">Member List</h5>
                    </div>
                    <div class="col-lg-9 col-md-8 col-xs-12 align-self-center">
<!--                        <button class="btn btn-danger text-white float-right ml-3 d-none d-md-block">Buy Ample Admin</button>-->
                        <nav aria-label="breadcrumb" class="mt-2 float-md-right float-left">
                            <ol class="breadcrumb mb-0 justify-content-end p-0">
                                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Member List</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
<div class="row">
                    <div class="col-12">
                        <div class="material-card card">
                            <div class="card-body">
<!--                                <h4 class="card-title">Zero Configuration</h4>-->
                                
                                <div class="table-responsive">
                            <table id="zero_config" class="table table-striped border">
                                <thead>
                    <tr>
                        <th>Sr. No.</th>
                        <th>Agent Name</th>
                         <th>Parent Name</th>
                         <th>Mobile Number</th>
                         <th>User Type</th>
                         <th>Balance</th>
                         <th>Package</th>
                        <th>Reg. Date</th>
                        <th>Reg. By</th>
                        <th>Balance Limit</th>
                        <th>Child</th>
                         <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                       </thead>
                                <tbody>
                    <?php
                    if(!empty($userRecords))
                    {
                        $i = 0;
                        foreach($userRecords as $record)
                        {
                            $i++;
                    ?>
                    <tr>
                        <td><?php echo $i ?></td>
                        <td><?php echo $record->first_name.' '.$record->last_name ?></td>
                        <td><?php echo $record->parent ?></td>
                        <td><?php echo $record->mobile ?></td>
                          <td><?php echo $record->role ?></td>
                        <td>-</td>
                        <td><?php echo $record->package ?></td>
                        <td><?php echo date("d-m-Y", strtotime($record->createdDtm)) ?></td>
                        <td><?php echo $record->regBy ?></td>
                         <td>-</td>
                         <td>-</td>
                         <td>-</td>
                        <td class="text-center">
<!--                            <a class="btn btn-sm btn-primary" href="<?= base_url().'login-history/'.$record->userId; ?>" title="Login history"><i class="fa fa-history"></i></a> | -->
                            <a class="btn btn-sm btn-info" href="<?php echo base_url().'editOld/'.$record->userId; ?>" title="Edit"><i class="fa fa-edit"></i></a>
                            <a class="btn btn-sm btn-danger deleteUser" href="#" data-userid="<?php echo $record->userId; ?>" title="Delete"><i class="fa fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php
                        }
                    }
                    ?>
 </tbody>

                            </table>
                           </div>
                            </div>
                        </div>
                    </div>
                </div>


<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/common.js" charset="utf-8"></script>
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('ul.pagination li a').click(function (e) {
            e.preventDefault();            
            var link = jQuery(this).get(0).href;            
            var value = link.substring(link.lastIndexOf('/') + 1);
            jQuery("#searchList").attr("action", baseURL + "userListing/" + value);
            jQuery("#searchList").submit();
        });
    });
</script>
