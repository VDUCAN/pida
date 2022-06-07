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
                    <a href="<?php echo $this->Html->url(array('plugin' => false, 'controller' => 'admins', 'action' => 'dashboard', 'admin' => true)); ?>">
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

                <li class="slide_class <?php echo (isset($tab_open) && in_array($tab_open, array('faqs', 'pages'))) ? 'active open' : '' ?>">
			<a href="javascript:void(0)">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="fa fa-newspaper-o"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title">Content Management</span><i class="icon-arrow"></i>
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
                </li>
		
		<li class="slide_class <?php echo (isset($tab_open) && in_array($tab_open, array('categories', 'vehicle_types','cargo_types','delivery_types','vehicle_types','feedback_types','makes','models'))) ? 'active open' : '' ?>">
			<a href="javascript:void(0)">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="fa fa-list"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title">Dropdown Management</span><i class="icon-arrow"></i>
                            </div>
                        </div>
                    </a>
		     <ul class="sub-menu" style="<?php echo (isset($tab_open) && in_array($tab_open, array('categories', 'vehicle_types','cargo_types','delivery_types','vehicle_types','feedback_types','makes','models'))) ? '' : 'display: none;' ?>">
                        <li class="<?php echo (isset($tab_open) && $tab_open == 'categories') ? 'active' : '' ?>">
                            <a href="<?php echo $this->Html->url(array('plugin' => false,'controller' => 'categories','action' => 'index', 'admin' => true)); ?>">
                                <span class="title">Category</span>
                            </a>
                        </li>
			
			<?php /* ?>
                        <li class="<?php echo (isset($tab_open) && $tab_open == 'vehicle_types') ? 'active' : '' ?>">
                            <a href="<?php echo $this->Html->url(array('plugin' => false,'controller' => 'vehicle_types','action' => 'index', 'admin' => true)); ?>">
                                <span class="title">Vehicle Types</span>
                            </a>
                        </li>
			<?php */ ?>
			
			<li class="<?php echo (isset($tab_open) && $tab_open == 'cargo_types') ? 'active' : '' ?>">
                            <a href="<?php echo $this->Html->url(array('plugin' => false,'controller' => 'cargo_types','action' => 'index', 'admin' => true)); ?>">
                                <span class="title">Cargo Types</span>
                            </a>
                        </li>
			<li class="<?php echo (isset($tab_open) && $tab_open == 'delivery_types') ? 'active' : '' ?>">
                            <a href="<?php echo $this->Html->url(array('plugin' => false,'controller' => 'delivery_types','action' => 'index', 'admin' => true)); ?>">
                                <span class="title">Delivery Types</span>
                            </a>
                        </li>
			<li class="<?php echo (isset($tab_open) && $tab_open == 'vehicle_types') ? 'active' : '' ?>">
                            <a href="<?php echo $this->Html->url(array('plugin' => false,'controller' => 'vehicle_types','action' => 'index', 'admin' => true)); ?>">
                                <span class="title">Vehicle Types</span>
                            </a>
                        </li>
			<li class="<?php echo (isset($tab_open) && $tab_open == 'feedback_types') ? 'active' : '' ?>">
                            <a href="<?php echo $this->Html->url(array('plugin' => false,'controller' => 'feedback_types','action' => 'index', 'admin' => true)); ?>">
                                <span class="title">Feedback Types</span>
                            </a>
                        </li>
			<li class="<?php echo (isset($tab_open) && $tab_open == 'makes') ? 'active' : '' ?>">
                            <a href="<?php echo $this->Html->url(array('plugin' => false,'controller' => 'makes','action' => 'index', 'admin' => true)); ?>">
                                <span class="title">Vehicle Make</span>
                            </a>
                        </li>
			<li class="<?php echo (isset($tab_open) && $tab_open == 'models') ? 'active' : '' ?>">
                            <a href="<?php echo $this->Html->url(array('plugin' => false,'controller' => 'models','action' => 'index', 'admin' => true)); ?>">
                                <span class="title">Vehicle Model</span>
                            </a>
                        </li>
                    </ul>
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
                        <li class="<?php echo (isset($tab_open) && $tab_open == 'vehiclespending') ? 'active' : '' ?>">
                            <a href="<?php echo $this->Html->url(array('plugin' => false,'controller' => 'vehicles', 'action' => 'index', 'type' => 'pending', 'admin' => true)); ?>">
                                <span class="title">Approval Pending Vehicles</span>
                            </a>
                        </li>

                    </ul>
                </li>
		
		
		<li class="slide_class <?php echo (isset($tab_open) && in_array($tab_open, array('payments'))) ? 'active open' : '' ?>">
                    <a href="javascript:void(0)">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="fa fa-money"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title">Payment Management</span><i class="icon-arrow"></i>
                            </div>
                        </div>  
                    </a>
                    <ul class="sub-menu" style="<?php echo (isset($tab_open) && in_array($tab_open, array('payments'))) ? '' : 'display: none;' ?>">
                        <li class="<?php echo (isset($tab_open) && $tab_open == 'payments') ? 'active' : '' ?>">
                            <a href="<?php echo $this->Html->url(array('plugin' => false,'controller' => 'payments','action' => 'index', 'admin' => true)); ?>">
                                <span class="title">Driver Payments</span> 
                            </a>

                        </li> 
			<?php /* ?>
                        <li class="<?php echo (isset($tab_open) && $tab_open == 'invoices') ? 'active' : '' ?>">
                            <a href="<?php echo $this->Html->url(array('plugin' => false,'controller' => 'invoices', 'action' => 'index', 'type' => 'approved', 'admin' => true)); ?>">
                                <span class="title">Invoices</span>
                            </a>
                        </li>
			 <?php */ ?>
                       
                    </ul>
                </li>

		
		
		
		
		
	<!--	<li class="<?php echo (isset($tab_open) && $tab_open == 'payments') ? 'active open' : '' ?>">
                    <a href="<?php echo $this->Html->url(array('plugin' => false, 'controller' => 'payments', 'action' => 'index', 'admin' => true)); ?>">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="fa fa-money"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title">Payment and billing Management</span>
                            </div>
                        </div>
                    </a>
        </li>   -->

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

		<li class="<?php echo (isset($tab_open) && $tab_open == 'global_settings') ? 'active open' : '' ?>">
                    <a href="<?php echo $this->Html->url(array('plugin' => false, 'controller' => 'admins', 'action' => 'global_settings', 'admin' => true)); ?>">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="fa fa-gears"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title">Settings Management</span>
                            </div>
                        </div>
                    </a>
                </li>

		<li class="<?php echo (isset($tab_open) && $tab_open == 'reviews') ? 'active open' : '' ?>">
                    <a href="<?php echo $this->Html->url(array('plugin' => false, 'controller' => 'reviews', 'action' => 'index', 'admin' => true)); ?>">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="fa fa-star"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title">Reviews Management</span>
                            </div>
                        </div>
                    </a>
                </li>
		
		  <li class="slide_class <?php echo (isset($tab_open) && in_array($tab_open, array('drivers', 'customers','drivers_online'))) ? 'active open' : '' ?>">
                    <a href="javascript:void(0)">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="fa fa-user"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title">User/Driver Management</span><i class="icon-arrow"></i>
                            </div>
                        </div>
                    </a>
                    <ul class="sub-menu" style="<?php echo (isset($tab_open) && in_array($tab_open, array('drivers', 'customers','drivers_online'))) ? '' : 'display: none;' ?>">
                        <li class="<?php echo (isset($tab_open) && $tab_open == 'drivers') ? 'active' : '' ?>">
                            <a href="<?php echo $this->Html->url(array('plugin' => false,'controller' => 'drivers','action' => 'index', 'admin' => true)); ?>">
                                <span class="title">Drivers</span>
                            </a>

                        </li>

                        <li class="<?php echo (isset($tab_open) && $tab_open == 'drivers_online') ? 'active' : '' ?>">
                            <a href="<?php echo $this->Html->url(array('plugin' => false,'controller' => 'drivers','action' => 'online', 'admin' => true)); ?>">
                                <span class="title">Online Drivers</span>
                            </a>

                        </li>

                        <li class="<?php echo (isset($tab_open) && $tab_open == 'customers') ? 'active' : '' ?>">
                            <a href="<?php echo $this->Html->url(array('plugin' => false,'controller' => 'customers','action' => 'index', 'admin' => true)); ?>">
                                <span class="title">Customers</span>
                            </a>
                        </li>
                    </ul>
               
               
                </li>
		

 <li class="slide_class <?php echo (isset($tab_open) && in_array($tab_open, array('maps', 'riders'))) ? 'active open' : '' ?>">
                    <a href="javascript:void(0)">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="fa fa-user"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title">Maps Management</span><i class="icon-arrow"></i>
                            </div>
                        </div>
                    </a>
                    <ul class="sub-menu" style="<?php echo (isset($tab_open) && in_array($tab_open, array('maps', 'riders'))) ? '' : 'display: none;' ?>">
                        <li class="<?php echo (isset($tab_open) && $tab_open == 'maps') ? 'active' : '' ?>">
                            <a href="<?php echo $this->Html->url(array('plugin' => false,'controller' => 'maps','action' => 'index', 'admin' => true)); ?>">
                                <span class="title">Drivers</span>
                            </a>

                        </li>
                        <li class="<?php echo (isset($tab_open) && $tab_open == 'riders') ? 'active' : '' ?>">
                            <a href="<?php echo $this->Html->url(array('plugin' => false,'controller' => 'maps','action' => 'rider', 'admin' => true)); ?>">
                                <span class="title">Riders</span>
                            </a>
                        </li>
                    </ul>
               
               
                </li>
		

		<li class="<?php echo (isset($tab_open) && $tab_open == 'bookings') ? 'active open' : '' ?>">
                    <a href="<?php echo $this->Html->url(array('plugin' => false, 'controller' => 'bookings', 'action' => 'index', 'admin' => true)); ?>">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="fa fa-list"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title">Booking Management</span>
                            </div>
                        </div>
                    </a>
                </li>

		<?php /* ?>    
		<li class="<?php echo (isset($tab_open) && $tab_open == 'reports') ? 'active open' : '' ?>">
                    <a href="<?php echo $this->Html->url(array('plugin' => false, 'controller' => 'reports', 'action' => 'index', 'admin' => true)); ?>">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="fa fa-bar-chart"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title">Reports Management</span>
                            </div>
                        </div>
                    </a>
                </li>
		<?php */ ?>
		
		<li class="slide_class <?php echo (isset($tab_open) && in_array($tab_open, array('invoices'))) ? 'active open' : '' ?>">
                    <a href="javascript:void(0)">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="fa fa-money"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title">Reports Management</span><i class="icon-arrow"></i>
                            </div>
                        </div>  
                    </a>
                    <ul class="sub-menu" style="<?php echo (isset($tab_open) && in_array($tab_open, array('invoices'))) ? '' : 'display: none;' ?>">
                        <li class="<?php echo (isset($tab_open) && $tab_open == 'invoices') ? 'active' : '' ?>">
                            <a href="<?php echo $this->Html->url(array('plugin' => false,'controller' => 'invoices', 'action' => 'index', 'admin' => true)); ?>">
                                <span class="title">Invoices</span>
                            </a>
                        </li>
                       
                    </ul>
                </li>


		<li class="<?php echo (isset($tab_open) && $tab_open == 'notifications') ? 'active open' : '' ?>">
                    <a href="<?php echo $this->Html->url(array('plugin' => false, 'controller' => 'notifications', 'action' => 'admin_rider', 'admin' => true)); ?>">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="fa fa-bell"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title">Push Notification Management</span>
                            </div>
                        </div>
                    </a>
					
					<ul class="sub-menu" style="<?php echo (isset($tab_open) && in_array($tab_open, array('notifications'))) ? '' : 'display: none;' ?>">
                        <li class="<?php echo (isset($tab_open) && $tab_open == 'notifications') ? 'active' : '' ?>">
                            <a href="<?php echo $this->Html->url(array('plugin' => false,'controller' => 'notifications', 'action' => 'admin_rider', 'admin' => true)); ?>">
                                <span class="title">Notification To Rider</span>
                            </a>
                        </li>
                       
					   
					    <li class="<?php echo (isset($tab_open) && $tab_open == 'notifications') ? 'active' : '' ?>">
                            <a href="<?php echo $this->Html->url(array('plugin' => false,'controller' => 'notifications', 'action' => 'admin_driver', 'admin' => true)); ?>">
                                <span class="title">Notification To Driver</span>
                            </a>
                        </li>
					   
					   
                    </ul>
					
					
                </li>

		<?php /* ?>
		<li class="<?php echo (isset($tab_open) && $tab_open == 'statistics') ? 'active open' : '' ?>">
                    <a href="<?php echo $this->Html->url(array('plugin' => false, 'controller' => 'statistics', 'action' => 'index', 'admin' => true)); ?>">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="fa fa-line-chart"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title">Statistics Management</span>
                            </div>
                        </div>
                    </a>
                </li>
		<?php */ ?>
		
		<li class="<?php echo (isset($tab_open) && $tab_open == 'features') ? 'active open' : '' ?>">
                    <a href="<?php echo $this->Html->url(array('plugin' => false, 'controller' => 'settings', 'action' => 'index', 'admin' => true)); ?>">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="fa fa-money"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title">Price Feature</span>
                            </div>
                        </div>
                    </a>
                </li>

				<li class="<?php echo (isset($tab_open) && $tab_open == 'inbox') ? 'active open' : '' ?>">
                    <a href="<?php echo $this->Html->url(array('plugin' => false, 'controller' => 'inbox', 'action' => 'index', 'admin' => true)); ?>">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="fa fa-inbox"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title">Inbox</span>
                            </div>
                        </div>
                    </a>
                </li>

                 <li class="<?php echo (isset($tab_open) && $tab_open == 'contact') ? 'active open' : '' ?>">
                    <a href="<?php echo $this->Html->url(array('plugin' => false, 'controller' => 'contact', 'action' => 'index', 'admin' => true)); ?>">
                        <div class="item-content">
                            <div class="item-media">
                                <i class="fa fa-envelope"></i>
                            </div>
                            <div class="item-inner">
                                <span class="title">Contact Request</span>
                            </div>
                        </div>
                    </a>
                </li>

            </ul>
        </nav>
    </div>
</div>

