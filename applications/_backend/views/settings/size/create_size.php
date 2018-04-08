<div id="content">
    <ul>
        <li>
			<h2>Nova veličina</h2>       	
            <a href="#" class="collapse">Collapse</a>

            <div class="innerContent">

                <form id="sizeForm" method="post" action="<?= site_url( 'settings/sizes/save') ?>" >
                    <fieldset class="sectionForm">

                        <ul>
                        	<li>
                                <input type="hidden" name="subcategory_id" id="subcategory_id" value="<?= $subcategory_id ?>"  />  
                            </li>
                        	<li>
                                <label>Naziv:</label>
                                <span class="inputField wide"><input type="text" name="title" class="required" /></span>
                            </li>
                            <li>
                                <label>Pozicija:</label>
                                <span class="inputField short"><input type="text" name="position" id="position" class="only_numbers"/></span>
                            </li>
                            <li>
                                <label>Status:</label>
                                <input type="radio" name="status" value="1"/>
                                <small>Aktivna</small>
                                <input type="radio" name="status" value="0"/>
                                <small>Neaktivna</small>
                            </li>
                        </ul>

                    </fieldset>

                    <div class="borderTop">
                        
                        <span class="button back">

                            <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('sizeGrid', 'settings/sizes/listing/'.$subcategory_id) ?>" />
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