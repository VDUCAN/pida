<?php
/**
 *
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
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
$cakeDescription = __d('cake_dev', 'Welcome to Tueeter');
?>
<!DOCTYPE html>
<html>
    <head>
        <?php echo $this->Html->charset(); ?>
        <title>
          Pida Administrator panel  -:
            <?php echo $title_for_layout; ?>
        </title>
        <?php echo $this->Html->meta('favicon.ico', 'images/favicon.ico', array('type' => 'icon')); ?>
        <?php
	
	//   echo $this->Html->css(array('bootstrap.min.css','font-awesome.min.css','themify-icons.min.css','animate.min.css','styles','themes/theme-1.css'));
            echo $this->Html->script(array('jquery.min.js', 'bootstrap.min.js','perfect-scrollbar.min.js','main.js'));
	
	
        echo $this->Html->css(array('bootstrap.min.css', 'font-awesome.min.css', 'themify-icons.min.css', 'animate.min.css', 'perfect-scrollbar.min.css', 'switchery.min.css', 'styles', 'plugins', 'themes/theme-1.css'));
    //    echo $this->Html->script(array('jquery.min.js', 'bootstrap.min.js', 'modernizr.js', 'jquery.cookie.js', 'perfect-scrollbar.min.js', 'switchery.min.js', 'jquery.sparkline.min.js', 'main.js'));
	
	
      echo $this->Html->scriptBlock("
		jQuery(document).ready(function () {
                                            Main.init();
                                    });", array('inline' => false));
    
//        $css = array('styles');
//        echo $this->Html->css($css);

        echo $this->fetch('meta');
        echo $this->fetch('css');
        echo $this->fetch('script');
        ?>
    </head>
    <body>

        <div id="app">
            <!-- sidebar -->
            <?php echo $this->element('sidebar'); ?>
            <!-- / sidebar -->
            <div class="app-content">
                <!-- start: TOP NAVBAR -->
                <?php echo $this->element('header'); ?>
                <!-- end: TOP NAVBAR -->
                <div class="main-content" >
                    <div class="wrap-content container" id="container">
                        <?php echo $this->fetch('content'); ?>
                    </div>
                </div>
            </div>
            <!-- start: FOOTER -->
            <?php echo $this->element('footer'); ?>
            <!-- end: FOOTER -->
        </div>
        
		<?php  //echo $this->element('sql_dump');  ?>
    </body>
</html>
