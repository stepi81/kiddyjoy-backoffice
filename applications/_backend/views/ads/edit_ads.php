<script type="text/javascript">
   
    $(document).ready(function(){
        
       /* Select product category and product id check based on ID and Category entry */
       if($('#link_type').val() == 1){
                $(".link label").text('ID Proizvoda');
                $(".link input").ForceNumericOnly().val('<?= $ad->getAdsLink() ?>').addClass('product_id');
                $("span.save").attr('style','display:none');//Removes save button until product id check returns true
       }
              
        $("#link_type").change(function(event){
            if($(this).val() == 1){
                $(".link label").text('ID Proizvoda');
                $(".link input").ForceNumericOnly().val('').addClass('product_id');
                $("span.save").attr('style','display:none');//Removes save button until product id check returns true
            }else{
                $(".link label").text('Link');
                $(".link input").unbind(event.ForceNumericOnly).val('').removeClass('product_id');
                $("span.save").attr('style','display:block');
                $(".link input").parent().removeClass('false');
            }
        });
 
     
        $('.selected_category').text($('#product_category option:selected').text());
        $('.selected_collection').text($('#collection option:selected').text());
        $('.selected_subcollection').text($('#subcollection option:selected').text());


        /*Display Subcategories item based on a selected Category*/
        $('#product_category').change(function(){
            $('.selected_group').text('Sellect'); //Removes selected option and displays default option
            //$('.selected_subcategory').text('Sellect');
            $('#group').empty(); //Removes any existing options
            $('#group').addClass('required');
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
                   $('#subcategory').removeClass('required')
               }
            });
        });
   });

     
    var flashvars = {};
    var params = {wmode:'transparent'};
    var attributes = {};
    swfobject.embedSWF("<?= $ad->getSource() ?>", "myContent", "985", "119", "9.0.0", false, flashvars, params, attributes);
    

</script>

<div id="content">
    <ul>
        <li>
        	<?php if( $category== 'product') { ?>
        	<h2><?php echo 'Proizvod ' . 'detalji' ?></h2>
        	<?php } else{ ?>
            <h2><?php echo ucfirst($category) . ' ' . 'detalji' ?></h2>
            <?php } ?>
            <a href="#" class="collapse">Collapse</a>

            <div class="innerContent">

                <form method="post" id="adsForm" action="<?= site_url( 'ads/edit/'.$ad->getID() ) ?>" id="adsForm" enctype="multipart/form-data">
                    <fieldset class="sectionForm half">

                        <ul>
                            <?php if ($ad->getSourceType() == 2) { ?>
                            <li>
                                <div id="myContent">
                                  <p>Alternative content</p>
                                </div>
                            </li>
                            <?php } else{ ?>
                            <li>
                                <label>&nbsp;</label>
                                <span class="image"><img src="<?= $ad->getSource() ?>" alt="KiddyJoy Shop ads" /></span>
                            </li>
                            <?php } ?>
                            
                            <?php if( $ad->getCategoryId() == 1 || $ad->getCategoryId() == 2 ): ?>
                            <li>
                                <label>&nbsp;</label>
                                <span class="image"><img src="<?= $ad->getSourceMobile() ?>" alt="KiddyJoy Shop ads mobile" /></span>
                            </li>
                            <?php endif; ?>
                            
                            <li>
                                <input type="hidden" name="route_id" id="route_id" value="<?= $route_id ?>"  />
                            </li>
                            <li>
                                <input type="hidden" name="category" id="route_id" value="<?= $category ?>"  />
                            </li>
                            <li>
                                <input type="hidden" name="old_position" id="old_position" value="<?= $ad->getPosition() ?>"  />
                            </li>
                            <li>
                                <label>Status:</label>
                                <input type="radio" name="status" value="1"<?= $ad->getStatus() ? ' checked="checked"' : '' ?>/>
                                <small>Aktivna</small>
                                <input type="radio" name="status" value="0"<?= $ad->getStatus() ? '' : ' checked="checked"' ?>/>
                                <small>Neaktivna</small>
                            </li>
                            
                            <?php if( $ad->getCategoryId() == 1 ): ?>
                            <!--<li>
                                <label>Status mobile:</label>
                                <input type="radio" name="status_mobile" value="1"<?= $ad->getStatusMobile() ? ' checked="checked"' : '' ?>/>
                                <small>Aktivna</small>
                                <input type="radio" name="status_mobile" value="0"<?= $ad->getStatusMobile() ? '' : ' checked="checked"' ?>/>
                                <small>Neaktivna</small>
                            </li> --> 
                            <?php endif; ?>
                                                                                  
                            <li>
                                <label>Naziv:</label>
                                <span class="inputField wide"><input type="text" name="title" id="title" class="required" value="<?= $ad->getTitle()?>"/></span>
                            </li>
                            
                            <li>
                                <label>Datum početka:</label>
                                <span class="inputField wide"><input type="text" name="start_date" class="date-picker" value="<?= $ad->getFormatedStartDate() ?>"/></span>
                            </li>
                            <li>
                                <label>Datum kraja:</label>
                                <span class="inputField wide"><input type="text" name="end_date" class="date-picker" value="<?= $ad->getFormatedEndDate() ?>"/></span>
                            </li>
                           
                            <?php if( $ad->getCategoryId() == 5 ) { ?>
	                           	<?php if( $this->session->userdata('application_id') ): ?>
	                           	<li>
	                                <label>Meni Kategorija:</label>
	                                <span class="inputField wide"><input type="text" name="menu_id" id="menu_id" class="required" value="<?= $ad->getMenuID()?>"/></span>
	                            </li>
	                            <?php else: ?>
	                            <li>
		                         	<label class="alignLeft">Kategorija:</label>
		                            <div class="customComboHolder">
		                                <div>
		                                    <?php is_object($ad->getCategory())? $cat=$ad->getCategory()->getName():''; echo isset($cat)? $cat : 'Odaberite kategoriju' ?>
		                                </div>
		                                <select name="filter_ad_category" id="category" class="required">
		                                    <option value="" <?php echo isset($cat) ? "" : 'selected'; ?> ><?= 'Odaberite kategoriju' ?></option>
		                                    <?php foreach( $categories as $category): ?>
		                                        <option value="<?= $category -> getID() ?>" 
		                                        <?php if (is_object($ad->getCategory())) {
		                                                if ($ad->getCategory()->getID() == $category -> getID()) {
		                                                    echo 'selected';
		                                                }
		                                            }
		                                        ?> > <?= $category -> getName() ?>
		                                        </option>
		                                    <?php endforeach; ?>
		                                </select> 
		                            </div>
		                        </li>
		                        <?php endif; ?>
                           	<?php } ?>
                           
                            <li>
                                <label class="alignLeft">Tip linka:</label>
                                <div class="customComboHolder">
                                    <div><?php if( $ad->getLinkType() ){ echo $ad->getLinkType()->getName(); } else { echo 'Nema Link'; }  ?></div>
                                    <select name="link_type" id="link_type" >
                                        <option value="" >Nema link</option> 
                                        <?php foreach( $link_types as $link_type ): ?>
                                            <option value="<?= $link_type->getID() ?>" <?php if ( $ad->getLinkType() ){if ($ad->getLinkType()->getID() == $link_type->getID()){echo 'selected';}}?> ><?= $link_type->getName() ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </li>
                            <li>
                                <label class="alignLeft">Tip:</label>
                                <div class="customComboHolder">
                                  <div><?= $ad->getSourceType() == 1 ? 'Slika' : ($ad->getSourceType() == 2 ? 'SWF' : 'Tekst') ?></div>
                                    <select name="source_type" id="source_type" >
                                        <option value="1" <?= $ad->getSourceType()==1 ? 'selected':''?>>Slika</option>
                                   <?php if ($ad->getSourceType() == 2) {$sel='selected';} echo $route_id == 2 ? '<option value="2"' . $sel . '>SWF</option>' : '' ?>
                                   	<?php if ($ad->getSourceType() == 3) {$sel='selected';} echo $route_id == 8 ? '<option value="3"' . $sel . '>Tekst</option>' : '' ?> 
                                    </select>
                                </div>
                            </li>
                            <li class="link">
                                <label>Link</label>
                                <span class="inputField wide"><input type="text" name="link" id="link" onBlur="check_id(this)" value="<?= $ad->getAdsLink()?>"/></span>
                            </li>
                            <?php if( $ad->getLinkType() && $ad->getLinkType()->getID() == 1 ) { ?>
                            <li>
                                <label>Cena proizvoda:</label>
                                <span class="inputField wide"><input type="text" name="product_price" id="product_price" readonly="readonly" value="<?= number_format( $product_price, 2, ',', '.' ) ?>"/></span>
                            </li>
                            <?php } ?>   
                            <li>
                                <label>Pozicija:</label>
                                <span class="inputField wide"><input type="text" name="position" id="position" value="<?= $ad->getPosition()?>" class="only_numbers"/></span>
                            </li>
                            <li>
                                <label>Slika:</label>
                                <span class="inputField wide"><input type="text" name="image_name" disabled="true" /></span>
                                <div class="uploadFile">
                                    <input type="file" name="image" />
                                </div>
                            </li>   
                            <?php if( $ad->getCategoryId() == 1 || $ad->getCategoryId() == 2 ): ?>
                            	<li>
	                                <label>Slika mobile:</label>
	                                <span class="inputField wide"><input type="text" name="image_mobile_name" disabled="true" /></span>
	                                <div class="uploadFile">
	                                    <input type="file" name="image_mobile" />
	                                </div>
	                            </li>  
                            <?php endif; ?>  
                        </ul>

                    </fieldset>
                    <?php if( $ad->getCategoryId() == 6 ) { ?>
                        <fieldset class="sectionForm half" style="padding-left: 100px;">
                            <ul>
                                <?php if (is_object($ad->getCampaing())){ ?>
                                <li>
                                    <label>Tip:</label>
                                    <input type="radio" name="type" disabled="true" value="1" checked/>
                                    <small>Kampanja</small>
                                    <input type="radio" name="type" disabled="true" value="0" />
                                    <small>Proizvod</small>
                                </li>    
                                <li>
                                <label class="alignLeft">Kategorija:</label>
                                    <div class="customComboHolder">
                                        <div>
                                            <?php is_object($ad->getCampaing()->getCategory())? $cat = $ad->getCampaing()->getCategory()->getName():''; echo isset($cat)? $cat : 'Odaberite kategoriju'
                                            ?>
                                        </div>
                                        <select name="product_category" id="product_category">
                                            <option value="" <?php echo isset($cat) ? "" : 'selected'; ?> ><?= 'Odaberite kategoriju' ?></option>
                                            <?php foreach( $categories as $category):
                                            ?>
                                            <option value="<?= $category -> getID() ?>" <?php
                                            if (is_object($ad->getCampaing()->getCategory())) {
                                                if ($ad->getCampaing()->getCategory()->getID() == $category -> getID()) {
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
                                                <?php if ($ad->getCampaing()->getSubcategory()->getParent()== NULL) {
                                                            echo $ad->getCampaing()->getSubcategory()->getName(); 
                                                        } else {
                                                            foreach( $subcategories as $subcategory) {
                                                                if ($subcategory->getID() == $ad->getCampaing()->getSubcategory()->getParent()->getID()){
                                                                    echo $subcategory->getName(); 
                                                                }
                                                            }
                                                        }
                                                  ?>
                                            </div>
                                            <select name="group" id="group" class="required">
                                                <?php foreach( $subcategories as $subcategory): 
                                                    if ($subcategory->getParent () == NULL && $subcategory->getCategory()->getID() == $ad->getCampaing()->getCategory()->getID() ){ ?>
                                                    <option value="<?= $subcategory->getID() ?>" 
                                                        <?php if ($ad->getCampaing()->getSubcategory()->getParent()== NULL) {
                                                                  if ($ad->getCampaing()->getSubcategory()->getID() == $subcategory->getID()) {
                                                                      echo 'selected';
                                                                  }
                                                              } else {
                                                                  if ($subcategory->getID() == $ad->getCampaing()->getSubcategory()->getParent()->getID()){
                                                                    echo 'selected';
                                                                  } 
                                                              }
                                                              
                                                              ?> >
                                                        <?= $subcategory->getName() ?></option>
                                                <?php } endforeach; ?>
                                            </select>
                                        </div>
                                    </li> 
                                    <li>
                                        <label class="alignLeft">Podkategorija:</label>
                                        <div class="customComboHolder">
                                            <div class="selected_subcategory">
                                                <?php if ($ad->getCampaing()->getSubcategory()->getParent() != NULL ) {
                                                           foreach( $subcategories as $subcategory ){  
                                                               if ($ad->getCampaing()->getSubcategory()->getID() == $subcategory->getID()){
                                                                   echo $subcategory->getName(); 
                                                               }
                                                           }    
                                                       } else {
                                                       echo "Sellect" ;    
                                                       }
                                                       ?></div>
                                            <select name="subcategory" id="subcategory" >
                                                <?= is_object($ad->getCampaing()->getSubcategory()->getParent()) ? "":'<option value="" selected>Sellect</option>' ?>
                                                <?php if (is_object($ad->getCampaing()->getSubcategory()->getParent())){?>    
                                                <?php foreach( $subcategories as $subcategory ):
                                                    if( $ad->getCampaing()->getSubcategory()->getParent() == $subcategory->getParent()){  ?>
                                                    <option value="<?= $subcategory->getID() ?>" 
                                                    <?php if ($ad->getCampaing()->getSubcategory()->getParent() != NULL) {
                                                              if ($ad->getCampaing()->getSubcategory()->getID() == $subcategory->getID()){
                                                                 echo 'selected';
                                                              }
                                                          }?> ><?= $subcategory->getName() ?></option>
                                                <?php } endforeach; } ?>
                                            
                                            </select>
                                        </div>
                                    </li> 
                                    <li><br><li>
                                    <li>
                                        <label class="alignLeft">Brend:</label>
                                        <div class="customComboHolder">
                                            <div>
                                                <?php is_object($ad->getCampaing()->getBrand())? $bra = $ad->getCampaing()->getBrand()->getName():''; echo isset($bra)? $bra : 'Odaberite brend' ?>
                                            </div>
                                            <select name="brand" id="brand">
                                                <option value="" <?php echo isset($bra) ? "" : 'selected'; ?> ><?= 'Odaberite brend' ?></option>
                                                <?php foreach( $brands as $brand): ?>
                                                <option value="<?= $brand -> getID() ?>" <?php
                                                if (is_object($ad->getCampaing()->getBrand())) {
                                                    if ($ad->getCampaing()->getBrand()->getID() == $brand -> getID()) {
                                                        echo 'selected';
                                                    }
                                                }
                                                    ?> ><?= $brand -> getName() ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </li>
                                    
                                    <?php } else { ?>
                                
                                <div class="product_ids_holder">
                                    <li>
                                        <label>Tip:</label>
                                        <input type="radio" name="type" disabled="true" value="1" />
                                        <small>Kampanja</small>
                                        <input type="radio" name="type" disabled="true" value="0" checked/>
                                        <small>Proizvod</small>
                                    </li>
                                    <?php if(!empty($product_ids)):?>
                                        <?php foreach($product_ids as $key=>$val):?>
                                        <li class="element-<?= $key + 1 ?>">
                                            <label>ID Proizvoda:</label>
                                            <span class="inputField short">
                                                <input type="text" id="product_id-<?= $key + 1 ?>" name="product_id[]" class="only_numbers" value="<?=$val?>">
                                            </span>
                                            <?php if($key == count($product_ids) - 1):?>
                                                <img onclick="delete_product(this)" id="delete-<?= $key + 1 ?>" class="delete_product" alt="delete_product" src="<?= layout_url('flexigrid/delete.png')?>">
                                                <img class="add_new add_button-<?= $key + 1 ?>" onclick="add_product(this)" alt="add_new" src="<?= layout_url('flexigrid/add.png')?>">
                                            <?php elseif($key > 0):?>
                                                <img onclick="delete_product(this)" id="delete-<?= $key + 1  ?>" class="delete_product" alt="delete_product" src="<?= layout_url('flexigrid/delete.png')?>">
                                            <?php endif;?>
                                        </li>
                                        <?php endforeach;?>
                                    <?php else: ?>
                                        <li class="element-1">
                                        <label>ID Proizvoda:</label>
                                        <span class="inputField short">
                                            <input type="text" id="product_id-1" name="product_id[]" class="only_numbers">
                                        </span>
                                        <img class="add_new add_button-1" onclick="add_product(this)" alt="add_new" src="<?= layout_url('flexigrid/add.png')?>">
                                    </li>
                                    <?php endif?>
                                </div>
                                    <?php } ?>
                              </ul>
                        </fieldset>
                    <?php } ?>


					<?php if( ($ad->getCategoryId() == 8) || ($ad->getCategoryId() == 9) ) { ?>
						<fieldset class="sectionForm half" style="padding-left: 100px;">
							<ul>
								
		                        <?php if( $ad->getCategoryId() == 8 ) { ?>
		                        <li>
		                            <label>Tekst:</label>
		                            <span class="textArea"><textarea name="text_banner"><?= $ad->getText() ?></textarea></span>
		                        </li>
		                        <?php } ?>
		                        
		                        
		                    <?php if( is_object($ad->getCategory()) ) { ?>
	                            <li>
		                         	<label class="alignLeft">Kategorija:</label>
		                            <div class="customComboHolder">
		                                <div>
		                                    <?php is_object($ad->getCategory())? $cat=$ad->getCategory()->getName():''; echo isset($cat)? $cat : 'Odaberite kategoriju' ?>
		                                </div>
		                                <select name="filter_ad_category" id="category" class="required">
		                                    <option value="" <?php echo isset($cat) ? "" : 'selected'; ?> ><?= 'Odaberite kategoriju' ?></option>
		                                    <?php foreach( $categories as $category): ?>
		                                        <option value="<?= $category -> getID() ?>" 
		                                        <?php if (is_object($ad->getCategory())) {
		                                                if ($ad->getCategory()->getID() == $category -> getID()) {
		                                                    echo 'selected';
		                                                }
		                                            }
		                                        ?> > <?= $category -> getName() ?>
		                                        </option>
		                                    <?php endforeach; ?>
		                                </select> 
		                            </div>
		                        </li>
		                        <li>
		                            <label class="alignLeft">Grupa:</label>
		                            <div class="customComboHolder">
		                            	<?php if( $ad->getSubcategory() == NULL ): ?>
	                            		<div class="selected_group">Sellect</div>
	                            		<select name="filter_ad_group" id="group">
		                                	<option value="" <?php echo ($ad->getSubcategory() == NULL) ? "" : 'selected'; ?> ><?= 'Odaberite grupu' ?></option>
		                                    <?php foreach( $subcategories as $subcategory): 
		                                    
		                                        if ($subcategory->getParent() == NULL && $subcategory->getCategory()->getID() == $ad->getCategory()->getID() ){ ?>
		                                        <option value="<?= $subcategory->getID() ?>" >
		                                            <?= $subcategory->getName() ?></option>
		                                    <?php } endforeach; ?>
		                                </select>
		                                <?php else: ?>
		                            		
		                                <div class="selected_group">
		                                    <?php if ($ad->getSubcategory()->getParent()== NULL) {
		                                                echo $ad->getSubcategory()->getName(); 
		                                          } else {
		                                               foreach( $subcategories as $subcategory) {
		                                                   if ($subcategory->getID() == $ad->getSubcategory()->getParent()->getID()){
		                                                       echo $subcategory->getName(); 
		                                                   }
		                                               }
		                                          }
		                                      ?>
		                                </div>
		                                <select name="filter_ad_group" id="group">
		                                	<option value="" <?php echo ($ad->getSubcategory() == NULL) ? "" : 'selected'; ?> ><?= 'Odaberite grupu' ?></option>
		                                    <?php foreach( $subcategories as $subcategory): 
		                                    
		                                        if ($subcategory->getParent () == NULL && $subcategory->getCategory()->getID() == $ad->getCategory()->getID() ){ ?>
		                                        <option value="<?= $subcategory->getID() ?>" 
		                                            <?php if ($ad->getSubcategory()->getParent()== NULL) {
		                                                      if ($ad->getSubcategory()->getID() == $subcategory->getID()) {
		                                                          echo 'selected';
		                                                      }
		                                                  } else {
		                                                      if ($subcategory->getID() == $ad->getSubcategory()->getParent()->getID()){
		                                                        echo 'selected';
		                                                      } 
		                                                  }
		                                                  ?> >
		                                            <?= $subcategory->getName() ?></option>
		                                    <?php } endforeach; ?>
		                                </select>
		                                <?php endif; ?>
		                            </div>
		                        </li>
			                    <li>
	                                <label class="alignLeft">Podkategorija:</label>
	                                <div class="customComboHolder">
	                                    <div class="selected_subcategory">
	                                        <?php if ($ad && ($ad->getSubcategory()->getParent() != NULL) ) {
	                                                   foreach( $subcategories as $subcategory ){  
	                                                       if ($ad->getSubcategory()->getID() == $subcategory->getID()){
	                                                           echo $subcategory->getName(); 
	                                                       }
	                                                   }    
	                                               } else {
	                                               echo "Sellect" ;    
	                                               }
	                                               ?></div>
	                                    <select name="filter_ad_subcategory" id="subcategory" >
	                                        <?= is_object($ad && ($ad->getSubcategory()->getParent())) ? "":'<option value="" selected>Sellect</option>' ?>
	                                        <?php if ($ad && is_object($ad->getSubcategory()->getParent())){?>    
	                                        <?php foreach( $subcategories as $subcategory ):
	                                            if( $ad->getSubcategory()->getParent() == $subcategory->getParent()){  ?>
	                                            <option value="<?= $subcategory->getID() ?>" 
	                                            <?php if ($ad->getSubcategory()->getParent() != NULL) {
	                                                      if ($ad->getSubcategory()->getID() == $subcategory->getID()){
	                                                         echo 'selected';
	                                                      }
	                                                  }?> ><?= $subcategory->getName() ?></option>
	                                        <?php } endforeach; } ?>
	                                    
	                                    </select>
	                                </div>
	                            </li>
	                        <?php } else { ?>
	                        	<li>
	                                <label class="alignLeft">Kategorija:</label>
	                                <div class="customComboHolder">
	                                    <div class="selected_category">Odaberite kategoriju</div>
	                                    <select name="filter_ad_category" id="product_category">
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
	                                    <select name="filter_ad_group" id="group">
	                                        <option value="" selected>Prvo odaberite kategoriju</option>
	                                    </select>
	                                </div>
	                             </li>
	                             <li>
	                                <label class="alignLeft">Podkategorija:</label>
	                                <div class="customComboHolder">
	                                    <div class="selected_subcategory">Odaberite podkategoriju</div>
	                                    <select name="filter_ad_subcategory" id="subcategory" >
	                                        <option value="" selected>Prvo odaberite grupu</option>
	                                    </select>
	                                </div>
	                             </li>
	                        <?php } ?>
                            <?php if($ad->getCategoryId() == 8): ?>
                            <li>
	                            <label>Minimalna cena:</label>
	                            <span class="inputField wide"><input type="text"  name="min_price" value="<?= $ad->getMinPrice() ?>"></span>
	                        </li>
	                        <li>
	                            <label>Maksimalna cena:</label>
	                            <span class="inputField wide"><input type="text"  name="max_price" value="<?= $ad->getMaxPrice() ?>"></span>
	                        </li>
                            <?php endif; ?>       

                              </ul>
                        </fieldset>                                    
                                   
                                   
                        <li>                      
                <h2>Specifikacije</h2>
                <a href="#" class="collapse">Collapse</a>
                  <div class="innerContent">
                      <fieldset class="sectionForm">
                      <ul>
                      <fieldset class="sectionForm half" style="clear:both"> 
		                       <li>
	                         	<?php foreach($specifications as $specification) {
	                         		if($specification->getTypeID() == 2) continue;
	                         		echo '<li>'.$specification->getName().':</li>';
									foreach($specification->getFilters() as $filter) { ?>
									 <li>
		                            	<label>&nbsp;</label>
										<input type="checkbox" value="<?= $filter->getID() ?>" name="ad_filters[]"<?= $ad->getFilters()->contains($filter) ? ' checked="checked"' : '' ?> />
										<small><?= $filter->getName() ?></small>
		                            </li>
		                            <?php } 
	                         	} ?>
	                        	</li>
		                        
					<?php } ?>
					</fieldset>
				</ul>
				</div>
				</li>

                    <div class="borderTop">

                        <span class="button back">
                           <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('productsGrid', 'ads/listing/'.$route_id) ?>" />
                        </span> 
                        <span class="button save">
                            <input type="button" value="Sačuvaj" onclick="validateForm('adsForm')" /> 
                        </span>
                        <?php if( isset($message) ) echo $message; ?>
                    </div>
                </form>
            </div>
        </li>
    </ul>
</div>