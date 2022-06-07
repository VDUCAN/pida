<?php 
    $option_transactions = array('1' => 'Pending', '2' => 'Paid');
    $option_type = array('1' => 'Now', '2' => 'Scheduled');
    $option_payments = array('1' => 'Success', '2' => 'Failure');
?>

<!-- start: PAGE TITLE -->
<section id="page-title">
    <div class="row">
        <div class="col-sm-8">
            <h1 class="mainTitle">View Transaction</h1>
        </div>
    </div>
</section>
<!-- end: PAGE TITLE -->
<!-- Global Messages -->
<?php echo $this->Session->flash(); ?>
<!-- Global Messages End -->

<div class="container-fluid container-fullw bg-white">
    
    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped table-bordered table-hover table-full-width">
                <thead>
                <tr>
		    <th>Booking Id</th>
		    <th>Transaction Id</th>
                    <th>Customer</th>
                    <th>Driver</th>
		    <th>Pickup date</th>
                    <th>Driver Amount</th>
		    <th>Admin Amount</th>
		    <th>Commission Precentage(%)</th>
		    <th>Payment Status</th>
		    <th>Payment Failure Reason</th>
		    <th>Miles</th>
		    <th>Type</th>
		    <th>Status</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php if (!empty($result_data)) { ?>
                    <?php foreach ($result_data as $data) { ?>
                        <tr>
			    <td><?php echo $data['Booking']['id']; ?></td>
			    <td><?php echo $data['Transaction']['transaction_id']; ?></td>
                            <td><?php echo ucfirst($data['User']['first_name'].' '.$data['User']['last_name']); ?></td>
			    <td><?php echo ucfirst($data['Driver']['first_name'].' '.$data['Driver']['last_name']); ?></td>
                            <td><?php
				if(!empty($data['Booking']['pickup_date']) && date('Y-m-d',strtotime($data['Booking']['pickup_date'])) != '1970-01-01'){
				    echo date(DATETIME_FORMAT,strtotime($data['Booking']['pickup_date']));	
				}else{
				    echo '-';
				}
			     ?></td>
			    <td><?php echo $data['Transaction']['driver_amount']; ?></td>
			    <td><?php echo $data['Transaction']['admin_amount']; ?></td>
			    <td><?php echo $data['Transaction']['admin_percent']; ?></td>
			    <td><?php
				    if($data['Transaction']['payment_status']){
					echo $option_payments[$data['Transaction']['payment_status']];	
				    }else{
					echo '-';
				    }
			    ?></td>
			    <td><?php
				    if($data['Transaction']['reject_reason']){
					echo $data['Transaction']['reject_reason'];	
				    }else{
					echo '-';
				    }
			    ?></td>
			    <td><?php echo $data['Booking']['total_miles']; ?></td>
			    <td><?php
				    if($data['Booking']['booking_type']){
					echo $option_type[$data['Booking']['booking_type']];	
				    }else{
					echo '-';
				    }
			    ?></td>
			    <td><?php
				    if($data['Transaction']['status']){
					echo $option_transactions[$data['Transaction']['status']];
				    }else{
					echo '-';
				    }
			     ?></td>
			   
			    <td>
                                <div>
                                    <?php
                                    echo $this->Html->link('<i class="fa fa-eye"></i>',
                                        array('plugin' => false, 'controller' => 'bookings', 'action' => 'view', 'id' => base64_encode($data['Booking']['id']), 'admin' => true),
                                        array('class' => 'btn btn-transparent btn-xs', 'title' => 'Click here to View', 'escape' => false)
                                    );
                                     ?>
                                </div>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="14" class="text-center">Transaction Not Available !</td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    
</div>