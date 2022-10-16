<div class="page-breadcrumb border-bottom">
                <div class="row">
                    <div class="col-lg-3 col-md-4 col-xs-12 align-self-center">
                        <h5 class="font-medium text-uppercase mb-0">Recharge Report</h5>
                    </div>
                    <div class="col-lg-9 col-md-8 col-xs-12 align-self-center">
<!--                        <button class="btn btn-danger text-white float-right ml-3 d-none d-md-block">Buy Ample Admin</button>-->
                        <nav aria-label="breadcrumb" class="mt-2 float-md-right float-left">
                            <ol class="breadcrumb mb-0 justify-content-end p-0">
                                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Recharge Report</li>
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
                                        <th>Sr No.</th>
                                        <th>Date</th>
                                        <th>Member Name</th>
                                        <th>Member Mobile</th>
                                        <th>Recharge Id</th>
                                        <th>API User Id</th>
                                        <th>API</th>
                                        <th>Response</th>
                                        <th>Operator</th>
                                        <th>Operator Id</th>
                                        <th>Recharge By</th>
                                        <th>Mobile</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                                        <?php
                    if(!empty($transactionRecords))
                    {
                        $i = 0;
                        foreach($transactionRecords as $record)
                        {
                            $i++
                    ?>
                                    <tr>
                                        <td><?php echo $i ?></td>
                                        <td><?php echo date("d-m-Y", strtotime($record->updated_on)) ?></td>
                                        <td><?php echo $record->first_name.' '.$record->last_name ?></td>
                                        
                                        <td><?php echo $record->mobile ?></td>
                                        <td><?php echo $record->transaction_id ?></td>
                                        <td><?php echo $record->user_id ?></td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td><?php echo $record->operator_name ?></td>
                                        <td><?php echo $record->operator_id ?></td>
                                        <td>-</td>
                                        <td><?php echo $record->mobile ?></td>
                                        <td><?php echo $record->total_amount ?></td>
                                        <td><?php echo $record->transaction_status ?></td>
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

