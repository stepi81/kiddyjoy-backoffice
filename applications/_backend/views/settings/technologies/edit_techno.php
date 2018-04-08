<div id="content">
    <ul>
        <li>
            <h2>Detalji tehnologije</h2>
            <a href="#" class="collapse">Collapse</a>

            <div class="innerContent">

                <form id="technologiesForm" method="post" action="<?= site_url( 'settings/technologies/edit/'.$technology->getID() ) ?>" enctype="multipart/form-data">
                    <fieldset class="sectionForm">

                        <ul>
                        	<li>
                                <label>&nbsp;</label>
                                <span class="image"><img src="<?= $technology->getImageURL() ?>" alt="KiddyJoy vesti" /></span>
                            </li>
                            <li>
                                <label>Naslov:</label>
                                <span class="inputField wide"><input type="text" name="title" class="required" value="<?= $technology->getTitle() ?>" /></span>
                            </li>
                            <li>
                                <label>Opis:</label>
                                <span class="textArea"><textarea name="summary" class="required"><?= $technology->getDescription() ?></textarea></span>
                            </li>
                            <li>
	                            <label>Slika:</label>
	                            <span class="inputField wide"><input type="text" name="thumb_name" disabled="true" /></span>
                                <div class="uploadFile">
                                    <input type="file" name="thumb" />
                                </div>
                            </li>
                        </ul>

                    </fieldset>

                    <div class="borderTop">

                        <span class="button back">
                             <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('productsGrid', 'settings/technologies/listing') ?>" />
                        </span> 
                        <span class="button save">
                            <input type="button" value="Izmeni" onclick="validateForm('technologiesForm')" /> 
                        </span>
                        <?php if( isset($message) ) echo $message; ?>

                    </div>
                </form>

            </div>
        </li>
    </ul>
</div>