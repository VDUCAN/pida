

<?php   echo $this->Html->css(array('bootstrap-datetimepicker'), null, array('inline' => false));
    echo $this->Html->script(array('bootstrap-datepicker'), array('inline' => false)); ?>

<script type="text/javascript">
    jQuery(document).ready(function () {
   //     UINotifications.init();
   //     TableData.init();

        jQuery('#reset_button').click(function(){
            jQuery('.reset-field').val('');
            jQuery('#order_by').val('Booking.name ASC');
        });

        jQuery('#records_per_page').change(function(){
            jQuery('#pageSizeForm').submit();
        });

    });
</script>

<?php $option_status = array('5'=>'Completed','6'=>'Paid');
$option_type = array('1' => 'Now', '2' => 'Scheduled');
$option_order = array(
    'Booking.pickup_date ASC' => 'Pick up date Ascending',
    'Booking.pickup_date DESC' => 'Pick up date Descending',
    'Booking.price ASC' => 'Price Ascending',
    'Booking.price DESC' => 'Price Descending',
    'Booking.total_miles ASC' => 'Miles Ascending',
    'Booking.total_miles DESC' => 'Miles Descending',
    'Booking.created ASC' => 'Created On Ascending',
    'Booking.created DESC' => 'Created On Descending',
);

$option_week = array();
for ($i = 1; $i < 52; $i++) {
    $day = date("Y-m-d", strtotime("this monday - $i week"));
    $nextDay = date("Y-m-d", strtotime('+6 day',strtotime ($day)));
    $key =  $day.'_'.$nextDay;
    $value = date(DATE_FORMAT, strtotime($day)) . " - " . date(DATE_FORMAT, strtotime($nextDay));
    $option_week[$key] = $value;
}

//pr($option_week);die;


$search_txt = $status =  $search_lang = $type = $from = $to = $week = '';
if($this->Session->check('invoice_search')){
    $search = $this->Session->read('invoice_search');
    $search_txt = @$search['search'];
  //  $status = $search['booking_status'];
    $type = @$search['booking_type'];
    $from = @$search['booking_from'];
    $to = @$search['booking_to'];
    $week = @$search['week'];
 //   $search_lang = $search['lang_code'];
}
?>

<section id="page-title">
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <h1 class="mainTitle pull-left">Invoice List</h1>
            
        </div>
    </div>
</section>
<?php echo $this->Session->flash(); ?>

<div class="container-fluid container-fullw bg-white">

    <!-- start: SEARCH FORM START -->
    <div class="border-around margin-bottom-15 padding-10">
        <?php echo $this->Form->create('Invoice', array(
                'url' => array('controller' => 'invoices', 'action' => 'index', 'admin' => true), 'class' => 'form', 'role' => 'search', 'autocomplete' => 'off')
        ); ?>

        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label">Search</label>
                <?php echo $this->Form->input('search', array('type' => 'text', 'value' => $search_txt, 'placeholder' => 'Search...', 'class' =>'form-control reset-field', 'div' => false, 'label' => false, 'required' => false)); ?>
            </div>
        </div>
	
	<div class="col-md-4">
            <div class="form-group">
                <label class="control-label">From</label>
                <?php echo $this->Form->input('booking_from', array('id'=>'booking_from','value' => $from, 'class' => 'form-control reset-field', 'placeholder'=>'Booking From', 'label' => false, 'div' => false, 'required' => false)); ?>
            </div>
        </div>
	
	<div class="col-md-4">
            <div class="form-group">
                <label class="control-label">To</label>
                <?php echo $this->Form->input('booking_to', array('id'=>'booking_to','value' => $to, 'class' => 'form-control reset-field', 'placeholder'=>'Booking To', 'label' => false, 'div' => false, 'required' => false)); ?>
            </div>
        </div>
	
	<?php /* ?>
	 <div class="col-md-4">
            <div class="form-group">
                <label class="control-label">Status</label>
                <?php echo $this->Form->input('booking_status', array('options' => $option_status, 'value' => $status, 'class' => 'form-control reset-field', 'empty' => 'All', 'label' => false, 'div' => false, 'required' => false)); ?>
            </div>
        </div>  
	 <?php */ ?>
	
	<div class="col-md-4">
            <div class="form-group">
                <label class="control-label">Service Type</label>
                <?php echo $this->Form->input('booking_type', array('options' => $booking_type, 'value' => $type, 'class' => 'form-control reset-field', 'empty' => 'All', 'label' => false, 'div' => false, 'required' => false)); ?>
            </div>
        </div>
	


        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label">Order By</label>
                <?php echo $this->Form->input('order_by', array('options' => $option_order, 'value' => $order, 'id' => 'order_by', 'class' => 'form-control', 'empty' => false, 'label' => false, 'div' => false, 'required' => false)); ?>
            </div>
        </div>
	
	   <div class="col-md-4">
            <div class="form-group">
                <label class="control-label">Select Week</label>
                <?php echo $this->Form->input('week', array('options' => $option_week, 'id' => 'select_week', 'class' => 'form-control', 'empty' => 'Select week', 'label' => false, 'div' => false, 'required' => false)); ?>
            </div>
        </div>

        <div class="col-md-12">
            <?php 
	    echo $this->Html->link('Reset <i class="fa fa-times-circle"></i>', array('controller' => 'invoices','action'=>'reset_filter','admin' => true), array('title' => 'Reset', 'escape' => false, 'class' => 'btn btn-primary btn-wide pull-right'));
	    echo $this->Form->button('Search <i class="fa fa-arrow-circle-right"></i>',array('class' => 'btn btn-primary btn-wide pull-right margin-right-10','type' => 'submit','id' => 'submit_button')) ?>
        </div>

        <?php echo $this->Form->end(); ?>
        <div class="clearfix"></div>
    </div>

    <?php echo $this->Form->create('PageSize', array(
            'url' => array('controller' => 'bookings', 'action' => 'index', 'admin' => true), 'class' => 'form', 'autocomplete' => 'off', 'id' => 'pageSizeForm')
    ); ?>
    
     <div class="form-group pull-left">
	 <label class="control-label">
	     <?php
		if(!empty($from) && !empty($to)){
		    $value = date(DATE_FORMAT, strtotime($from)) . " - " . date(DATE_FORMAT, strtotime($to));
		    echo 'Date of '.$value;	
		}else if($week){
		    $weekArr = explode('_',$week);
		    $weekFrom = (!empty($weekArr[0]) ? $weekArr[0] : NULL);
		    $weekTo = (!empty($weekArr[1]) ? $weekArr[1] : NULL);
		    $value = date(DATE_FORMAT, strtotime($weekFrom)) . " - " . date(DATE_FORMAT, strtotime($weekTo));
		    echo 'Week of '.$value;	
		}    
	     ?>
	     
	 </label>
    </div>
    
    <?php /* ?>
    <div class="form-group pull-left">
        <label class="control-label">Records Per Page</label>
        <?php echo $this->Form->input('records_per_page', array('options' => unserialize(PAGING_OPTIONS), 'value' => $limit, 'id' => 'records_per_page', 'class' => 'form-control', 'empty' => false, 'label' => false, 'div' => false)); ?>
    </div>
    <?php */ ?>
    
    
    <?php echo $this->Form->end(); ?>

    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped table-bordered table-hover table-full-width">
                <thead>
                <tr>
		    <th>Booking Date</th>
		    <th>Invoice No.</th>
		    <th>Driver</th>
                    <th>Customer</th>
		    <th>Service Type</th>
		    <th>Mile</th>
		    <th>Total Charge</th>
		    <th>Pida Fee</th>
		    <th>Pida Percentage</th>
                    <th>Total Pida charged</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php if (!empty($result_data)) { ?>
                    <?php foreach ($result_data as $data) { ?>
                        <tr>
			    
			     <td><?php
				if(!empty($data['Booking']['pickup_date']) && date('Y-m-d',strtotime($data['Booking']['pickup_date'])) != '1970-01-01'){
				    echo date(DATE_FORMAT,strtotime($data['Booking']['pickup_date']));	
				}else{
				    echo '-';
				}
			     ?></td>
			     
			     
			     <td><?php 
			     $invoiceNo =  str_pad($data['Booking']['id'],6,'0',STR_PAD_LEFT);
			     echo $this->Html->link($invoiceNo,
                                        array('plugin' => false, 'controller' => 'invoices', 'action' => 'view', 'id' => base64_encode($data['Booking']['id']), 'admin' => true),
                                        array('class' => 'btn btn-transparent btn-xs', 'title' => 'Click here to View', 'escape' => false)
                             );
			     
			     
			     ?></td>
			     
			      <td><?php echo ucfirst($data['Driver']['first_name'].' '.$data['Driver']['last_name']); ?></td>
			      
                            <td><?php echo ucfirst($data['User']['first_name'].' '.$data['User']['last_name']); ?></td>
			   
                           
			    <td><?php if(!empty($data['CategoryLocale']['name'])){
					echo ucfirst($data['CategoryLocale']['name']);
			    }else{
					echo 'N/A';
			    } ?></td>
			    
			    <td><?php echo $data['Booking']['total_miles']; ?></td>
			    
			    
			    <td><?php echo '$ '.$data['Booking']['price']; ?></td>
			    <td><?php echo '$ '.$data['Transaction']['admin_pida_fee']; ?></td>
			    <td><?php echo '$ '.$data['Transaction']['admin_amount']; ?></td>
			    <td><?php echo '$ '.$data['Transaction']['admin_total_amount']; ?></td>
			    
			    <td>
                                <div>
                                    <?php
                                    echo $this->Html->link('<i class="fa fa-eye"></i>',
                                        array('plugin' => false, 'controller' => 'invoices', 'action' => 'view', 'id' => base64_encode($data['Booking']['id']), 'admin' => true),
                                        array('class' => 'btn btn-transparent btn-xs', 'title' => 'Click here to View', 'escape' => false)
                                    );
                                     ?>
                                </div>
                            </td>
                        </tr>
                        <?php
                    }
                    if('all' != $limit){ ?>
                        <tr>
                            <td colspan="11">
                                <?php // echo $this->element('pagination'); ?>
                            </td>
                        </tr>
                    <?php }
		    ?>
			
			
			
			
			
			<tr>
			    <td colspan="6">
				
			    </td>
                            <td>
				<?php
				    if(!empty($from) && !empty($to)){
					$value = date(DATE_FORMAT, strtotime($from)) . " - " . date(DATE_FORMAT, strtotime($to));
					echo $value.' Payment';	
				    }else if($week){
					$weekArr = explode('_',$week);
					$weekFrom = (!empty($weekArr[0]) ? $weekArr[0] : NULL);
					$weekTo = (!empty($weekArr[1]) ? $weekArr[1] : NULL);
					$value = date(DATE_FORMAT, strtotime($weekFrom)) . " - " . date(DATE_FORMAT, strtotime($weekTo));
					echo $value.' Payment';	
				    }    
				 ?>
                            </td>
			     <td>
                                <?php echo 'Pida fee charge'; ?>
                            </td>
			     <td>
                                <?php echo 'Pida percentage'; ?>
                            </td>
			     <td>
                                <?php echo 'Pida weekly profit'; ?>
                            </td>
			    <td>
				
			    </td>
			
			
			<tr>
			    <td colspan="6">
				
			    </td>
                            <td>
                                <?php echo '$ '.$sum_data[0][0]['price_sum']; ?>
                            </td>
			     <td>
                                <?php echo '$ '.$sum_data[0][0]['pida_fee_sum']; ?>
                            </td>
			     <td>
                                <?php echo '$ '.$sum_data[0][0]['admin_amount_sum']; ?>
                            </td>
			     <td>
                                <?php echo '$ '.$sum_data[0][0]['admin_total_amount_sum']; ?>
                            </td>
			    <td>
				
			    </td>
                        </tr>
		    <?php
                } else {
                    ?>
                    <tr>
                        <td colspan="11" class="text-center">Invoice Not Available !</td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function () {
	$('#booking_to').datepicker();
	$('#booking_from').datepicker();

	$('#select_week').on('change', function (e) {
	    var optionSelected = $("option:selected", this);
	    var valueSelected = this.value;
	    if(valueSelected){
		$('#booking_to').val('');
		$('#booking_from').val('');
	    }
	});
    });
</script>
<?php
  //  	pr($result_data);
?>
