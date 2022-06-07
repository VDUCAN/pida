<?php echo $this->Html->css(array('sweet-alert.css', 'ie9.css', 'toastr.min.css', 'select2.min.css', 'DT_bootstrap.css'), null, array('inline' => false));
echo $this->Html->script(array('select2.min.js', 'jquery.dataTables.min.js', 'table-data.js', 'sweet-alert.min.js', 'ui-notifications.js'), array('inline' => false)); ?>

<?php $option_status = array('A' => 'Active', 'I' => 'Inactive');
$option_order = array(
    'FaqLocale.question ASC' => 'Question Ascending',
    'FaqLocale.question DESC' => 'Question Descending',
    'FaqLocale.answer ASC' => 'Answer Ascending',
    'FaqLocale.answer DESC' => 'Answer Descending',
    'Faq.status ASC, Faq.created ASC' => 'Status Ascending',
    'Faq.status DESC, Faq.created ASC' => 'Status Descending',
    'Faq.created ASC' => 'Created On Ascending',
    'Faq.created DESC' => 'Created On Descending',
);

$search_txt = $status = $search_lang = '';
if($this->Session->check('faq_search')){
    $search = $this->Session->read('faq_search');
    $search_txt = $search['search'];
    $status = $search['status'];
    $search_lang = $search['lang_code'];
}
?>

<section id="page-title">
    <div class="row">
        <div class="col-sm-12 col-md-12">
            <h1 class="mainTitle pull-left">FAQ List</h1>
            <div class="row pull-right">

               <?php     echo $this->Html->link('Add FAQ <i class="fa fa-plus"></i>',
                        array('plugin' => false, 'controller' => 'faqs', 'action' => 'add_edit', 'admin' => true),
                        array('class' => 'btn btn-green', 'escape' => false)
                    ); ?>

            </div>
        </div>
    </div>
</section>
<?php echo $this->Session->flash(); ?>

<div class="container-fluid container-fullw bg-white overflow-x">

    <!-- start: SEARCH FORM START -->
    <div class="border-around margin-bottom-15 padding-10">
        <?php echo $this->Form->create('Faq', array(
                'url' => array('controller' => 'faqs', 'action' => 'index', 'admin' => true), 'class' => 'form', 'role' => 'search', 'autocomplete' => 'off')
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
                <label class="control-label">Order By</label>
                <?php echo $this->Form->input('order_by', array('options' => $option_order, 'value' => $order, 'id' => 'order_by', 'class' => 'form-control', 'empty' => false, 'label' => false, 'div' => false, 'required' => false)); ?>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label class="control-label">Status</label>
                <?php echo $this->Form->input('status', array('options' => $option_status, 'value' => $status, 'class' => 'form-control reset-field', 'empty' => 'All', 'label' => false, 'div' => false, 'required' => false)); ?>
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
            'url' => array('controller' => 'faqs', 'action' => 'index', 'admin' => true), 'class' => 'form', 'autocomplete' => 'off', 'id' => 'pageSizeForm')
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
                    <th>Question</th>
                    <th>Answer</th>
                    <th>Language</th>
                    <th>Status</th>
                    <th>Created On</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php if (!empty($faqs)) { ?>
                    <?php foreach ($faqs as $k => $faq) { ?>
                        <tr>
                            <td title="<?php echo ucfirst($faq['FaqLocale']['question']); ?>"><?php echo nl2br($this->Common->textLimit(ucfirst($faq['FaqLocale']['question']), 100)); ?></td>
                            <td title="<?php echo ucfirst($faq['FaqLocale']['answer']); ?>"><?php echo nl2br($this->Common->textLimit(ucfirst($faq['FaqLocale']['answer']), 100)); ?></td>
                            <td><?php echo $languages[$faq['FaqLocale']['lang_code']]; ?></td>
                            <td> <?php
                                if ($faq['Faq']['status'] == 'A') {
                                    echo $this->Html->image('/img/test-pass-icon.png', array('border' => 0, 'alt' => 'Active', 'title' => 'Active'));
                                } else {
                                    echo $this->Html->image('/img/cross.png', array('border' => 0, 'alt' => 'Inactive', 'title' => 'Inactive'));
                                }
                                ?>
                            </td>
                            <td>
                                <?php echo date(DATETIME_FORMAT, $faq['Faq']['created']); ?>
                            </td>
                            <td>
                                <div>
                                    <?php

                                        if ($faq['Faq']['status'] == 'A') {
                                            echo $this->Html->link('<i class="fa fa-check-circle"></i>', array('controller' => 'faqs', 'action' => 'status', base64_encode($faq['Faq']['id']), 'I', 'admin' => true), array('title' => 'Click here to inactive', 'escape' => false, 'class' => 'btn btn-transparent btn-xs'));
                                        } else {
                                            echo $this->Html->link('<i class="fa fa-times-circle"></i>', array('controller' => 'faqs', 'action' => 'status', base64_encode($faq['Faq']['id']), 'A', 'admin' => true), array('title' => 'Click here to active', 'escape' => false, 'class' => 'btn btn-transparent btn-xs'));
                                        }

                                        echo $this->Html->link('<i class="fa fa-pencil"></i>',
                                            array('plugin' => false, 'controller' => 'faqs', 'action' => 'add_edit', 'id' => base64_encode($faq['Faq']['id']), 'admin' => true),
                                            array('class' => 'btn btn-transparent btn-xs', 'title' => 'Click here to edit Faq', 'escape' => false)
                                        );


                                        echo $this->Html->link('<i class="fa fa-eye" data-toggle = "modal" data-target = "#myModal"></i>',
                                            '#myGallery',
                                            array('class' => 'btn btn-transparent btn-xs', 'title' => 'Click here to view', 'escape' => false, 'data-slide-to' => $k)
                                        );


                                        $href_lnk = $this->Html->url(array('plugin' => false, 'controller' => 'faqs', 'action' => 'delete', base64_encode($faq['Faq']['id']), 'admin' => true));
                                        echo $this->Html->link('<i class="fa fa-times"></i>',
                                            'javascript:void(0);',
                                            array('data-href' => $href_lnk, 'class' => 'delete-btn', 'title' => 'Click here to delete', 'escape' => false));
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
                        <td colspan="6" class="text-center">Faq Not Available !</td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<?php if(!empty($faqs)){ ?>
    <div aria-hidden="true" aria-labelledby="myModalLabel" class="modal fade" id="myModal" role="dialog" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">FAQ</h4>
                </div>
                <div class="modal-body">
                    <div class="carousel slide" data-interval="false" id="myGallery">
                        <div class="carousel-inner">
                            <?php
                            foreach ($faqs as $k => $faq) {

                                $active = (0 == $k) ? 'active' : '';
                                ?>

                                <div class="item <?php echo $active; ?>">

                                    <div class="col-md-12 text-bold text-large">Question :</div>

                                    <div class="col-md-12 "><?php echo nl2br(ucfirst($faq['FaqLocale']['question'])); ?></div>

                                    <div class="clearfix padding-bottom-15"></div>

                                    <div class="col-md-12  text-bold text-large">Answer :</div>
                                    <div class="col-md-12 "><?php echo nl2br(ucfirst($faq['FaqLocale']['answer'])); ?></div>

                                </div>
                            <?php }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-green add-row" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<script type="text/javascript">
    jQuery(document).ready(function () {
        UINotifications.init();
        TableData.init();

        jQuery('#reset_button').click(function(){
            jQuery('.reset-field').val('');
            jQuery('#order_by').val('Faq.created DESC');
        });

        jQuery('#records_per_page').change(function(){
            jQuery('#pageSizeForm').submit();
        });

        jQuery('.delete-btn').click(function () {
            var this_href = $(this).attr('data-href');
            swal({
                    title: "Are you sure?",
                    text: "FAQ will be deleted permanently",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: '#DD6B55',
                    confirmButtonText: 'Yes, delete it!',
                    closeOnConfirm: false,
                },
                function () {
                    window.location.href = this_href;
                });
        });

    });
</script>