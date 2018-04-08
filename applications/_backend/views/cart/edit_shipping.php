<div id="content">
    <ul>
        <li>
            <h2>Detalji isporuke</h2>
            <a href="#" class="collapse">Collapse</a>

            <div class="innerContent">
            	
            	<form id="shippingForm" method="post" action="<?= site_url( 'cart/shipping/edit/'.$shipping->getID() ) ?>" enctype="multipart/form-data">

	                <fieldset class="sectionForm">
	
	                    <ul>
	                    	<li>
	                            <label>&nbsp;</label>
	                            <span class="image"><img src="<?= $shipping->getIconURL() ?>" alt="<?= $shipping->getTitle() ?>" /></span>
	                        </li>
	                        <li>
	                            <label>Naziv:</label>
	                            <span class="inputField wide"><input type="text" name="title" class="required" value="<?= $shipping->getTitle() ?>" /></span>
	                        </li>
	                        <li>
	                            <label>Limit:</label>
	                            <span class="inputField wide"><input type="text" name="limit" value="<?= $shipping->getLimit() ?>" /></span>
	                        </li>
	                        <li>
	                            <label>Cena:</label>
	                            <span class="inputField wide"><input type="text" name="price" value="<?= $shipping->getPrice() ?>" /></span>
	                        </li>
	                        <li>
	                            <label>Opis:</label>
	                            <span class="textArea"><textarea name="description"><?= $shipping->getDescription() ?></textarea></span>
	                        </li>
	                        <li>
	                        	<label>Lokacija:</label>
	                            <input type="radio" name="location" value="1"<?= $shipping->getLocations() ? ' checked="checked"' : '' ?> />
	                            <small>Aktivna</small>
	                            <input type="radio" name="location" value="0"<?= $shipping->getLocations() ? '' : ' checked="checked"' ?> />
	                            <small>Neaktivna</small>
	                        </li>
	                        <li>
	                        	<label>Status:</label>
	                            <input type="radio" name="status" value="1"<?= $shipping->getStatus() ? ' checked="checked"' : '' ?> />
	                            <small>Aktivna</small>
	                            <input type="radio" name="status" value="0"<?= $shipping->getStatus() ? '' : ' checked="checked"' ?> />
	                            <small>Neaktivna</small>
	                        </li>
	                        <li>
	                            <label>Slika:</label>
	                            <span class="inputField wide"><input type="text" name="image_name" disabled="true" /></span>
                                <div class="uploadFile">
                                    <input type="file" name="icon" />
                                </div>
                            </li>
	                    </ul>
	
	                </fieldset>
	
	                <div class="borderTop">
	
	                    <span class="button back">
	                        <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('cartShippingGrid', 'cart/shipping/listing') ?>" />
	                    </span> 
						<span class="button save">
                            <input type="button" value="Izmeni" onclick="validateForm('shippingForm')" /> 
                        </span>
                        <?php if( isset($message) ) echo $message; ?>
						
	                </div>
	                
	        	</form>

            </div>
        </li>
    </ul>
</div>
