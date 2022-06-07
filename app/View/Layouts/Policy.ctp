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
          Courierrr -:
            <?php echo $title_for_layout; ?>
        </title>
        <?php echo $this->Html->meta('favicon.ico', 'images/favicon.ico', array('type' => 'icon')); ?>
        <?php
        echo $this->Html->css(array('bootstrap.min.css', 'font-awesome.min.css', 'themify-icons.min.css', 'animate.min.css', 'perfect-scrollbar.min.css', 'switchery.min.css', 'styles', 'plugins', 'themes/theme-1.css'));
        echo $this->Html->script(array('jquery.min.js', 'bootstrap.min.js', 'modernizr.js', 'jquery.cookie.js', 'perfect-scrollbar.min.js', 'switchery.min.js', 'jquery.sparkline.min.js', 'main.js', 'login'));
        echo $this->Html->scriptBlock("
		jQuery(document).ready(function () {
			Login.init();
            Main.init();
        });
		", array('inline' => false));
        ?>
        <?php
        $css = array('styles');
        echo $this->Html->css($css);

        $js = array('jquery.min', 'tytabs.jquery.min', 'html5');
        echo $this->Html->script($js);

        echo $this->fetch('meta');
        echo $this->fetch('css');
        echo $this->fetch('script');
        ?>
    </head>
    <body class="login">
           <body>


			<?php
	      //  echo ((isset($loggedinUserDetails) && !empty($loggedinUserDetails)) ? $this->element('header_after_login') : $this->element('header'));
		echo $this->fetch('content');

	       // if ($this->params['action'] != 'rider_login') {
		//    echo $this->element('footer');
	       // }

		// echo $this->element('sql_dump'); 
		?>
	
    </body>
    </body>
</html>
