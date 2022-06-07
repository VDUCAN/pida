<section id="page-title">
    <div class="row">
        <div class="col-sm-12 col-md-12 text-center">
            <h1 class="mainTitle">Pida Control Panel</h1>
        </div>
    </div>
</section>

<div class="help-block"><?php echo $this->Session->Flash(); ?></div>

<div class="container-fluid container-fullw bg-white">
    <div class="row">

        <div class="col-sm-4">
            <div class="panel panel-white no-radius text-center">
                <div class="panel-body">
                    <a href="<?php echo $this->Html->url(array('plugin' => false,'controller' => 'drivers','action' => 'index', 'admin' => true)); ?>" style="color: #5b5b60;" title="Click here to view drivers">
                        <div class="stat-area color-on-duty"><?php echo $on_duty_drivers_count; ?></div>
                        <p class="stat-txt">
                            Drivers On Duty
                        </p>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="panel panel-white no-radius text-center">
                <div class="panel-body">
                    <a href="<?php echo $this->Html->url(array('plugin' => false,'controller' => 'drivers','action' => 'online', 'admin' => true)); ?>" style="color: #5b5b60;" title="Click here to view drivers">
                        <div class="stat-area color-online"><?php echo $online_drivers_count; ?></div>
                        <p class="stat-txt">
                            Drivers Online
                        </p>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-sm-4">
            <div class="panel panel-white no-radius text-center">
                <div class="panel-body">
                    <a href="<?php echo $this->Html->url(array('plugin' => false,'controller' => 'customers','action' => 'index', 'admin' => true)); ?>" style="color: #5b5b60;" title="Click here to view customers">
                        <div class="stat-area color-usr"><?php echo $customers_count; ?></div>
                        <p class="stat-txt">
                            Customers
                        </p>
                    </a>
                </div>
            </div>
        </div>
        
    </div>
</div>
<!-- end: FEATURED BOX LINKS -->