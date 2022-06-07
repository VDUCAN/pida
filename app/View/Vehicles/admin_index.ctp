<?php echo $this->Html->css(array('sweet-alert.css', 'ie9.css', 'toastr.min.css', 'select2.min.css', 'DT_bootstrap.css', 'bootstrap-datetimepicker'), null, array('inline' => false));
echo $this->Html->script(array('select2.min.js', 'jquery.dataTables.min.js', 'table-data.js', 'sweet-alert.min.js', 'ui-notifications.js', 'bootstrap-datepicker'), array('inline' => false));
?>
<script type="text/javascript">
    jQuery(document).ready(function () {
     
		
		jQuery('a[id ^= delete_customer_]').click(function () {
            var thisID = $(this).attr('id');
            var breakID = thisID.split('_');
            var record_id = breakID[2];
            swal({
                title: "Are you sure?",
                text: "vehicle will be deleted permanently",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes, delete it!',
                closeOnConfirm: false,
            },
            function () {
                jQuery.ajax({
                    type: 'get',
                     url: '<?php echo $this->Html->url(array('plugin' => false, 'controller' => 'vehicles', 'action' => 'admin_delete')) ?>'+'/'+record_id,
  //                  data: 'id=' + record_id,
                    dataType: 'json',
                    success: function (data) {
		//	console.log(data);return false;
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
		
		
		
		
    });
</script>
<?php 
$doc_status = $search_status = unserialize(DOC_STATUS);
    $colspan = 15;
    $heading =  'Vehicle Management';
    if('' != $type){
        unset($search_status['A']);
        $colspan = 14;

        if('approved' == $type){
            $heading = 'Approved Vehicles';
        }
        elseif('pending' == $type){
            $heading = 'Approval Pending Vehicles';
        }

    }

$option_order = array(
    'User.first_name ASC, User.last_name ASC' => 'Name Ascending',
    'User.first_name DESC, User.last_name DESC' => 'Name Descending',
    'Vehicle.make_year ASC' => 'Make Year On Ascending',
    'Vehicle.make_year DESC' => 'Make Year On Descending',
    'Vehicle.insurance_expiry_date ASC' => 'Insurance Expiry On Ascending',
    'Vehicle.insurance_expiry_date DESC' => 'Insurance Expiry On Descending',
    'Vehicle.created ASC' => 'Created On Ascending',
    'Vehicle.created DESC' => 'Created On Descending',
);

$search_txt = $registered_from = $registered_till = $make_id = $model_id = $search_status_reg_doc = $search_status_insurance_doc = '';
if($this->Session->check($session_name)){
    $search = $this->Session->read($session_name);
    $search_txt = $search['search'];
    $search_status_reg_doc = $search['is_registration_doc_approved'];
    $search_status_insurance_doc = $search['is_insurance_policy_doc_approved'];
    $registered_from = $search['registered_from'];
    $registered_till = $search['registered_till'];
    $make_id = $search['make_id'];
    $model_id = $search['model_id'];
}
?>

<section id="page-title">
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <h1 class="mainTitle pull-left"><?php echo $heading; ?></h1>
            <div class="row pull-right">
                <?php if('' == $type) {
                    echo $this->Html->link('Add Vehicle <i class="fa fa-plus"></i>',
                        array('plugin' => false, 'controller' => 'vehicles', 'action' => 'add_edit', 'admin' => true),
                        array('class' => 'btn btn-green', 'escape' => false)
                    );
                }
                ?>
            </div>
        </div>
    </div>
</section>
<?php echo $this->Session->flash(); ?>

<div class="container-fluid container-fullw bg-white overflow-x">

    <!-- start: SEARCH FORM START -->

    <div class="border-around margin-bottom-15 padding-10">
        <?php echo $this->Form->create('Vehicle', array(
                'url' => array('controller' => 'vehicles', 'action' => 'index', 'type' => $type, 'admin' => true), 'class' => 'form', 'role' => 'search', 'autocomplete' => 'off')
        ); ?>

        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label">Search</label>
                <?php echo $this->Form->input('search', array('type' => 'text', 'value' => $search_txt, 'placeholder' => 'Search...', 'class' =>'form-control reset-field', 'div' => false, 'label' => false, 'required' => false)); ?>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label">Vehicle Make</label>
                <?php echo $this->Form->input('make_id', array('options' => $makes, 'value' => $make_id, 'class' => 'form-control reset-field', 'empty' => 'All', 'label' => false, 'div' => false, 'required' => false)); ?>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label">Vehicle Model</label>
                <?php echo $this->Form->input('model_id', array('options' => $models, 'value' => $model_id, 'class' => 'form-control reset-field', 'empty' => 'All', 'label' => false, 'div' => false, 'required' => false)); ?>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label">Created From</label>
                <?php echo $this->Form->input('registered_from', array('value' => $registered_from, 'type' => 'text', 'class' =>'form-control datepicker reset-field', 'div' => false, 'label' => false, 'required' => false)); ?>
            </div>
        </div>

        <?php if('approved' != $type){ ?>

            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Registration Doc Status</label>
                    <?php echo $this->Form->input('is_registration_doc_approved', array('options' => $search_status, 'value' => $search_status_reg_doc, 'class' => 'form-control reset-field', 'empty' => 'All', 'label' => false, 'div' => false, 'required' => false)); ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Insurance Doc Status</label>
                    <?php echo $this->Form->input('is_insurance_policy_doc_approved', array('options' => $search_status, 'value' => $search_status_insurance_doc, 'class' => 'form-control reset-field', 'empty' => 'All', 'label' => false, 'div' => false, 'required' => false)); ?>
                </div>
            </div>
        <?php } ?>

        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label">Created To</label>
                <?php echo $this->Form->input('registered_till', array('value' => $registered_till, 'type' => 'text', 'class' =>'form-control datepicker reset-field', 'div' => false, 'label' => false, 'required' => false)); ?>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label">Order By</label>
                <?php echo $this->Form->input('order_by', array('options' => $option_order, 'value' => $order, 'id' => 'order_by', 'class' => 'form-control', 'empty' => false, 'label' => false, 'div' => false, 'required' => false)); ?>
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
            'url' => array('controller' => 'vehicles', 'action' => 'index', 'type' => $type, 'admin' => true), 'class' => 'form', 'autocomplete' => 'off', 'id' => 'pageSizeForm')
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
                    <th>Vehicle Make</th>
                    <th>Vehicle Model</th>
                    <th>Make Year</th>
                    <th>Color</th>
                    <th>Vehicle Type</th>
                    <th>Plate Number</th>
                    <th>Registration Number</th>
                    <th class="text-center">Registration Doc Status</th>
                    <th>Insurance Policy Number</th>
                    <th>Insurance Expiry Date</th>
                    <th class="text-center">Insurance Doc Status</th>
                    <th class="text-center">Status</th>
                    <th>Created On</th>
                    <?php if('' == $type){ ?>
                        <th>Action</th>
                    <?php } ?>
                </tr>
                </thead>
                <tbody>
                <?php if (!empty($result_data)) { ?>
                    <?php foreach ($result_data as $data) { ?>
                        <tr>
                            <td>
                                <?php  
				$name = ucfirst($data['User']['first_name']) . ' ' . ucfirst($data['User']['last_name']);
				echo $this->Html->link($name,array('plugin' => false, 'controller' => 'drivers', 'action' => 'view', 'id' => base64_encode($data['User']['id']), 'admin' => true),array('class' => 'btn btn-transparent btn-xs', 'title' => 'Click here to view driver', 'escape' => false));				     ?>
                            </td>
                            <td>
                                <?php if(isset($data['Make']['MakeLocale'])) {
                                    foreach ($data['Make']['MakeLocale'] as $mk) {
                                        if ('en' == $mk['lang_code']) {
                                            echo $mk['name'];
                                            break;
                                        }
                                    }
                                }
                                ?>
                            </td>
                            <td>
                                <?php if(isset($data['Models']['ModelLocale'])) {
                                    foreach ($data['Models']['ModelLocale'] as $mk) {
                                        if ('en' == $mk['lang_code']) {
                                            echo $mk['name'];
                                            break;
                                        }
                                    }
                                }
                                ?>
                            </td>
                            <td><?php echo $data['Vehicle']['make_year']; ?></td>
                            <td><?php echo $data['Vehicle']['color']; ?></td>
                            <td><?php echo $data['Vehicle']['vehicle_type']; ?></td>
                            <td><?php echo $data['Vehicle']['plate_no']; ?></td>
                            <td>
                                <?php
                                $registration_doc = $data['Vehicle']['registration_doc'];
                                if(!empty($data['Vehicle']['registration_no'])) {
                                    if('' != $registration_doc && file_exists(VEHICLE_DOC_PATH_LARGE . $registration_doc)){
                                        echo $this->Html->link($data['Vehicle']['registration_no'],
                                            VEHICLE_DOC_URL_LARGE . $registration_doc,
                                            array('escape' => false, 'target' => '_blank', 'title' => 'Click here to view registration document')
                                        );
                                    }else {
                                        echo $data['Vehicle']['registration_no'];
                                    }
                                }
                                elseif('' != $registration_doc && file_exists(VEHICLE_DOC_PATH_LARGE . $registration_doc)) {
                                    echo $this->Html->link('View Registration Document',
                                        VEHICLE_DOC_URL_LARGE . $registration_doc,
                                        array('escape' => false, 'target' => '_blank', 'title' => 'Click here to view registration document')
                                    );
                                }
                                ?>
                            </td>

                            <td class="text-center">

                                <?php
                                if ($data['Vehicle']['is_registration_doc_approved'] == 'A') {

                                    $href_lnk = $this->Html->url(array('plugin' => false, 'controller' => 'vehicles', 'action' => 'approve_registration_doc', base64_encode($data['Vehicle']['id']), 'R', 'admin' => true));
                                    echo $this->Html->link('Reject',
                                        'javascript:void(0);',
                                        array('data-href' => $href_lnk, 'data-type' => 'R', 'class' => 'text-red text-underline approve-doc', 'title' => 'Click here to reject registration document', 'escape' => false)
                                    );
                                }
                                elseif ($data['Vehicle']['is_registration_doc_approved'] == 'R') {

                                    echo '<span class="text-red">' . $doc_status[$data['Vehicle']['is_registration_doc_approved']] . '</span>';
                                    echo '<br />';

                                    $href_lnk = $this->Html->url(array('plugin' => false, 'controller' => 'vehicles', 'action' => 'approve_registration_doc', base64_encode($data['Vehicle']['id']), 'A', 'admin' => true));
                                    echo $this->Html->link('Approve',
                                        'javascript:void(0);',
                                        array('data-href' => $href_lnk, 'data-type' => 'A', 'class' => 'text-primary text-underline approve-doc', 'title' => 'Click here to approve registration document', 'escape' => false)
                                    );
                                }
                                elseif ($data['Vehicle']['is_registration_doc_approved'] == 'P') {

                                    $href_lnk = $this->Html->url(array('plugin' => false, 'controller' => 'vehicles', 'action' => 'approve_registration_doc', base64_encode($data['Vehicle']['id']), 'A', 'admin' => true));
                                    echo $this->Html->link('Approve',
                                        'javascript:void(0);',
                                        array('data-href' => $href_lnk, 'data-type' => 'A', 'class' => 'text-primary text-underline approve-doc', 'title' => 'Click here to approve registration document', 'escape' => false)
                                    );
                                    echo '<br />';

                                    $href_lnk = $this->Html->url(array('plugin' => false, 'controller' => 'vehicles', 'action' => 'approve_registration_doc', base64_encode($data['Vehicle']['id']), 'R', 'admin' => true));
                                    echo $this->Html->link('Reject',
                                        'javascript:void(0);',
                                        array('data-href' => $href_lnk, 'data-type' => 'R', 'class' => 'text-red text-underline approve-doc', 'title' => 'Click here to reject registration document', 'escape' => false)
                                    );
                                }
                                ?>

                            </td>

                            <td>
                                <?php
                                $insurance_policy_doc = $data['Vehicle']['insurance_policy_doc'];
                                if(!empty($data['Vehicle']['insurance_policy_no'])) {
                                    if('' != $insurance_policy_doc && file_exists(VEHICLE_DOC_PATH_LARGE . $insurance_policy_doc)){
                                        echo $this->Html->link($data['Vehicle']['insurance_policy_no'],
                                            VEHICLE_DOC_URL_LARGE . $insurance_policy_doc,
                                            array('escape' => false, 'target' => '_blank', 'title' => 'Click here to view insurance policy document')
                                        );
                                    }else {
                                        echo $data['Vehicle']['insurance_policy_no'];
                                    }
                                }
                                elseif('' != $insurance_policy_doc && file_exists(VEHICLE_DOC_PATH_LARGE . $insurance_policy_doc)) {
                                    echo $this->Html->link('View Insurance Policy',
                                        VEHICLE_DOC_URL_LARGE . $insurance_policy_doc,
                                        array('escape' => false, 'target' => '_blank', 'title' => 'Click here to view insurance policy document')
                                    );
                                }
                                ?>
                            </td>

                            <td>
                                <?php
								
									if($data['Vehicle']['insurance_expiry_date']){
										
										echo date(DATE_FORMAT, strtotime($data['Vehicle']['insurance_expiry_date'])); 
									}else{
										
										echo "N/A";
										
										
									}							
							
								?> 
								 
								

                            </td>

                            <td class="text-center">

                                <?php
                                if ($data['Vehicle']['is_insurance_policy_doc_approved'] == 'A') {

                                    $href_lnk = $this->Html->url(array('plugin' => false, 'controller' => 'vehicles', 'action' => 'approve_insurance_doc', base64_encode($data['Vehicle']['id']), 'R', 'admin' => true));
                                    echo $this->Html->link('Reject',
                                        'javascript:void(0);',
                                        array('data-href' => $href_lnk, 'data-type' => 'R', 'class' => 'text-red text-underline approve-doc', 'title' => 'Click here to reject insurance policy document', 'escape' => false)
                                    );
                                }
                                elseif ($data['Vehicle']['is_insurance_policy_doc_approved'] == 'R') {

                                    echo '<span class="text-red">' . $doc_status[$data['Vehicle']['is_insurance_policy_doc_approved']] . '</span>';
                                    echo '<br />';

                                    $href_lnk = $this->Html->url(array('plugin' => false, 'controller' => 'vehicles', 'action' => 'approve_insurance_doc', base64_encode($data['Vehicle']['id']), 'A', 'admin' => true));
                                    echo $this->Html->link('Approve',
                                        'javascript:void(0);',
                                        array('data-href' => $href_lnk, 'data-type' => 'A', 'class' => 'text-primary text-underline approve-doc', 'title' => 'Click here to approve insurance policy document', 'escape' => false)
                                    );
                                }
                                elseif ($data['Vehicle']['is_insurance_policy_doc_approved'] == 'P') {

                                    $href_lnk = $this->Html->url(array('plugin' => false, 'controller' => 'vehicles', 'action' => 'approve_insurance_doc', base64_encode($data['Vehicle']['id']), 'A', 'admin' => true));
                                    echo $this->Html->link('Approve',
                                        'javascript:void(0);',
                                        array('data-href' => $href_lnk, 'data-type' => 'A', 'class' => 'text-primary text-underline approve-doc', 'title' => 'Click here to approve insurance policy document', 'escape' => false)
                                    );
                                    echo '<br />';

                                    $href_lnk = $this->Html->url(array('plugin' => false, 'controller' => 'vehicles', 'action' => 'approve_insurance_doc', base64_encode($data['Vehicle']['id']), 'R', 'admin' => true));
                                    echo $this->Html->link('Reject',
                                        'javascript:void(0);',
                                        array('data-href' => $href_lnk, 'data-type' => 'R', 'class' => 'text-red text-underline approve-doc', 'title' => 'Click here to reject insurance policy document', 'escape' => false)
                                    );
                                }
                                ?>

                            </td>

                            <td>
                                <?php
                                if ($data['Vehicle']['status'] == 'A') {
                                    $img = $this->Html->image('/img/test-pass-icon.png', array('border' => 0, 'alt' => 'Active', 'title' => 'Click here to inactive'));
                                    echo $this->Html->link($img, array('controller' => 'vehicles', 'action' => 'status', base64_encode($data['Vehicle']['id']), 'I', 'admin' => true), array('title' => 'Click here to inactive', 'escape' => false, 'class' => 'btn btn-transparent btn-xs'));

                                } else {
                                    $img = $this->Html->image('/img/cross.png', array('border' => 0, 'alt' => 'Inactive', 'title' => 'Click here to active'));
                                    echo $this->Html->link($img, array('controller' => 'vehicles', 'action' => 'status', base64_encode($data['Vehicle']['id']), 'A', 'admin' => true), array('title' => 'Click here to active', 'escape' => false, 'class' => 'btn btn-transparent btn-xs'));
                                }
                                ?>
                            </td>

                            <td>
                                <?php echo date(DATETIME_FORMAT, $data['Vehicle']['created']);

								//echo $this->Time->format($data['Vehicle']['created'], '%B %e, %Y %H:%M %p');
								?>
                            </td>

                            <?php if('' == $type){ ?>
                                <td>
                                    <div>
                                        <?php
                                        echo $this->Html->link('<i class="fa fa-pencil"></i>',
                                            array('plugin' => false, 'controller' => 'vehicles', 'action' => 'add_edit', 'id' => base64_encode($data['Vehicle']['id']), 'admin' => true),
                                            array('class' => 'btn btn-transparent btn-xs', 'title' => 'Click here to vehicle', 'escape' => false)
                                        );


                                        if (isset($data['User']['DriverDetail']['vehicle_id']) && $data['User']['DriverDetail']['vehicle_id'] == $data['Vehicle']['id']) {
                                            echo $this->Html->link('<i class="fa fa-check-square-o"></i>', 'javascript:void(0);', array('title' => 'Vehicle assigned to driver', 'escape' => false, 'class' => 'btn btn-transparent btn-xs'));
                                        } else {
                                            echo $this->Html->link('<i class="fa fa-square-o"></i>', array('controller' => 'vehicles', 'action' => 'assign_driver', base64_encode($data['Vehicle']['id']), base64_encode($data['Vehicle']['user_id']), 'admin' => true), array('title' => 'Click here to assign this vehicle to driver', 'escape' => false, 'class' => 'btn btn-transparent btn-xs'));
                                        }
										
										
										echo $this->Html->link('<i class="fa fa-trash-o"></i>
										', 'javascript:void(0)', array('class' => 'btn btn-transparent btn-xs tooltips', 'tooltip-placement' => 'top', 'tooltip' => 'Remove', 'id' => 'delete_customer_' . $data['Vehicle']['id'], 'escape' => false));

                                        ?>
                                    </div>
                                </td>
                            <?php } ?>

                        </tr>
                        <?php
                    }
                    if('all' != $limit){ ?>
                        <tr>
                            <td colspan="<?php echo $colspan; ?>">
                                <?php echo $this->element('pagination'); ?>
                            </td>
                        </tr>
                    <?php }
                } else {
                    ?>
                    <tr>
                        <td colspan="<?php echo $colspan; ?>" class="text-center">Vehicle Not Available !</td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

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
            jQuery('#order_by').val('Vehicle.created DESC');
        });

        jQuery('#records_per_page').change(function(){
            jQuery('#pageSizeForm').submit();
        });

        jQuery('.approve-doc').click(function () {
            var this_href = $(this).attr('data-href');
            var this_type = $(this).attr('data-type');
            var txt_msg = '';
            var confirm_msg = '';

            if('A' == this_type){
                txt_msg = 'Approve this document';
                confirm_msg = 'Yes, approve it!';
            }
            else if('R' == this_type){
                txt_msg = 'Reject this document';
                confirm_msg = 'Yes, reject it!';
            }

            swal({
                    title: "Are you sure?",
                    text: txt_msg,
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: '#DD6B55',
                    confirmButtonText: confirm_msg,
                    closeOnConfirm: false,
                },
                function () {
                    window.location.href = this_href;
                });
        });

    });
</script>