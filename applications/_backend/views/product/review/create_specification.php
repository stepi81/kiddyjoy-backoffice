<div id="content">
    <ul>
        <li>
            <h2><?= $grid_title ?></h2>
            <a href="#" class="collapse">Collapse</a>
            <div class="innerContent">
            
                <fieldset class="sectionForm half"> 
                    <div class="innerContent">
		                <?= $grid ?>
		                <table id="grid" style="display:none"></table>
		            </div>
	            </fieldset>
	            
	             <fieldset class="sectionForm half" style="padding-left: 30px; padding-top: 20px;">
	                 <form id="sprecificationsForm" action="<?= site_url( 'reviews/save_specification/'.$this->uri->segment(3) ) ?>"  method="post">
	                         <ul>
	                           <li style="padding-top: 0px; width:200px;">
	                                <label><b>Naziv specifikacije:</b></label> 
	                                <span class="inputField wide"><input type="text" name="name" class="required"></span>
	                            </li>
	                           <li style="padding-top: 0px; width:200px;">
	                                <label>Pozicija:</label>
	                                <span class="inputField short"><input type="text" name="position" value="" class="only_numbers" /></span>
	                           </li>  
	                            <li>
	                                <div>
	                                    <!--<?php if( $this->session->userdata('review_specifications_back_button') ): ?>
	                                    <span class="button back">
	                                        <input type="button" value="Nazad" onclick="<?= $this->session->userdata('review_specifications_back_button') ?>" />
	                                    </span>
	                                    <?php endif; ?>-->
	                                    <span class="button save">
	                                        <input type="button" value="Sačuvaj" onclick="validateForm('sprecificationsForm')" id="saveRsponse" /> 
	                                    </span>
	                                </div>
	                            </li>
	                            <li>
								<?php if( $message ) echo $message; ?>
								</li>
	                        </ul>
	                    </form>
				</fieldset>
				
            </div>
        </li>
    </ul>
</div>
                            