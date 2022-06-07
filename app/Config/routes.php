<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */
	Router::connect('/', array('controller' => 'home', 'action' => 'index'));
	Router::connect('/about-us', array('controller' => 'home', 'action' => 'page','id'=>1));
        Router::connect('/privacy-policy', array('controller' => 'home', 'action' => 'page','id'=>2));
	Router::connect('/service', array('controller' => 'home', 'action' => 'page','id'=>13));
	Router::connect('/term-conditions', array('controller' => 'home', 'action' => 'page','id'=>3));
	Router::connect('/help', array('controller' => 'home', 'action' => 'page','id'=>14));
        Router::connect('/contact-us', array('controller' => 'home', 'action' => 'contact_us'));
/**
 * ...and connect the rest of 'Pages' controller's URLs.
 */
	//Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));
    Router::connect('/admin', array('controller' => 'admins', 'action' => 'login', 'admin' => true));
    Router::connect('/admin/dashboard', array('controller' => 'admins', 'action' => 'dashboard', 'admin' => true));
    Router::connect('/admin/logout', array('controller' => 'admins', 'action' => 'logout', 'admin' => true));
    Router::connect('/admin/settings', array('controller' => 'admins', 'action' => 'global_settings', 'admin' => true));

    Router::connect('/admin/customers/add', array('controller' => 'customers', 'action' => 'add_edit', 'admin' => true));
    Router::connect('/admin/customers/edit/:id', array('controller' => 'customers', 'action' => 'add_edit', 'admin' => true),
        array(
            'pass' => array('id')
        )
    );

    Router::connect('/admin/drivers/add', array('controller' => 'drivers', 'action' => 'add_edit', 'admin' => true));
    Router::connect('/admin/drivers/edit/:id', array('controller' => 'drivers', 'action' => 'add_edit', 'admin' => true),
        array(
            'pass' => array('id')
        )
    );
    // Router::connect('admin/drivers/change_approval/:id', array('controller' => 'drivers', 'action' => 'change_approval', 'admin' => true),
    //     array(
    //         'pass' => array('id')
    //     )
    // );
/*    Router::connect('/admin/drivers/view/:id', array('controller' => 'drivers', 'action' => 'view', 'admin' => true),
        array(
            'pass' => array('id')
        )
    ); */

    Router::connect('/admin/:type/change_password/:id', array('controller' => 'users', 'action' => 'change_password', 'admin' => true),
        array(
            'pass' => array('type', 'id')
        )
    );

    Router::connect('/admin/categories/add', array('controller' => 'categories', 'action' => 'add_edit', 'admin' => true));
    Router::connect('/admin/categories/edit/:id', array('controller' => 'categories', 'action' => 'add_edit', 'admin' => true),
        array(
            'pass' => array('id')
        )
    );

    Router::connect('/admin/vehicle_types/add', array('controller' => 'vehicle_types', 'action' => 'add_edit', 'admin' => true));
    Router::connect('/admin/vehicle_types/edit/:id', array('controller' => 'vehicle_types', 'action' => 'add_edit', 'admin' => true),
        array(
            'pass' => array('id')
        )
    );

    Router::connect('/admin/cargo_types/add', array('controller' => 'cargo_types', 'action' => 'add_edit', 'admin' => true));
    Router::connect('/admin/cargo_types/edit/:id', array('controller' => 'cargo_types', 'action' => 'add_edit', 'admin' => true),
        array(
            'pass' => array('id')
        )
    );

    Router::connect('/admin/delivery_types/add', array('controller' => 'delivery_types', 'action' => 'add_edit', 'admin' => true));
    Router::connect('/admin/delivery_types/edit/:id', array('controller' => 'delivery_types', 'action' => 'add_edit', 'admin' => true),
        array(
            'pass' => array('id')
        )
    );

    Router::connect('/admin/feedback_types/add', array('controller' => 'feedback_types', 'action' => 'add_edit', 'admin' => true));
    Router::connect('/admin/feedback_types/edit/:id', array('controller' => 'feedback_types', 'action' => 'add_edit', 'admin' => true),
        array(
            'pass' => array('id')
        )
    );

    Router::connect('/admin/countries/add', array('controller' => 'countries', 'action' => 'add_edit', 'admin' => true));
    Router::connect('/admin/countries/edit/:id', array('controller' => 'countries', 'action' => 'add_edit', 'admin' => true),
        array(
            'pass' => array('id')
        )
    );

    Router::connect('/admin/states/add', array('controller' => 'states', 'action' => 'add_edit', 'admin' => true));
    Router::connect('/admin/states/edit/:id', array('controller' => 'states', 'action' => 'add_edit', 'admin' => true),
        array(
            'pass' => array('id')
        )
    );

    Router::connect('/admin/cities/add', array('controller' => 'cities', 'action' => 'add_edit', 'admin' => true));
    Router::connect('/admin/cities/edit/:id', array('controller' => 'cities', 'action' => 'add_edit', 'admin' => true),
        array(
            'pass' => array('id')
        )
    );

    Router::connect('/admin/makes/add', array('controller' => 'makes', 'action' => 'add_edit', 'admin' => true));
    Router::connect('/admin/makes/edit/:id', array('controller' => 'makes', 'action' => 'add_edit', 'admin' => true),
        array(
            'pass' => array('id')
        )
    );

    Router::connect('/admin/models/add', array('controller' => 'models', 'action' => 'add_edit', 'admin' => true));
    Router::connect('/admin/models/edit/:id', array('controller' => 'models', 'action' => 'add_edit', 'admin' => true),
        array(
            'pass' => array('id')
        )
    );

    Router::connect('/admin/vehicles/add', array('controller' => 'vehicles', 'action' => 'add_edit', 'admin' => true));
    Router::connect('/admin/vehicles/edit/:id', array('controller' => 'vehicles', 'action' => 'add_edit', 'admin' => true),
        array(
            'pass' => array('id')
        )
    );
    
    Router::connect('/admin/faqs/add', array('controller' => 'faqs', 'action' => 'add_edit', 'admin' => true));
    Router::connect('/admin/faqs/edit/:id', array('controller' => 'faqs', 'action' => 'add_edit', 'admin' => true),
        array(
            'pass' => array('id')
        )
    );
    
    Router::connect('/admin/pages/add', array('controller' => 'pages', 'action' => 'add_edit', 'admin' => true));
    Router::connect('/admin/pages/edit/:id', array('controller' => 'pages', 'action' => 'add_edit', 'admin' => true),
        array(
            'pass' => array('id')
        )
    );

    Router::connect('/admin/vehicles', array('controller' => 'vehicles', 'action' => 'index', 'admin' => true));
    Router::connect('/admin/vehicles/:type', array('controller' => 'vehicles', 'action' => 'index', 'admin' => true),
        array(
            'pass' => array('type')
        )
    );

/**
 * Load all plugin routes. See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
	CakePlugin::routes();

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
	require CAKE . 'Config' . DS . 'routes.php';
