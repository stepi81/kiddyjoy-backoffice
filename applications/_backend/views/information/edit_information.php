<div id="content">
    <ul>
        <li>
            <h2><?= $page->getName() ?></h2>
            <a href="#" class="collapse">Collapse</a>

            <div class="innerContent">
                <form id="informationForm" method="post" action="<?= site_url( 'informations/edit/'.$page->getID() ) ?>" enctype="multipart/form-data">
                    <fieldset class="sectionForm">

                        <ul>
                            <li>
                                <label style="width:320px !important;"><a href="<?= $page->getURL() ?>" target="_blank"><?= $page->getURL() ?></a></label>
                            </li>
                        	<li>
                                <label>&nbsp;</label>
                                <span class="image"><img src="<?= $page->getIconURL() ?>" alt="KiddyJoy informacije" /></span>
                            </li>
                        	<li>
	                            <label>Ikona:</label>
	                            <span class="inputField wide"><input type="text" name="icon_name" disabled="true" /></span>
                                <div class="uploadFile">
                                    <input type="file" name="icon" />
                                </div>
                            </li>
                            <li>
                                <label>Status:</label>
                                <input type="radio" name="status" value="1"<?= $page->getStatus() ? ' checked="checked"' : '' ?> />
                                <small>Aktivna</small>
                                <input type="radio" name="status" value="0"<?= $page->getStatus() ? '' : ' checked="checked"' ?> />
                                <small>Neaktivna</small>
                            </li>
                            <li>
                                <label>Na naslovnoj strani:</label>
                                <input type="radio" name="featured" value="1"<?= $page->getFeatured() ? ' checked="checked"' : '' ?> />
                                <small>Istaknuta</small>
                                <input type="radio" name="featured" value="0"<?= $page->getFeatured() ? '' : ' checked="checked"' ?> />
                                <small>Neistaknuta</small>
                            </li>
                            <li>
                                <label>Naslov:</label>
                                <span class="inputField wide"><input type="text" name="name" class="required" value="<?= $page->getTitle() ?>" /></span>
                            </li>
                            <li>
                                <label>Pozicija:</label>
                                <span class="inputField short"><input type="text" name="position" class="only_numbers" value = "<?= $page->getPosition() ?>" /></span>
                           	</li> 
                            <li>
                                <label>Upload slika:</label>
                                <?= $plupload ?>
                                <div id="uploader" style="width: 632px;" ><p>Vaš browser nema Flash, Silverlight, Gears, BrowserPlus ili HTML5 podršku.</p></div>
                            </li>
                            <li>
                                <label>Stranica:</label>
                                <?= $tinymce ?>
                                <textarea name="page" id="page"><?= $page->getContent() ?></textarea>
                            </li>
                        </ul>

                    </fieldset>

                    <div class="borderTop">

                        <span class="button back">
                            <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid( 'productsGrid', 'informations/listing/' . $page->getSection()->getID() ) ?>" />
                        </span> 
                        <span class="button save">
                            <input type="button" value="Izmeni" onclick="validateForm('informationForm')" /> 
                        </span>
                        <?php if( isset($message) ) echo $message; ?>

                    </div>
                </form>

            </div>
        </li>
    </ul>
</div>