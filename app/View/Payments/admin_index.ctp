
<?php echo $this->Html->css(array('sweet-alert.css', 'ie9.css', 'toastr.min.css', 'select2.min.css', 'DT_bootstrap.css', 'bootstrap-datetimepicker'), null, array('inline' => false));
echo $this->Html->script(array('select2.min.js', 'jquery.dataTables.min.js', 'table-data.js', 'sweet-alert.min.js', 'ui-notifications.js', 'bootstrap-datepicker'), array('inline' => false)); ?>

<script type="text/javascript">
    jQuery(document).ready(function () {
        UINotifications.init();
        TableData.init();

        jQuery('#reset_button').click(function(){
            jQuery('.reset-field').val('');
            jQuery('#order_by').val('CargoTypeLocale.name ASC');
        });

        jQuery('#records_per_page').change(function(){
            jQuery('#pageSizeForm').submit();
        });

    });
</script>

<?php 
$option_transactions = array('1' => 'Pending', '2' => 'Paid');
$option_order = array(
    'Driver.first_name ASC' => 'Driver Name Ascending',
    'Driver.first_name DESC' => 'Driver Name Descending',
    'Transaction.created ASC' => 'Date Range On Ascending',
    'Transaction.created DESC' => 'Date Range On Descending',
);

$search_txt = $status =  $search_lang = $from = $to = '';
if($this->Session->check('transaction_search')){
    $search = $this->Session->read('transaction_search');
    $search_txt = $search['search'];
    $status = $search['status'];
    $from = $search['from'];
    $to = $search['to'];
}
?>

<section id="page-title">
    <div class="row">
        <div class="col-sm-12 col-md-12">
	    <?php /* ?>
            <h1 class="mainTitle pull-left">Cargo Type List</h1>
            <div class="row pull-right">
                <?php
                echo $this->Html->link('Add Cargo Type <i class="fa fa-plus"></i>',
                    array('plugin' => false, 'controller' => 'cargo_types', 'action' => 'add_edit', 'admin' => true),
                    array('class' => 'btn btn-green', 'escape' => false)
                );
                ?>
            </div>
	    <?php */ ?>
        </div>
    </div>
</section>
<?php echo $this->Session->flash(); ?>

<div class="container-fluid container-fullw bg-white">

    <!-- start: SEARCH FORM START -->
    <div class="border-around margin-bottom-15 padding-10">
        <?php echo $this->Form->create('Payments', array(
                'url' => array('action' => 'index', 'admin' => true), 'class' => 'form', 'role' => 'search', 'autocomplete' => 'off')
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
                <?php echo $this->Form->input('from', array('id'=>'from','value' => $from, 'class' => 'form-control reset-field', 'placeholder'=>'Booking From', 'label' => false, 'div' => false, 'required' => false)); ?>
            </div>
        </div>
	
	<div class="col-md-4">
            <div class="form-group">
                <label class="control-label">To</label>
                <?php echo $this->Form->input('to', array('id'=>'to','value' => $to, 'class' => 'form-control reset-field', 'placeholder'=>'Booking To', 'label' => false, 'div' => false, 'required' => false)); ?>
            </div>
        </div>


        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label">Status</label>
                <?php echo $this->Form->input('status', array('options' => $option_transactions, 'value' => $status, 'class' => 'form-control reset-field', 'empty' => 'All', 'label' => false, 'div' => false, 'required' => false)); ?>
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
	    echo $this->Html->link('Reset <i class="fa fa-times-circle"></i>', array('action'=>'reset_filter','admin' => true), array('title' => 'Reset', 'escape' => false, 'class' => 'btn btn-primary btn-wide pull-right'));
            echo $this->Form->button('Search <i class="fa fa-arrow-circle-right"></i>',array('class' => 'btn btn-primary btn-wide pull-right margin-right-10','type' => 'submit','id' => 'submit_button')) ?>
        </div>

        <?php echo $this->Form->end(); ?>
        <div class="clearfix"></div>
    </div>

    <?php echo $this->Form->create('PageSize', array(
            'url' => array('controller' => 'payments', 'action' => 'index', 'admin' => true), 'class' => 'form', 'autocomplete' => 'off', 'id' => 'pageSizeForm')
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
                    <th>Driver Name</th>
		    <th>Driver Amount</th>
		    <th>Admin Amount</th>
		    <th>Commission Precentage(%)</th>
                    <th>Miles</th>
                    <th>DateRange</th>
		    <th>Status</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php if (!empty($result_data)) { ?>
                    <?php foreach ($result_data as $data) { ?>
                        <tr>
                            <td><?php echo ucfirst($data['Driver']['first_name'].' '.$data['Driver']['last_name']); ?></td>
			    <td><?php echo $data[0]['total_driver_amount']; ?></td>
			    <td><?php echo $data[0]['total_admin_amount']; ?></td>
			    <td><?php echo $data['Transaction']['admin_percent']; ?></td>
			    <td><?php echo $data[0]['total_miles']; ?></td>
			    <td><?php echo $data[0]['date_range']; ?></td>
			    <td><?php echo $option_transactions[$data['Transaction']['status']]; ?></td>
                            <td>
                                <div>
                                    <?php
                                    echo $this->Html->link('<i class="fa fa-eye"></i>',
                                        array('plugin' => false, 'controller' => 'payments', 'action' => 'view', 'id' => base64_encode($data[0]['transaction_id']),'date_range'=>  base64_encode($data[0]['date_range']), 'admin' => true),
                                        array('class' => 'btn btn-transparent btn-xs', 'title' => 'Click here to view', 'escape' => false)
                                    );
				    
				    if($data['Transaction']['status']==1){
					echo $this->Html->link('<i class="fa fa-credit-card"></i>',
					    array('plugin' => false, 'controller' => 'payments', 'action' => 'pay_view', 'id' => base64_encode($data[0]['transaction_id']),'date_range'=>  base64_encode($data[0]['date_range']), 'admin' => true),
					    array('class' => 'btn btn-transparent btn-xs', 'title' => 'Click here to view payment', 'escape' => false)
					);
				    }
				    
                                     ?>
                                </div>
                            </td>
                        </tr>
                        <?php
                    }
                    if('all' != $limit){ ?>
                        <tr>
                            <td colspan="8">
                                <?php echo $this->element('pagination'); ?>
                            </td>
                        </tr>
                    <?php }
                } else {
                    ?>
                    <tr>
                        <td colspan="8" class="text-center">Transactions Not Available !</td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function () {
	$('#to').datepicker();
	$('#from').datepicker();
    });
</script>
<?php
//pr($result_data);
?>
