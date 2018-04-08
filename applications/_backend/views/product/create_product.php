<div id="content">
    <ul>
        <form id="productForm" method="post" action="<?= site_url( 'products/save' ) ?>" >    
        <li>                      
            <h2>Detalji proizvoda</h2>
            <a href="#" class="collapse">Collapse</a>
			<div class="innerContent">
				<fieldset class="sectionForm">
					<ul>
                  		<fieldset class="sectionForm half" style="clear:both">
							
							<li>
	                            <label>Dobavljač:</label>
	                            <span class="inputField wide"><input type="text" name="vendor" value="" /></span>
	                        </li>
	                        <li>
	                            <label>Cena:</label>
	                            <span class="inputField wide"><input type="text" name="price" class="required" value=""/></span>
	                        </li>
	                        <li>
	                            <label>Stara cena:</label>
	                            <span class="inputField wide"><input type="text" name="old_price" value=""/></span>
	                        </li>
	                        <li>
	                            <label>Ime:</label>
	                            <span class="inputField wide"><input type="text" name="name" class="required" value=""/></span>
	                        </li>
	                        <li>
	                        	<label class="alignLeft">Brend:</label>
	                            <div class="customComboHolder">
	                                <div>Odaberi brend</div>
	                                <select name="brand" id="brand" class="required">
	                                	<option value="" selected>Sellect</option> 
	                                    <?php foreach( $brands as $brand): ?>
	                                        <option value="<?= $brand->getID() ?>"><?= $brand->getName() ?></option>
	                                    <?php endforeach; ?>
	                                </select> 
	                            </div>
	                        </li>
	                        <li>
	                        	<label class="alignLeft">Kategorija:</label>
	                            <div class="customComboHolder">
	                                <div><?= $category->getName() ?></div>
	                                <select name="category" id="category" class="required">
	                                    <?php foreach( $categories as $category_data): ?>
	                                        <option value="<?= $category_data->getID() ?>" <?php if( $category_data->getID() == $category->getID() ) { echo 'selected'; } ?>><?= $category_data->getName() ?></option>
	                                    <?php endforeach; ?>
	                                </select> 
	                            </div>
	                        </li>
	                        <li>
	                            <label class="alignLeft">Grupa:</label>
	                            <div class="customComboHolder">
	                                <div class="selected_group">Odaberi grupu</div>
	                                <select name="group" id="group">
	                                	<option value="" selected>Sellect</option> 
	                                    <?php foreach( $subcategories as $subcategory): ?>
	                                    	<?php if( $subcategory->getParent() == NULL && $subcategory->getCategory()->getID() == $category->getID() ): ?>
	                                        <option value="<?= $subcategory->getID() ?>"  ><?= $subcategory->getName() ?></option>
	                                        <?php endif; ?>
	                                    <?php endforeach; ?>
	                                </select>
	                            </div>
	                        </li> 
	                        <li>
                            <label class="alignLeft">Podkategorija:</label>
                            <div class="customComboHolder">
                                <div class="selected_subcategory">Odaberi grupu</div>
                                <select name="subcategory" id="subcategory">
                                    <option value="" selected>Sellect</option>  
                                </select>
                            </div>
                        </li>
                        <li>
                            <label class="alignLeft">Garancija:</label>
                            <div class="customComboHolder">
                                <div>Odaberite garanciju</div>
                                <select name="warranty" id="warranty" >
                                    <option value="" >Odaberite garanciju</option>
                                    <?php foreach( $warranties as $warranty ): ?>
                                        <option value="<?= $warranty->getID() ?>" > <?= $warranty->getName() ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </li>
                        <!--<li>
                        	<label class="alignLeft">Sticker:</label>
                            <div class="customComboHolder">
                                <div>Odaberi sticker</div>
                                <select name="sticker" id="sticker">
                                	<option value="" selected>Sellect</option> 
                                    <?php foreach( $stickers as $sticker): ?>
                                        <option value="<?= $sticker->getID() ?>"><?= $sticker->getName() ?></option>
                                    <?php endforeach; ?>
                                </select> 
                            </div>
                        </li>-->
	                        
	                     </fieldset>
	                     <fieldset class="sectionForm half" style="padding-left: 30px;">
	                        <li>
	                            <label>Akcija:</label>
	                            <input type="radio" name="promotion" value="1"/>
	                            <small>Da</small>
	                            <input type="radio" name="promotion" value="0"/>
	                            <small>Ne</small>
	                        </li>
	                        <!--<li>
	                            <label>Rasprodaja:</label>
	                            <input type="radio" name="sale" value="1"/>
	                            <small>Da</small>
	                            <input type="radio" name="sale" value="0"/>
	                            <small>Ne</small>
	                        </li>
	                        <li>
	                            <label>Outlet:</label>
	                            <input type="radio" name="outlet" value="1"/>
	                            <small>Da</small>
	                            <input type="radio" name="outlet" value="0"/>
	                            <small>Ne</small>
	                        </li>-->
	                        <li>
	                            <label>Status:</label>
	                            <input type="radio" name="status" value="1"/>
	                            <small>Aktivan</small>
	                            <input type="radio" name="status" value="0"/>
	                            <small>Neaktivan</small>
	                        </li>
                       
                    	</fieldset>
                	</ul>
            	</fieldset>
            </div>
            <div class="borderTop">
                <span class="button back">
                    <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('productsGrid', 'products/listing/' . $category->getID()) ?>" />
                </span> 
                <span class="button save">
                    <input type="button" value="Sačuvaj" onclick="validateForm('productForm')" /> 
                </span>
                <?php if( isset($message) ) echo $message; ?>
            </div>
        </li>               
        </form> 
    </ul>
</div>