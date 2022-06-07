<!--<span>
                                    <ul>
										<?php echo $this->element('pagination'); ?>
                                        <li class="active"><a href="#_">1</a></li>
                                        <li><a href="#_">2</a></li>
                                        <li><a href="#_">3</a></li>
                                        <li><a href="#_">4</a></li>
                                        <li><a href="#_"> ...</a></li>
                                        <li><a href="#_">29</a></li>
                                        <li><a href="#_">30</a></li>
                                    </ul>
                                </span>-->

                                
						
								<span>
								<ul>
    <?php
    
    echo $this->Paginator->numbers(array('tag'=>'li','currentClass'=>'active','separator' => null));
    
    ?>
	</ul>
</span>

<b><?php echo $this->Paginator->counter('Page {:page} of {:pages}'); ?></b>
    
