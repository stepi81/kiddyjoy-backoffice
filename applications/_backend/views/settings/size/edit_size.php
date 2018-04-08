<div id="content">
    <ul>
        <li>
			<h2>Detalji</h2>       	
            <a href="#" class="collapse">Collapse</a>

            <div class="innerContent">

                <form id="sizeForm" method="post" action="<?= site_url( 'settings/sizes/edit/'.$size->getID()) ?>" >
                    <fieldset class="sectionForm">

                        <ul>
                        	<li>
                                <label>Naziv:</label>
                                <span class="inputField wide"><input type="text" name="title" class="required" value="<?= $size->getName() ?>" /></span>
                            </li>
                            <li>
                                <label>Pozicija:</label>
                                <span class="inputField short"><input type="text" name="position" id="position" value="<?= $size->getPosition() ?>" class="only_numbers"/></span>
                            </li>
                            <li>
                                <label>Status:</label>
                                <input type="radio" name="status" value="1"<?= $size->getStatus() ? ' checked="checked"' : '' ?>/>
                                <small>Aktivna</small>
                                <input type="radio" name="status" value="0"<?= $size->getStatus() ? '' : ' checked="checked"' ?>/>
                                <small>Neaktivna</small>
                            </li>
                        </ul>

                    </fieldset>

                    <div class="borderTop">
                        
                        <span class="button back">

                            <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('sizeGrid', 'settings/sizes/listing/'.$size->getSubcategory()->getID()) ?>" />
                        </span> 
                        <span class="button save">
                            <input type="button" value="Sačuvaj" onclick="validateForm('sizeForm')" /> 
                        </span>
                        <?php if( isset($message) ) echo $message; ?>

                    </div>
                </form>

            </div>
        </li>
    </ul>
</div>