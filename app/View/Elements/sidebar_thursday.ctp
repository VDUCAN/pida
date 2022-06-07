<div class="sidebar app-aside" id="sidebar">
    <div class="sidebar-container perfect-scrollbar">
        <nav>

            <!-- end: SEARCH FORM -->
            <!-- start: MAIN NAVIGATION MENU -->
            <div class="navbar-title">
                <span>Main Navigation</span>
            </div>
            <ul class="main-navigation-menu">
                <li class="<?php echo (isset($tab_open) && $tab_open == 'dashboard') ? 'active open' : '' ?>">
                    <a href="<?php echo $this->Html->url(array('plugin' => false,'controller' => 'admins','action' => 'dashboard', 'admin' => true)); ?>">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="ti-home"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title">Dashboard</span>
                            </div>
                        </div>
                    </a>
                </li>

                <li class="<?php echo (isset($tab_open) && $tab_open == 'drivers') ? 'active open' : '' ?>">
                    <a href="<?php echo $this->Html->url(array('plugin' => false,'controller' => 'drivers','action' => 'index', 'admin' => true)); ?>">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="fa fa-user-secret"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title">Drivers Management</span>
                            </div>
                        </div>
                    </a>
                </li>

                <li class="<?php echo (isset($tab_open) && $tab_open == 'customers') ? 'active open' : '' ?>">
                    <a href="<?php echo $this->Html->url(array('plugin' => false,'controller' => 'customers','action' => 'index', 'admin' => true)); ?>">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="fa fa-user"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title">Customers Management</span>
                            </div>
                        </div>
                    </a>
                </li>

                <li class="<?php echo (isset($tab_open) && $tab_open == 'categories') ? 'active open' : '' ?>">
                    <a href="<?php echo $this->Html->url(array('plugin' => false,'controller' => 'categories','action' => 'index', 'admin' => true)); ?>">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="fa fa-list"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title">Category Management</span>
                            </div>
                        </div>
                    </a>
                </li>

                <li class="<?php echo (isset($tab_open) && $tab_open == 'vehicle_types') ? 'active open' : '' ?>">
                    <a href="<?php echo $this->Html->url(array('plugin' => false,'controller' => 'vehicle_types','action' => 'index', 'admin' => true)); ?>">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="fa fa-ship"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title">Vehicle Types Management</span>
                            </div>
                        </div>
                    </a>
                </li>

                <li class="<?php echo (isset($tab_open) && $tab_open == 'cargo_types') ? 'active open' : '' ?>">
                    <a href="<?php echo $this->Html->url(array('plugin' => false,'controller' => 'cargo_types','action' => 'index', 'admin' => true)); ?>">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="fa fa-truck"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title">Cargo Types Management</span>
                            </div>
                        </div>
                    </a>
                </li>

                <li class="slide_class <?php echo (isset($tab_open) && in_array($tab_open, array('vehicles', 'vehiclesapproved', 'vehiclespending'))) ? 'active open' : '' ?>">
                    <a href="javascript:void(0)">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="fa fa-truck"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title">Vehicle Management</span><i class="icon-arrow"></i>
                            </div>
                        </div>
                    </a>
                    <ul class="sub-menu" style="<?php echo (isset($tab_open) && in_array($tab_open, array('vehicles', 'vehiclesapproved', 'vehiclespending'))) ? '' : 'display: none;' ?>">
                        <li class="<?php echo (isset($tab_open) && $tab_open == 'vehicles') ? 'active' : '' ?>">
                            <a href="<?php echo $this->Html->url(array('plugin' => false,'controller' => 'vehicles','action' => 'index', 'admin' => true)); ?>">
                                <span class="title">All Vehicles</span>
                            </a>

                        </li>
                        <li class="<?php echo (isset($tab_open) && $tab_open == 'vehiclesapproved') ? 'active' : '' ?>">
                            <a href="<?php echo $this->Html->url(array('plugin' => false,'controller' => 'vehicles', 'action' => 'index', 'type' => 'approved', 'admin' => true)); ?>">
                                <span class="title">Approved Vehicles</span>
                            </a>
                        </li>
                        <?php /*<li class="<?php echo (isset($tab_open) && $tab_open == 'vehiclespending') ? 'active' : '' ?>">
                            <a href="<?php echo $this->Html->url(array('plugin' => false,'controller' => 'vehicles', 'action' => 'index', 'type' => 'pending', 'admin' => true)); ?>">
                                <span class="title">Approval Pending Vehicles</span>
                            </a>
                        </li>*/ ?>

                    </ul>
                </li>


                <li class="slide_class <?php echo (isset($tab_open) && in_array($tab_open, array('countries', 'states', 'cities'))) ? 'active open' : '' ?>">
                    <a href="javascript:void(0)">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="fa fa-globe"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title">Location Management</span><i class="icon-arrow"></i>
                            </div>
                        </div>
                    </a>
                    <ul class="sub-menu" style="<?php echo (isset($tab_open) && in_array($tab_open, array('countries', 'states', 'cities'))) ? '' : 'display: none;' ?>">
                        <li class="<?php echo (isset($tab_open) && $tab_open == 'countries') ? 'active' : '' ?>">
                            <a href="<?php echo $this->Html->url(array('plugin' => false,'controller' => 'countries','action' => 'index', 'admin' => true)); ?>">
                                <span class="title">Countries</span>
                            </a>

                        </li>
                        <li class="<?php echo (isset($tab_open) && $tab_open == 'states') ? 'active' : '' ?>">
                            <a href="<?php echo $this->Html->url(array('plugin' => false,'controller' => 'states','action' => 'index', 'admin' => true)); ?>">
                                <span class="title">States</span>
                            </a>
                        </li>
                        <li class="<?php echo (isset($tab_open) && $tab_open == 'cities') ? 'active' : '' ?>">
                            <a href="<?php echo $this->Html->url(array('plugin' => false,'controller' => 'cities','action' => 'index', 'admin' => true)); ?>">
                                <span class="title">Cities</span>
                            </a>
                        </li>

                    </ul>
                </li>

                <li class="<?php echo (isset($tab_open) && $tab_open == 'makes') ? 'active open' : '' ?>">
                    <a href="<?php echo $this->Html->url(array('plugin' => false,'controller' => 'makes','action' => 'index', 'admin' => true)); ?>">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="fa fa-list"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title">Vehicle Make Management</span>
                            </div>
                        </div>
                    </a>
                </li>

                <li class="<?php echo (isset($tab_open) && $tab_open == 'models') ? 'active open' : '' ?>">
                    <a href="<?php echo $this->Html->url(array('plugin' => false,'controller' => 'models','action' => 'index', 'admin' => true)); ?>">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="fa fa-list"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title">Vehicle Model Management</span>
                            </div>
                        </div>
                    </a>
                </li>

                <li class="<?php echo (isset($tab_open) && $tab_open == 'global_settings') ? 'active open' : '' ?>">
                    <a href="<?php echo $this->Html->url(array('plugin' => false,'controller' => 'admins','action' => 'global_settings', 'admin' => true)); ?>">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="fa fa-gears"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title">Global Settings</span>
                            </div>
                        </div>
                    </a>
                </li>

                <?php /*<li class="slide_class <?php echo (isset($tab_open) && in_array($tab_open, array('faqs', 'pages'))) ? 'active open' : '' ?>">
                    <a href="javascript:void(0)">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="fa fa-newspaper-o"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title">Pages Management</span><i class="icon-arrow"></i>
                            </div>
                        </div>
                    </a>
                    <ul class="sub-menu" style="<?php echo (isset($tab_open) && in_array($tab_open, array('faqs', 'pages'))) ? '' : 'display: none;' ?>">
                        <li class="<?php echo (isset($tab_open) && $tab_open == 'faqs') ? 'active' : '' ?>">
                            <a href="<?php echo $this->Html->url(array('plugin' => false,'controller' => 'faqs','action' => 'index', 'admin' => true)); ?>">
                                <span class="title">FAQ</span>
                            </a>

                        </li>
                        <li class="<?php echo (isset($tab_open) && $tab_open == 'pages') ? 'active' : '' ?>">
                            <a href="<?php echo $this->Html->url(array('plugin' => false,'controller' => 'pages','action' => 'index', 'admin' => true)); ?>">
                                <span class="title">Static Pages</span>
                            </a>
                        </li>

                    </ul>
                </li>*/ ?>

            </ul>
        </nav>
    </div>
</div>