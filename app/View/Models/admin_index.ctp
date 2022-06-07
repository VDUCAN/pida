<?php echo $this->Html->css(array('sweet-alert.css', 'ie9.css', 'toastr.min.css', 'select2.min.css', 'DT_bootstrap.css', 'bootstrap-datetimepicker'), null, array('inline' => false));
echo $this->Html->script(array('select2.min.js', 'jquery.dataTables.min.js', 'table-data.js', 'sweet-alert.min.js', 'ui-notifications.js', 'bootstrap-datepicker'), array('inline' => false)); ?>

<script type="text/javascript">
    jQuery(document).ready(function () {
        UINotifications.init();
        TableData.init();

        jQuery('#reset_button').click(function(){
            jQuery('.reset-field').val('');
            jQuery('#order_by').val('ModelLocale.name ASC');
        });

        jQuery('#records_per_page').change(function(){
            jQuery('#pageSizeForm').submit();
        });

    });
</script>

<?php $option_status = array('A' => 'Active', 'I' => 'Inactive');
$option_order = array(
    'ModelLocale.name ASC' => 'Model Name Ascending',
    'ModelLocale.name DESC' => 'Model Name Descending',
    'Models.status ASC, ModelLocale.name ASC' => 'Status Ascending',
    'Models.status DESC, ModelLocale.name ASC' => 'Status Descending',
    'Models.created ASC' => 'Created On Ascending',
    'Models.created DESC' => 'Created On Descending',
);

$search_txt = $status = $make = $search_lang =  '';
if($this->Session->check('model_search')){
    $search = $this->Session->read('model_search');
    $search_txt = $search['search'];
    $status = $search['status'];
    $make = $search['make'];
    $search_lang = $search['lang_code'];
}
?>

<section id="page-title">
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <h1 class="mainTitle pull-left">Vehicle Model List</h1>
            <div class="row pull-right">
                <?php
                echo $this->Html->link('Add Model <i class="fa fa-plus"></i>',
                    array('plugin' => false, 'controller' => 'models', 'action' => 'add_edit', 'admin' => true),
                    array('class' => 'btn btn-green', 'escape' => false)
                );
                ?>
            </div>
        </div>
    </div>
</section>
<?php echo $this->Session->flash(); ?>

<div class="container-fluid container-fullw bg-white">

    <!-- start: SEARCH FORM START -->
    <div class="border-around margin-bottom-15 padding-10">
        <?php echo $this->Form->create('Models', array(
                'url' => array('controller' => 'models', 'action' => 'index', 'admin' => true), 'class' => 'form', 'role' => 'search', 'autocomplete' => 'off')
        ); ?>

        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label">Search</label>
                <?php echo $this->Form->input('search', array('type' => 'text', 'value' => $search_txt, 'placeholder' => 'Search...', 'class' =>'form-control reset-field', 'div' => false, 'label' => false, 'required' => false)); ?>
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

        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label">Language</label>
                <?php echo $this->Form->input('lang_code', array('options' => $languages, 'value' => $search_lang, 'class' => 'form-control reset-field', 'empty' => 'All', 'label' => false, 'div' => false, 'required' => false)); ?>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label">Vehicle Make</label>
                <?php echo $this->Form->input('make', array('options' => $makes, 'value' => $make, 'id' => 'order_by', 'class' => 'form-control reset-field', 'empty' => '-- Select Vehicle Make --', 'label' => false, 'div' => false, 'required' => false)); ?>
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
            'url' => array('controller' => 'models', 'action' => 'index', 'admin' => true), 'class' => 'form', 'autocomplete' => 'off', 'id' => 'pageSizeForm')
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
                    <th>Model Name</th>
                    <th>Language</th>
                    <th>Vehicle Make</th>
                    <th>Status</th>
                    <th>Created On</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php if (!empty($result_data)) { ?>
                    <?php foreach ($result_data as $data) { ?>
                        <tr>
                            <td><?php echo ucfirst($data['ModelLocale']['name']); ?></td>
                            <td><?php echo $languages[$data['ModelLocale']['lang_code']]; ?></td>
                            <td><?php echo $makes[$data['Models']['make_id']]; ?></td>
                            <td> <?php
                                if ($data['Models']['status'] == 'A') {
                                    echo $this->Html->image('/img/test-pass-icon.png', array('border' => 0, 'alt' => 'Active', 'title' => 'Active'));
                                } else {
                                    echo $this->Html->image('/img/cross.png', array('border' => 0, 'alt' => 'Inactive', 'title' => 'Inactive'));
                                }
                                ?>
                            </td>
                            <td>
                                <?php echo date(DATETIME_FORMAT, $data['Models']['created']); ?>
                            </td>
                            <td>
                                <div>
                                    <?php
                                    if ($data['Models']['status'] == 'A') {
                                        echo $this->Html->link('<i class="fa fa-check-circle"></i>', array('controller' => 'models', 'action' => 'status', base64_encode($data['Models']['id']), 'I', 'admin' => true), array('title' => 'Click here to inactive', 'escape' => false, 'class' => 'btn btn-transparent btn-xs'));
                                    } else {
                                        echo $this->Html->link('<i class="fa fa-times-circle"></i>', array('controller' => 'models', 'action' => 'status', base64_encode($data['Models']['id']), 'A', 'admin' => true), array('title' => 'Click here to active', 'escape' => false, 'class' => 'btn btn-transparent btn-xs'));
                                    }

                                    echo $this->Html->link('<i class="fa fa-pencil"></i>',
                                        array('plugin' => false, 'controller' => 'models', 'action' => 'add_edit', 'id' => base64_encode($data['Models']['id']), 'admin' => true),
                                        array('class' => 'btn btn-transparent btn-xs', 'title' => 'Click here to edit model', 'escape' => false)
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
                        <td colspan="6" class="text-center">Model Name Not Available !</td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>