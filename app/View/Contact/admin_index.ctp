
<?php echo $this->Html->css(array('sweet-alert.css', 'ie9.css', 'toastr.min.css', 'select2.min.css', 'DT_bootstrap.css', 'bootstrap-datetimepicker'), null, array('inline' => false));
echo $this->Html->script(array('select2.min.js', 'jquery.dataTables.min.js', 'table-data.js', 'sweet-alert.min.js', 'ui-notifications.js', 'bootstrap-datepicker'), array('inline' => false)); ?>

<script type="text/javascript">
    jQuery(document).ready(function () {
        UINotifications.init();
        TableData.init();

        jQuery('#reset_button').click(function(){
            jQuery('.reset-field').val('');
            
        });

        jQuery('#records_per_page').change(function(){
            jQuery('#pageSizeForm').submit();
        });

    });
</script>

<?php 

$search_txt = $search_feedback = $from = $to = '';
if($this->Session->check('contact_search')){
    $search = $this->Session->read('contact_search');
    $search_txt = $search['search'];
    
    $search_feedback = $search['type'];
    $from = $search['from'];
    $to = $search['to'];
}
?>

<section id="page-title">
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <h1 class="mainTitle pull-left">Contact Request</h1>
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
        <?php echo $this->Form->create('ContactUs', array(
                'url' => array('controller' => 'contact', 'action' => 'index', 'admin' => true), 'class' => 'form', 'role' => 'search', 'autocomplete' => 'off')
        ); ?>

        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label">Search</label>
                <?php echo $this->Form->input('search', array('type' => 'text', 'value' => $search_txt, 'placeholder' => 'Search...', 'class' =>'form-control reset-field', 'div' => false, 'label' => false, 'required' => false)); ?>
            </div>
        </div>
	
	

        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label">Type</label>
                <?php echo $this->Form->input('type', array('options' => array('driver'=>'Driver','rider'=>'Rider'), 'value' => $search_feedback, 'class' => 'form-control reset-field', 'empty' => 'All', 'label' => false, 'div' => false, 'required' => false)); ?>
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
                    <th>First Name</th>
					<th>Last Name</th>
					<th>Email</th>
                    <th>Reason</th>
                    <th>Type</th>
                    <th>Message</th>
					<th>Received At</th>
                </tr>
                </thead>
                <tbody>
                <?php if (!empty($result_data)) { ?>
                    <?php foreach ($result_data as $data) { ?>
                        <tr>
                            <td><?php echo $data['ContactUs']['first_name']; ?></td>
							<td><?php echo $data['ContactUs']['last_name']; ?></td>
							<td><?php echo $data['ContactUs']['email']; ?></td>
                            <td><?php echo $data['ContactUs']['reason']; ?></td>
							<td><?php echo $data['ContactUs']['type']; ?></td>
							<td><?php echo $data['ContactUs']['message']; ?></td>
							<td><?php echo $data['ContactUs']['created_at']; ?></td>
                            
                        </tr>
                        <?php
                    }
                    if('all' != $limit){ ?>
                        <tr>
                            <td colspan="7">
                                <?php echo $this->element('pagination'); ?>
                            </td>
                        </tr>
                    <?php }
                } else {
                    ?>
                    <tr>
                        <td colspan="7" class="text-center">No Contact Request Available !</td>
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
