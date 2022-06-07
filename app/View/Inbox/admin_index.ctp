
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

<?php $option_status = array('R' => 'Replied', 'P' => 'Pending');
$option_order = array(
    'FeedbackRequest.message ASC' => 'Message Ascending',
    'FeedbackRequest.message DESC' => 'Message Descending',
    'FeedbackTypeLocale.name ASC' => 'Feedback Type Ascending',
    'FeedbackTypeLocale.name DESC' => 'Feedback Type Descending',
    'FeedbackRequest.created ASC' => 'Created On Ascending',
    'FeedbackRequest.created DESC' => 'Created On Descending',
);

$search_txt = $status =  $search_feedback = $from = $to = '';
if($this->Session->check('inbox_search')){
    $search = $this->Session->read('inbox_search');
    $search_txt = $search['search'];
    $status = $search['status'];
    $search_feedback = $search['feedback_type_id'];
    $from = $search['from'];
    $to = $search['to'];
}
?>

<section id="page-title">
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <h1 class="mainTitle pull-left">Feedback Request List</h1>
            <div class="row pull-right">
                <?php
//		    echo $this->Html->link('Add Cargo Type <i class="fa fa-plus"></i>',
//                    array('plugin' => false, 'controller' => 'inbox', 'action' => 'add_edit', 'admin' => true),
//                    array('class' => 'btn btn-green', 'escape' => false)
//                );
                ?>
            </div>
        </div>
    </div>
</section>
<?php echo $this->Session->flash(); ?>

<div class="container-fluid container-fullw bg-white">

    <!-- start: SEARCH FORM START -->
    <div class="border-around margin-bottom-15 padding-10">
        <?php echo $this->Form->create('FeedbackRequest', array(
                'url' => array('controller' => 'inbox', 'action' => 'index', 'admin' => true), 'class' => 'form', 'role' => 'search', 'autocomplete' => 'off')
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
                <?php echo $this->Form->input('from', array('id'=>'from','value' => $from, 'class' => 'form-control reset-field', 'placeholder'=>'From', 'label' => false, 'div' => false, 'required' => false)); ?>
            </div>
        </div>
	
	<div class="col-md-4">
            <div class="form-group">
                <label class="control-label">To</label>
                <?php echo $this->Form->input('to', array('id'=>'to','value' => $to, 'class' => 'form-control reset-field', 'placeholder'=>'To', 'label' => false, 'div' => false, 'required' => false)); ?>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label">Feedback Type</label>
                <?php echo $this->Form->input('feedback_type_id', array('options' => $feedbackTypes, 'value' => $search_feedback, 'class' => 'form-control reset-field', 'empty' => 'All', 'label' => false, 'div' => false, 'required' => false)); ?>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label">Status</label>
                <?php echo $this->Form->input('status', array('options' => $option_status, 'value' => $status, 'class' => 'form-control reset-field', 'empty' => 'All', 'label' => false, 'div' => false, 'required' => false)); ?>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label">Order By</label>
                <?php echo $this->Form->input('order_by', array('options' => $option_order, 'value' => $order, 'id' => 'order_by', 'class' => 'form-control', 'empty' => false, 'label' => false, 'div' => false, 'required' => false)); ?>
            </div>
        </div>

        <div class="col-md-12">
            <?php   echo $this->Html->link('Reset <i class="fa fa-times-circle"></i>', array('action'=>'reset_filter','admin' => true), array('title' => 'Reset', 'escape' => false, 'class' => 'btn btn-primary btn-wide pull-right'));
            echo $this->Form->button('Search <i class="fa fa-arrow-circle-right"></i>',array('class' => 'btn btn-primary btn-wide pull-right margin-right-10','type' => 'submit','id' => 'submit_button')) ?>
        </div>

        <?php echo $this->Form->end(); ?>
        <div class="clearfix"></div>
    </div>

    <?php echo $this->Form->create('PageSize', array(
            'url' => array('controller' => 'inbox', 'action' => 'index', 'admin' => true), 'class' => 'form', 'autocomplete' => 'off', 'id' => 'pageSizeForm')
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
                    <th>Feedback Type</th>
		    <th>User's name</th>
                    <th>Message</th>
                    <th>Status</th>
                    <th>Created On</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php if (!empty($result_data)) { ?>
                    <?php foreach ($result_data as $data) { ?>
                        <tr>
                            <td><?php echo $data['FeedbackTypeLocale']['name']; ?></td>
			    <td><?php echo $data['User']['first_name'].' '.$data['User']['last_name']; ?></td>
                            <td><?php echo $data['FeedbackRequest']['message']; ?></td>
                            <td> <?php
                                if ($data['FeedbackRequest']['status'] == 'P') {
                                    echo 'Pending';
                                } else {
                                    echo 'Replied';
                                }
                                ?>
                            </td>
                            <td>
                                <?php echo date(DATETIME_FORMAT, $data['FeedbackRequest']['created']); ?>
                            </td>
                            <td>
                                <div>
                                    <?php
                                    
                                    echo $this->Html->link('<i class="fa fa-eye"></i>',
                                        array('plugin' => false, 'controller' => 'inbox', 'action' => 'view', base64_encode($data['FeedbackRequest']['id']), 'admin' => true),
                                        array('class' => 'btn btn-transparent btn-xs', 'title' => 'Click here to view feedback request', 'escape' => false)
                                    );
                                     ?>
                                </div>
                            </td>
                        </tr>
                        <?php
                    }
                    if('all' != $limit){ ?>
                        <tr>
                            <td colspan="6">
                                <?php echo $this->element('pagination'); ?>
                            </td>
                        </tr>
                    <?php }
                } else {
                    ?>
                    <tr>
                        <td colspan="6" class="text-center">Feedback request Not Available !</td>
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