<div id="content">
    <ul>
        <li>
            <h2>Detalji grupe</h2>
            <a href="#" class="collapse">Collapse</a>

            <div class="innerContent">

                <form id="subcategoryForm" method="post" action="<?= site_url( 'product/subcategories/edit/' . $subcategory->getID()) ?>" enctype="multipart/form-data">
                    <fieldset class="sectionForm">

                        <ul>
                        	<li>
                                <label>&nbsp;</label>
                                <span class="image"><img src="<?= $subcategory->getImageURL() ?>" alt="KiddyJoy grupe" /></span>
                            </li>
                            <li>    
                                <label>Ime grupe:</label>
                                <span class="inputField wide"><input type="text" name="name" class="required" value="<?= $subcategory->getName() ?>"/></span>
                            </li>
                            <li>
                                <label>Pozicija grupe:</label>
                                <span class="inputField short"><input type="text" name="position" class="only_numbers" value = "<?= $subcategory->getPosition() ?>" /></span>
                           </li>
                           <li>
	                            <label>Slika:</label>
	                            <span class="inputField wide"><input type="text" name="thumb_name" disabled="true" /></span>
                                <div class="uploadFile">
                                    <input type="file" name="thumb" />
                                </div>
                            </li>
                            <li>
			                    <label>Opis:</label><br /><br /><br />
			                    <?= $tinymce ?> 
			                    <textarea name="other" id="other"><?= $subcategory->getDescription() ?></textarea>
			               </li>
                        </ul>
                    </fieldset>

                    <div class="borderTop">

                        <span class="button back">
                            <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('subcategoryGrid', 'product/subcategories/groups_listing/' . $subcategory->getCategory()->getID()) ?>" />
                        </span> 
                        <span class="button save">
                            <input type="button" value="Izmeni" onclick="validateForm('subcategoryForm')" /> 
                        </span>
                        <?php if( isset($message) ) echo $message; ?>

                    </div>
                </form>

            </div>
        </li>
    </ul>
</div>
