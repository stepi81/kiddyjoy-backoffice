<script type="text/javascript">
   
    $(document).ready(function(){
        
       /* Select product category and product id check based on ID and Category entry */
 
     
        $('.selected_category').text($('#product_category option:selected').text());
        $('.selected_collection').text($('#collection option:selected').text());
        $('.selected_subcollection').text($('#subcollection option:selected').text());


        /*Display Subcategories item based on a selected Category*/
        $('#product_category').change(function(){
            $('.selected_group').text('Sellect'); //Removes selected option and displays default option
            //$('.selected_subcategory').text('Sellect');
            $('#group').empty(); //Removes any existing options
            //$('#group').addClass('required');
            var category = $('#product_category option:selected').val(); //Get's value of the selected option
            $.post('<?= site_url('products/get_groups')?>',{category_selection:category}, function(data){
               if(data){
                   $('#group').append(data);//Inserts newely created list
               }
            });
        });
        
        $('#group').change(function(){
            
            $('#subcategory').empty(); //Removes any existing options
            var group = $('#group option:selected').val(); //Get's value of the selected option
            $.post('<?= site_url('products/get_subcategories')?>',{group_selection:group}, function(data){
              
               if(data != '<option value="">Sellect</option>'){
                   $('.selected_subcategory').text('Sellect'); //Removes selected option and displays default option
                   $('#subcategory').append(data);//Inserts newely created list
                   
               } else {
                   $('.selected_subcategory').text('Grupa nema podkategorije');
                   //$('#subcategory').removeClass('required')
               }
            });
        });
   });

</script>
<div id="content">
    <ul>
        <li>
            <h2>Detalji pozadine</h2>
            <a href="#" class="collapse">Collapse</a>

            <div class="innerContent">

                <form id="backgroundForm" method="post" action="<?= site_url( 'settings/backgrounds/edit/'.$background->getID() ) ?>" enctype="multipart/form-data">
                    <fieldset class="sectionForm">

                        <ul>
                            <li>
                                <label>Status:</label>
                                <input type="radio" name="status" value="1"<?= $background->getStatus() ? ' checked="checked"' : '' ?> />
                                <small>Aktivna</small>
                                <input type="radio" name="status" value="0"<?= $background->getStatus() ? '' : ' checked="checked"' ?> />
                                <small>Neaktivna</small>
                            </li>
                            <li>
                                <label>Naziv:</label>
                                <span class="inputField wide"><input type="text" name="background_name" class="required" value="<?= $background->getName() ?>" /></span>
                            </li>
                            <?php if( $background->getObjectClass() ): ?>
                            <li>
                                <label class="alignLeft">Kategorija:</label>
                                    <div class="customComboHolder">
                                        <div>
                                        	<?php if( isset($object_category) ): ?>
                                            <?php is_object($object_category)? $cat = $object_category->getName():''; echo isset($cat)? $cat : 'Odaberite kategoriju' ?>
                                            <?php else: ?>
                                            	Odaberite kategoriju	
                                            <?php endif; ?>
                                        </div>
                                        <select name="product_category" id="product_category">
                                            <option value="" <?php echo isset($cat) ? "" : 'selected'; ?> ><?= 'Odaberite kategoriju' ?></option>
                                            <?php foreach( $categories as $category): ?>
                                            <option value="<?= $category->getID() ?>" <?php
                                            if( isset($category_id) ) {
                                                if ($category_id == $category -> getID()) {
                                                    echo 'selected';
                                                }
                                            }
                                                ?> ><?= $category -> getName() ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </li>
                                <li>
                                        <label class="alignLeft">Grupa:</label>
                                        <div class="customComboHolder">
                                            <div class="selected_group">
                                            	<?php if(isset($object_subcategory)) {
                                                	if($object_subcategory->getParent()== NULL) {
                                                            echo $object_subcategory->getName(); 
                                                        } else {
                                                            foreach( $subcategories as $subcategory) {
                                                                if ($subcategory->getID() == $object_subcategory->getParent()->getID()){
                                                                    echo $subcategory->getName(); 
                                                                }
                                                            }
                                                        }
													} 
												?>
                                            </div>
                                            <select name="group" id="group" >
                                            	
                                                <?php if( isset($object_subcategory) ): ?>
                                                	<?php foreach( $subcategories as $subcategory): 
                                                    	if ($subcategory->getParent() == NULL && $subcategory->getCategory()->getID() == $object_subcategory->getCategory()->getID() ){ ?>
                                                    	<option value="<?= $subcategory->getID() ?>" 
                                                        <?php if ($object_subcategory->getParent()== NULL) {
                                                                  if ($object_subcategory->getID() == $subcategory->getID()) {
                                                                      echo 'selected';
                                                                  }
                                                              } else {
                                                                  if ($subcategory->getID() == $object_subcategory->getParent()->getID()){
                                                                    echo 'selected';
                                                                  } 
                                                              }
                                                              
                                                              ?> >
                                                        <?= $subcategory->getName() ?></option>
                                                	<?php } endforeach; ?>
                                                <?php else: ?>
                                                	<option value="" selected>Odaberite grupu</option>
                                                	<?php if( isset($object_subcategory) ): ?>
                                                	<?php foreach( $subcategories as $subcategory): 
                                                    	if ($subcategory->getParent() == NULL && $subcategory->getCategory()->getID() == $object_category->getID() ){ ?>
                                                    	<option value="<?= $subcategory->getID() ?>" >
                                                        	<?= $subcategory->getName() ?>
                                                        </option>
                                                	<?php } endforeach; ?>	
                                                	<?php endif; ?>
                                                <?php endif; ?>
                                            </select>
                                        </div>
                                    </li> 
                                    <li>
                                        <label class="alignLeft">Podkategorija:</label>
                                        <div class="customComboHolder">
                                            <div class="selected_subcategory">
                                            	<?php if( isset($object_subcategory) ): ?>
                                                <?php 
                                                	if ($object_subcategory->getParent() != NULL ) {
                                                           foreach( $subcategories as $subcategory ){  
                                                               if ($object_subcategory->getID() == $subcategory->getID()){
                                                                   echo $subcategory->getName(); 
                                                               }
                                                           }    
                                                   } else {
                                                   	echo "Sellect" ;    
                                                   }
                                                ?>
                                                <?php endif; ?>
                                            </div>
                                            <select name="subcategory" id="subcategory" >
                                            	<?php if( isset( $object_subcategory ) ): ?>
	                                                <?= is_object($object_subcategory->getParent()) ? "":'<option value="" selected>Sellect</option>' ?>
	                                                <?php if (is_object($object_subcategory->getParent())){?>    
	                                                <?php foreach( $subcategories as $subcategory ):
	                                                    if( $object_subcategory->getParent() == $subcategory->getParent()){  ?>
	                                                    <option value="<?= $subcategory->getID() ?>" 
	                                                    <?php if ($object_subcategory->getParent() != NULL) {
	                                                              if ($object_subcategory->getID() == $subcategory->getID()){
	                                                                 echo 'selected';
	                                                              }
	                                                          }?> ><?= $subcategory->getName() ?></option>
	                                                <?php } endforeach; } ?>
                                                <?php else: ?>
                                                	<option value="" selected>Prvo odaberite grupu</option>
                                                <?php endif;?>	
                                            
                                            </select>
                                        </div>
                                    </li> 
                            <?php else: ?>
                            	
                            	<li>
	                                <label class="alignLeft">Kategorija:</label>
	                                <div class="customComboHolder">
	                                    <div class="selected_category">Odaberite kategoriju</div>
	                                    <select name="product_category" id="product_category">
	                                        <option value="" selected>Odaberite kategoriju</option>
	                                    <?php foreach( $categories as $category) {?>
	                                        <option value="<?= $category->getID() ?>" ><?= $category->getName() ?></option>
	                                    <?php } ?>
	                                    </select>
	                                </div>
	                             </li>
	                             <li>
	                                <label class="alignLeft">Grupa:</label>
	                                <div class="customComboHolder">
	                                    <div class="selected_group">Odaberite grupu</div>
	                                    <select name="group" id="group">
	                                        <option value="" selected>Prvo odaberite kategoriju</option>
	                                    </select>
	                                </div>
	                             </li>
	                             <li>
	                                <label class="alignLeft">Podkategorija:</label>
	                                <div class="customComboHolder">
	                                    <div class="selected_subcategory">Odaberite podkategoriju</div>
	                                    <select name="subcategory" id="subcategory" >
	                                        <option value="" selected>Prvo odaberite grupu</option>
	                                    </select>
	                                </div>
	                             </li>
                            	
                            <?php endif; ?>        
                                    
                            <?php foreach( $background->getImages() as $image ): ?>        
                            <li>
                                <label>&nbsp;</label>
                                <span class="image"><img src="<?= $image->getImageURL() ?>" alt="" height="150" /></span>
                            </li>    
                            <?php endforeach; ?>         
                            <li>
                                <label>Cela Slika:</label>
                                <span class="inputField wide"><input type="text" name="image_name" disabled="true" /></span>
                                <div class="uploadFile">
                                    <input type="file" name="background_image" />
                                </div>
                            </li>
                            <li>
                                <label>URL:</label>
                                <span class="inputField wide"><input type="text" name="background_url" value="<?= $background_url ?>" /></span>
                            </li>
                            <li>
                                <label>Leva Slika:</label>
                                <span class="inputField wide"><input type="text" name="image_name" disabled="true" /></span>
                                <div class="uploadFile">
                                    <input type="file" name="left_background_image" />
                                </div>
                            </li>
                            <li>
                                <label>Levi URL:</label>
                                <span class="inputField wide"><input type="text" name="left_url" value="<?= $left_url ?>" /></span>
                            </li>
                            <li>
                                <label>Desna Slika:</label>
                                <span class="inputField wide"><input type="text" name="image_name" disabled="true" /></span>
                                <div class="uploadFile">
                                    <input type="file" name="right_background_image" />
                                </div>
                            </li>
                            <li>
                                <label>Desni URL:</label>
                                <span class="inputField wide"><input type="text" name="right_url" value="<?= $right_url ?>" /></span>
                            </li>
                        </ul>

                    </fieldset>

                    <div class="borderTop">

                        <span class="button back">
                            <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('productsGrid', 'settings/backgrounds/listing') ?>" />
                        </span> 
                        <span class="button save">
                            <input type="button" value="Izmeni" onclick="validateForm('backgroundForm')" /> 
                        </span>
                        <?php if( isset($message) ) echo $message; ?>

                    </div>
                </form>

            </div>
        </li>
    </ul>
</div>
