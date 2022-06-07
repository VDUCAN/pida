<?php /* ?>
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

<?php $option_status = array('A' => 'Active', 'I' => 'Inactive');
$option_order = array(
    'CargoTypeLocale.name ASC' => 'Cargo Type Ascending',
    'CargoTypeLocale.name DESC' => 'Cargo Type Descending',
    'CargoType.status ASC, CargoTypeLocale.name ASC' => 'Status Ascending',
    'CargoType.status DESC, CargoTypeLocale.name ASC' => 'Status Descending',
    'CargoType.created ASC' => 'Created On Ascending',
    'CargoType.created DESC' => 'Created On Descending',
);

$search_txt = $status =  $search_lang = '';
if($this->Session->check('cargo_type_search')){
    $search = $this->Session->read('cargo_type_search');
    $search_txt = $search['search'];
    $status = $search['status'];
    $search_lang = $search['lang_code'];
}
?>

<section id="page-title">
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <h1 class="mainTitle pull-left">Cargo Type List</h1>
            <div class="row pull-right">
                <?php
                echo $this->Html->link('Add Cargo Type <i class="fa fa-plus"></i>',
                    array('plugin' => false, 'controller' => 'cargo_types', 'action' => 'add_edit', 'admin' => true),
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
        <?php echo $this->Form->create('CargoType', array(
                'url' => array('controller' => 'cargo_types', 'action' => 'index', 'admin' => true), 'class' => 'form', 'role' => 'search', 'autocomplete' => 'off')
        ); ?>

        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label">Search</label>
                <?php echo $this->Form->input('search', array('type' => 'text', 'value' => $search_txt, 'placeholder' => 'Search...', 'class' =>'form-control reset-field', 'div' => false, 'label' => false, 'required' => false)); ?>
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
            <?php echo $this->Form->button('Reset <i class="fa fa-times-circle"></i>',array('class' => 'btn btn-primary btn-wide pull-right','type' => 'button','id' => 'reset_button'));
            echo $this->Form->button('Search <i class="fa fa-arrow-circle-right"></i>',array('class' => 'btn btn-primary btn-wide pull-right margin-right-10','type' => 'submit','id' => 'submit_button')) ?>
        </div>

        <?php echo $this->Form->end(); ?>
        <div class="clearfix"></div>
    </div>

    <?php echo $this->Form->create('PageSize', array(
            'url' => array('controller' => 'cargo_types', 'action' => 'index', 'admin' => true), 'class' => 'form', 'autocomplete' => 'off', 'id' => 'pageSizeForm')
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
                    <th>Cargo Type</th>
                    <th>Language</th>
                    <th>Status</th>
                    <th>Created On</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php if (!empty($result_data)) { ?>
                    <?php foreach ($result_data as $data) { ?>
                        <tr>
                            <td><?php echo ucfirst($data['CargoTypeLocale']['name']); ?></td>
                            <td><?php echo $languages[$data['CargoTypeLocale']['lang_code']]; ?></td>
                            <td> <?php
                                if ($data['CargoType']['status'] == 'A') {
                                    echo $this->Html->image('/img/test-pass-icon.png', array('border' => 0, 'alt' => 'Active', 'title' => 'Active'));
                                } else {
                                    echo $this->Html->image('/img/cross.png', array('border' => 0, 'alt' => 'Inactive', 'title' => 'Inactive'));
                                }
                                ?>
                            </td>
                            <td>
                                <?php echo date(DATETIME_FORMAT, $data['CargoType']['created']); ?>
                            </td>
                            <td>
                                <div>
                                    <?php
                                    if ($data['CargoType']['status'] == 'A') {
                                        echo $this->Html->link('<i class="fa fa-check-circle"></i>', array('controller' => 'cargo_types', 'action' => 'status', base64_encode($data['CargoType']['id']), 'I', 'admin' => true), array('title' => 'Click here to inactive', 'escape' => false, 'class' => 'btn btn-transparent btn-xs'));
                                    } else {
                                        echo $this->Html->link('<i class="fa fa-times-circle"></i>', array('controller' => 'cargo_types', 'action' => 'status', base64_encode($data['CargoType']['id']), 'A', 'admin' => true), array('title' => 'Click here to active', 'escape' => false, 'class' => 'btn btn-transparent btn-xs'));
                                    }

                                    echo $this->Html->link('<i class="fa fa-pencil"></i>',
                                        array('plugin' => false, 'controller' => 'cargo_types', 'action' => 'add_edit', 'id' => base64_encode($data['CargoType']['id']), 'admin' => true),
                                        array('class' => 'btn btn-transparent btn-xs', 'title' => 'Click here to edit Cargo Type', 'escape' => false)
                                    );
                                     ?>
                                </div>
                            </td>
                        </tr>
                        <?php
                    }
                    if('all' != $limit){ ?>
                        <tr>
                            <td colspan="5">
                                <?php echo $this->element('pagination'); ?>
                            </td>
                        </tr>
                    <?php }
                } else {
                    ?>
                    <tr>
                        <td colspan="5" class="text-center">Cargo Type Not Available !</td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php */ ?>