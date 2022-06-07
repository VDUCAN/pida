

<?php echo $this->Html->css(array('sweet-alert.css', 'ie9.css', 'toastr.min.css', 'select2.min.css', 'DT_bootstrap.css', 'bootstrap-datetimepicker'), null, array('inline' => false));
echo $this->Html->script(array('select2.min.js', 'jquery.dataTables.min.js', 'table-data.js', 'sweet-alert.min.js', 'ui-notifications.js', 'bootstrap-datepicker'), array('inline' => false)); ?>

<script type="text/javascript">
    jQuery(document).ready(function () {
        UINotifications.init();
        TableData.init();

        jQuery('#reset_button').click(function(){
            jQuery('.reset-field').val('');
            jQuery('#order_by').val('Booking.name ASC');
        });

        jQuery('#records_per_page').change(function(){
            jQuery('#pageSizeForm').submit();
        });

    });
</script>

<?php $option_status = array('1' => 'Open', '2' => 'Assign','3'=>'Cancel','4'=>'Arrived','5'=>'Completed','6'=>'Paid');
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

$search_txt = $status =  $search_lang = $type = $from = $to = '';
if($this->Session->check('booking_search')){
    $search = $this->Session->read('booking_search');
    $search_txt = $search['search'];
    $status = $search['booking_status'];
    $type = $search['booking_type'];
    $from = $search['booking_from'];
    $to = $search['booking_to'];
 //   $search_lang = $search['lang_code'];
}
?>

<section id="page-title">
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <h1 class="mainTitle pull-left">Booking List</h1>
            
        </div>
    </div>
</section>
<?php echo $this->Session->flash(); ?>

<div class="container-fluid container-fullw bg-white">

    <!-- start: SEARCH FORM START -->
    <div class="border-around margin-bottom-15 padding-10">
        <?php echo $this->Form->create('Booking', array(
                'url' => array('controller' => 'bookings', 'action' => 'index', 'admin' => true), 'class' => 'form', 'role' => 'search', 'autocomplete' => 'off')
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
	
	 <div class="col-md-4">
            <div class="form-group">
                <label class="control-label">Status</label>
                <?php echo $this->Form->input('booking_status', array('options' => $option_status, 'value' => $status, 'class' => 'form-control reset-field', 'empty' => 'All', 'label' => false, 'div' => false, 'required' => false)); ?>
            </div>
        </div>
	
	<div class="col-md-4">
            <div class="form-group">
                <label class="control-label">Type</label>
                <?php echo $this->Form->input('booking_type', array('options' => $option_type, 'value' => $type, 'class' => 'form-control reset-field', 'empty' => 'All', 'label' => false, 'div' => false, 'required' => false)); ?>
            </div>
        </div>
	


        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label">Order By</label>
                <?php echo $this->Form->input('order_by', array('options' => $option_order, 'value' => $order, 'id' => 'order_by', 'class' => 'form-control', 'empty' => false, 'label' => false, 'div' => false, 'required' => false)); ?>
            </div>
        </div>

        <div class="col-md-12">
            <?php 
	    echo $this->Html->link('Reset <i class="fa fa-times-circle"></i>', array('controller' => 'bookings','action'=>'reset_filter','admin' => true), array('title' => 'Reset', 'escape' => false, 'class' => 'btn btn-primary btn-wide pull-right'));
	    echo $this->Form->button('Search <i class="fa fa-arrow-circle-right"></i>',array('class' => 'btn btn-primary btn-wide pull-right margin-right-10','type' => 'submit','id' => 'submit_button')) ?>
        </div>

        <?php echo $this->Form->end(); ?>
        <div class="clearfix"></div>
    </div>

    <?php echo $this->Form->create('PageSize', array(
            'url' => array('controller' => 'bookings', 'action' => 'index', 'admin' => true), 'class' => 'form', 'autocomplete' => 'off', 'id' => 'pageSizeForm')
    ); ?>
    <div class="form-group pull-left">
        <label class="control-label">Records Per Page</label>
        <?php echo $this->Form->input('records_per_page', array('options' => unserialize(PAGING_OPTIONS), 'value' => $limit, 'id' => 'records_per_page', 'class' => 'form-control', 'empty' => false, 'label' => false, 'div' => false)); ?>
    </div>
    <?php echo $this->Form->end(); ?>

    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped table-bordered table-hover table-full-width">
                <thead>
                <tr>
		    <th>Booking Id</th>
                    <th>Customer</th>
                    <th>Driver</th>
		    <th>Pickup date</th>
                    <th>Price</th>
		    <th>Miles</th>
		    <th>Type</th>
		    <th>Status</th>
                    <th>Created On</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php if (!empty($result_data)) { ?>
                    <?php foreach ($result_data as $data) { ?>
                        <tr>
			     <td><?php echo $data['Booking']['id']; ?></td>
                            <td><?php echo ucfirst($data['User']['first_name'].' '.$data['User']['last_name']); ?></td>
			    <td><?php echo ucfirst($data['Driver']['first_name'].' '.$data['Driver']['last_name']); ?></td>
                            <td><?php
				if(!empty($data['Booking']['pickup_date']) && date('Y-m-d',strtotime($data['Booking']['pickup_date'])) != '1970-01-01'){
				    echo date(DATETIME_FORMAT,strtotime($data['Booking']['pickup_date']));	
				}else{
				    echo '-';
				}
			     ?></td>
			    <td><?php echo $data['Booking']['price']; ?></td>
			    <td><?php echo $data['Booking']['total_miles']; ?></td>
			    <td><?php
				    if($data['Booking']['booking_type']){
					echo $option_type[$data['Booking']['booking_type']];	
				    }else{
					echo '-';
				    }
			    ?></td>
			    <td><?php
				    if($data['Booking']['booking_status']){
					echo $option_status[$data['Booking']['booking_status']];
				    }else{
					echo '-';
				    }
			     ?></td>
			    <td><?php
				    if(!empty($data['Booking']['created']) && date('Y-m-d',$data['Booking']['created']) != '1970-01-01'){
					echo date(DATETIME_FORMAT,$data['Booking']['created']);
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
                    if('all' != $limit){ ?>
                        <tr>
                            <td colspan="10">
                                <?php echo $this->element('pagination'); ?>
                            </td>
                        </tr>
                    <?php }
                } else {
                    ?>
                    <tr>
                        <td colspan="10" class="text-center">Bookings Not Available !</td>
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
    });
</script>
<?php
    //	pr($result_data);
?>
