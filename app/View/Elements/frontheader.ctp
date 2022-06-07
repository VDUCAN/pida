        <header>
            <div class="header-top">

			    <?php 
				
				if($this->Session->read('User')){?>
				
				<a href="<?php echo SITEURL;?>users/logout"><i class="fa fa-sign-out"></i> Logout</a>
                
				<?php } else { ?>
				<a href="#_"  data-modal-id="popup3"><i class="fa fa-sign-in"></i> Sign In</a>
				<?php  } ?>
				
            </div>
            <div class="header-main">
                <div class="container">
                    <div class="logo"><a href="<?php echo SITEURL;?>"><?php echo  $this->Html->image('front/logo.png',array('border' => 0,'alt' => 'Pida','class' => 'head-logo')) ?></a></div>
                    <span class="menu" ><i class="fa fa-navicon"></i></span>
					
					<?php 
				
				if($this->Session->read('User')){?>
					<div class="user-profile">
					<?php if($this->Session->read('User.photo') != '') { ?>
                    <img src="<?php echo $this->webroot.'uploads/user_photos/thumbnail/'.$this->Session->read('User.photo') ?>" alt="" >
				<?php } else { ?>
					<img src="<?php echo $this->webroot.'images/noprofile.gif' ?>" alt="" >
					
				<?php } ?>
					
					<a href="<?php echo SITEURL;?>home/dashboard"> <?php echo $this->Session->read('User.first_name') ." ".$this->Session->read('User.last_name') ?></a></div>
					<?php  } ?>
					
                </div>
            </div>

        </header>