<div id="content">
    <ul>
        <li>
			<h2>Detalji</h2>       	
            <a href="#" class="collapse">Collapse</a>

            <div class="innerContent">

                <form id="colorForm" method="post" action="<?= site_url( 'settings/colors/edit/'.$color->getID()) ?>" >
                    <fieldset class="sectionForm">

                        <ul>
                        	<li>
                                <label>Naziv:</label>
                                <span class="inputField wide"><input type="text" name="title" class="required" value="<?= $color->getName() ?>" /></span>
                            </li>
                            <!--<li>
                                <label>Kod:</label>
                                <span class="inputField wide"><input type="text" name="code" class="required" /></span>
                            </li>-->
                            <li>
                                <label>Pozicija:</label>
                                <span class="inputField short"><input type="text" name="position" id="position" value="<?= $color->getPosition() ?>" class="only_numbers"/></span>
                            </li>
                            <li>
                                <label>Status:</label>
                                <input type="radio" name="status" value="1"<?= $color->getStatus() ? ' checked="checked"' : '' ?>/>
                                <small>Aktivna</small>
                                <input type="radio" name="status" value="0"<?= $color->getStatus() ? '' : ' checked="checked"' ?>/>
                                <small>Neaktivna</small>
                            </li>
                        </ul>

                    </fieldset>

                    <div class="borderTop">
                        
                        <span class="button back">

                            <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('colorGrid', 'settings/colors/listing') ?>" />
                        </span> 
                        <span class="button save">
                            <input type="button" value="Sačuvaj" onclick="validateForm('colorForm')" /> 
                        </span>
                        <?php if( isset($message) ) echo $message; ?>

                    </div>
                </form>

            </div>
        </li>
    </ul>
</div>