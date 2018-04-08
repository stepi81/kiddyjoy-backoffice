<div id="content">
    <ul>
        <li>
            <h2>Detalji sekcije</h2>
            <a href="#" class="collapse">Collapse</a>

            <div class="innerContent">
            	
				<form id="cartSectionForm" method="post" action="<?= site_url( 'cart/sections/edit/'.$section->getID() ) ?>" enctype="multipart/form-data">
	                <fieldset class="sectionForm">
	
	                    <ul>
	                    	<li>
	                            <label>Label:</label>
	                            <span class="inputField wide"><input type="text" name="label" class="required" value="<?= $section->getLabel() ?>" /></span>
	                        </li>
	                        <li>
	                            <label>Naziv:</label>
	                            <span class="inputField wide"><input type="text" name="title" class="required" value="<?= $section->getTitle() ?>" /></span>
	                        </li>
	                        <li>
	                            <label>Opis:</label>
	                            <span class="textArea"><textarea name="description"><?= $section->getDescription() ?></textarea></span>
	                        </li>
	                        <li>
	                        	<label>Status:</label>
	                            <input readonly="readonly" type="radio" name="status" value="1"<?= $section->getStatus() ? ' checked="checked"' : '' ?> />
	                            <small>Aktivna</small>
	                            <input readonly="readonly" type="radio" name="status" value="0"<?= $section->getStatus() ? '' : ' checked="checked"' ?> />
	                            <small>Neaktivna</small>
	                        </li>
	                    </ul>
	
	                </fieldset>
	
	                <div class="borderTop">
	
	                    <span class="button back">
	                        
	                        <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('cartSectionsGrid', 'cart/sections/listing') ?>" />
	                    </span> 
						<span class="button save">
	                        <input type="button" value="Izmeni" onclick="validateForm('cartSectionForm')" /> 
	                    </span>
	                    <?php if( isset($message) ) echo $message; ?>
	                </div>
				</form>
				
            </div>
        </li>
    </ul>
</div>
