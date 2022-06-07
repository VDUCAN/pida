<?php echo $this->Html->css(array('sweet-alert.css', 'ie9.css', 'toastr.min.css', 'select2.min.css', 'DT_bootstrap.css', 'bootstrap-datetimepicker'), null, array('inline' => false));
echo $this->Html->script(array('select2.min.js', 'jquery.dataTables.min.js', 'table-data.js', 'sweet-alert.min.js', 'ui-notifications.js', 'bootstrap-datepicker'), array('inline' => false)); ?>

<script type="text/javascript">
    jQuery(document).ready(function () {
        UINotifications.init();
        TableData.init();

        jQuery('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            endDate: '0d'
        });

        jQuery('#reset_button').click(function(){
            jQuery('.reset-field').val('');
            jQuery('#order_by').val('User.created DESC');
        });

        jQuery('#records_per_page').change(function(){
            jQuery('#pageSizeForm').submit();
        });
		
		
	
    <?php /* ?>
		jQuery('a[id ^= delete_customer_]').click(function () {
            var thisID = $(this).attr('id');
            var breakID = thisID.split('_');
            var record_id = breakID[2];
            swal({
                title: "Are you sure?",
                text: "User will be deleted permanently",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes, delete it!',
                closeOnConfirm: false,
            },
            function () {
                jQuery.ajax({
                    type: 'get',
                     url: '<?php echo $this->Html->url(array('plugin' => false, 'controller' => 'drivers', 'action' => 'admin_delete')) ?>',
                    data: 'id=' + record_id,
                    dataType: 'json',
                    success: function (data) {
                        if (data.succ == '1') {
                            swal({
                                title: "Deleted!",
                                text: data.msg,
                                type: "success",
                                showCancelButton: false,
                                confirmButtonColor: '#d6e9c6',
                                confirmButtonText: 'OK',
                                closeOnConfirm: false,
                            }, function () {
                                window.location.reload();
                            });
                        } else {
                            swal({
                                title: "Error!",
                                text: data.msg,
                                type: "error",
                                showCancelButton: false,
                                confirmButtonColor: '#d6e9c6',
                                confirmButtonText: 'OK',
                                closeOnConfirm: false,
                            }, function () {
                                window.location.reload();
                            });
                        }
                    }
                });
            });
        });
        <?php */ ?>
		

    });
</script>

<?php $option_status = array('A' => 'Active', 'I' => 'Inactive');
$option_order = array(
    'User.first_name ASC, User.last_name ASC' => 'Name Ascending',
    'User.first_name DESC, User.last_name DESC' => 'Name Descending',
    'User.email ASC' => 'Email Ascending',
    'User.email DESC' => 'Email Descending',
    'User.driver_status ASC, User.first_name ASC, User.last_name ASC' => 'Status Ascending',
    'User.driver_status DESC, User.first_name ASC, User.last_name ASC' => 'Status Descending',
    'User.created ASC' => 'Created On Ascending',
    'User.created DESC' => 'Created On Descending',
);

$search_txt = $status = $registered_from = $registered_till = '';
if($this->Session->check('driver_online_search')){
    $search = $this->Session->read('driver_online_search');
    $search_txt = $search['search'];
//    $status = $search['status'];
    $registered_from = $search['registered_from'];
    $registered_till = $search['registered_till'];
}
?>

<section id="page-title">
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <h1 class="mainTitle pull-left">Online Driver List</h1>
        </div>
    </div>
</section>
<?php echo $this->Session->flash(); ?>

<div class="container-fluid container-fullw bg-white">

    <!-- start: SEARCH FORM START -->
    <div class="border-around margin-bottom-15 padding-10">
        <?php echo $this->Form->create('User', array(
                'url' => array('controller' => 'drivers', 'action' => 'online', 'admin' => true), 'class' => 'form', 'role' => 'search', 'autocomplete' => 'off')
        ); ?>

        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label">Search</label>
                <?php echo $this->Form->input('search', array('type' => 'text', 'value' => $search_txt, 'placeholder' => 'Search...', 'class' =>'form-control reset-field', 'div' => false, 'label' => false, 'required' => false)); ?>
            </div>
        </div>

        <?php /* ?>
        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label">Status</label>
                <?php echo $this->Form->input('status', array('options' => $option_status, 'value' => $status, 'class' => 'form-control reset-field', 'empty' => 'All', 'label' => false, 'div' => false, 'required' => false)); ?>
            </div>
        </div>
        <?php */ ?>

        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label">Order By</label>
                <?php echo $this->Form->input('order_by', array('options' => $option_order, 'value' => $order, 'id' => 'order_by', 'class' => 'form-control', 'empty' => false, 'label' => false, 'div' => false, 'required' => false)); ?>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label">Registered From</label>
                <?php echo $this->Form->input('registered_from', array('value' => $registered_from, 'type' => 'text', 'class' =>'form-control datepicker reset-field', 'div' => false, 'label' => false, 'required' => false)); ?>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label">Registered To</label>
                <?php echo $this->Form->input('registered_till', array('value' => $registered_till, 'type' => 'text', 'class' =>'form-control datepicker reset-field', 'div' => false, 'label' => false, 'required' => false)); ?>
            </div>
        </div>

        <div class="col-md-12">
            <?php echo $this->Form->button('Reset <i class="fa fa-times-circle"></i>',array('class' => 'btn btn-primary btn-wide pull-right','type' => 'button','id' => 'reset_button'));
            echo $this->Form->button('Search <i class="fa fa-arrow-circle-right"></i>',array('class' => 'btn btn-primary btn-wide pull-right margin-right-10','type' => 'submit','id' => 'submit_button')) ?>
        </div>

        <?php echo $this->Form->end(); ?>
        <div class="clearfix"></div>
    </div>

    <?php echo $this->Form->create('PageSize', array(
            'url' => array('controller' => 'drivers', 'action' => 'online', 'admin' => true), 'class' => 'form', 'autocomplete' => 'off', 'id' => 'pageSizeForm')
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
                    <th></th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>SSN</th>
                    <th>Driving License</th>
                    <th class="text-center">Status</th>
                    <th>Created On</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php if (!empty($result_data)) { ?>
                    <?php foreach ($result_data as $data) { ?>
                        <tr>
                            <td class="text-center">
                                <?php $photo = $data['User']['photo'];
                                if('' != $photo && file_exists(USER_PHOTO_PATH_THUMB . $photo)){ ?>
                                        <img src="<?php echo USER_PHOTO_URL_THUMB . $photo; ?>" width="50" />
                                <?php } ?>
                            </td>
                            <td>
							<?php
							$dirvername = ucfirst($data['User']['first_name']) . ' ' . ucfirst($data['User']['last_name']); 
							 echo $this->Html->link($dirvername,array('plugin' => false, 'controller' => 'drivers', 'action' => 'view', 'id' => base64_encode($data['User']['id']), 'admin' => true),array('class' => '', 'title' => 'Click here to view driver', 'escape' => false));
							?>
							</td>
							
							
                            <td>
								<a href="mailto:<?php echo $data['User']['email']; ?>"><?php echo $data['User']['email']; ?></a>
							</td>
                            <td>
								<?php echo $data['User']['phone']; ?>
							</td>
                            <td>
								<?php echo $data['DriverDetail']['ssn']; ?>
							</td>
                            <td>
                                <?php
                                $dl_doc = $data['DriverDetail']['driving_license_doc'];
                                if(!empty($data['DriverDetail']['driving_license_no'])) {
                                    if('' != $dl_doc && file_exists(DRIVER_DOC_PATH_LARGE . $dl_doc)){
                                        echo $this->Html->link($data['DriverDetail']['driving_license_no'],
                                            DRIVER_DOC_URL_LARGE . $dl_doc,
                                            array('escape' => false, 'target' => '_blank', 'title' => 'Click here to view driving license document')
                                        );
                                    }else {
                                        echo $data['DriverDetail']['driving_license_no'];
                                    }
                                }
                                elseif('' != $dl_doc && file_exists(DRIVER_DOC_PATH_LARGE . $dl_doc)) {
                                    echo $this->Html->link('View Driving License',
                                        DRIVER_DOC_URL_LARGE . $dl_doc,
                                        array('escape' => false, 'target' => '_blank', 'title' => 'Click here to view driving license document')
                                    );
                                }
                                ?>
                            </td>
                            <td class="text-center"> 
								<?php
									if ($data['DriverDetail']['is_online'] == 'Y') {
										echo $this->Html->image('/img/test-pass-icon.png', array('border' => 0, 'alt' => 'Active', 'title' => 'Active'));
									} else {
										echo $this->Html->image('/img/cross.png', array('border' => 0, 'alt' => 'Inactive', 'title' => 'Inactive'));
									}
								?>
                            </td>
                            <td>
                                <?php echo date(DATETIME_FORMAT, $data['User']['created']); ?>
                            </td>
                            <td>
                                <div>
                                    <?php
                                        if ($data['DriverDetail']['is_online'] == 'Y') {
                                            echo $this->Html->link('<i class="fa fa-check-circle"></i>', array('controller' => 'drivers', 'action' => 'offline', base64_encode($data['User']['id']), 'admin' => true), array('title' => 'Click here to make driver offline', 'escape' => false, 'class' => 'btn btn-transparent btn-xs'));
                                        }

                                        /*
                                        echo $this->Html->link('<i class="fa fa-pencil"></i>',
                                            array('plugin' => false, 'controller' => 'drivers', 'action' => 'add_edit', 'id' => base64_encode($data['User']['id']), 'admin' => true),
                                            array('class' => 'btn btn-transparent btn-xs', 'title' => 'Click here to edit driver', 'escape' => false)
                                        );
					
                                        echo $this->Html->link('<i class="fa fa-eye"></i>',
                                            array('plugin' => false, 'controller' => 'drivers', 'action' => 'view', 'id' => base64_encode($data['User']['id']), 'admin' => true),
                                            array('class' => 'btn btn-transparent btn-xs', 'title' => 'Click here to view driver', 'escape' => false)
                                        );
					
                                        echo $this->Html->link('<i class="fa fa-key"></i>',
                                            '/admin/drivers/change_password/' . base64_encode($data['User']['id']),
                                            array('class' => 'btn btn-transparent btn-xs tooltips', 'title' => 'Click here to change password', 'escape' => false)
                                        );
										
										
										echo $this->Html->link('<i class="fa fa-trash-o"></i>
										', 'javascript:void(0)', array('class' => 'btn btn-transparent btn-xs tooltips', 'tooltip-placement' => 'top', 'tooltip' => 'Remove', 'id' => 'delete_customer_' . $data['User']['id'], 'escape' => false)); */
										
                                     ?>
                                </div>
                            </td>
                        </tr>
                        <?php
                    }
                    if('all' != $limit){ ?>
                        <tr>
                            <td colspan="9">
                                <?php echo $this->element('pagination'); ?>
                            </td>
                        </tr>
                    <?php }
                } else {
                    ?>
                    <tr>
                        <td colspan="9" class="text-center">No Driver is online !</td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>