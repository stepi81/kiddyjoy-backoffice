<div id="content">
    <ul>
        <li>
            <h2>Detalji testa</h2>
            <a href="#" class="collapse">Collapse</a>

            <div class="innerContent">

                <form id="benchmarkForm" method="post" action="<?= site_url( 'benchmarks/edit/'.$benchmark->getID() ) ?>" enctype="multipart/form-data">
                    <fieldset class="sectionForm">

                        <ul>
                        	<li>
                                <label>&nbsp;</label>
                                <span class="image"><img src="<?= $benchmark->getThumbURL() ?>" alt="KiddyJoy test" /></span>
                            </li>
                            <li>
                                <label>Datum testa:</label>
                                <span class="inputField wide"><input type="text" disabled="disabled" value="<?= $benchmark->getFormatedDate() ?>"/></span>
                            </li>
                             <li>
                                <label class="alignLeft">Kategorija:</label>
                                    <div class="customComboHolder">
                                        <div>
                                            <?= $benchmark->getCategory()->getName() ?>
                                        </div>
                                        <select name="test_category" id="test_category" >
                                            <?php foreach( $categories as $category): ?>
                                                <option value="<?= $category -> getID() ?>" 
                                                    <?php if ($benchmark->getCategory()->getID() == $category -> getID()) {
                                                              echo 'selected';
                                                          }
                                                    ?> > <?= $category->getName() ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                            </li>
                            <li>
                                <label>ID proizvoda:</label>
                                <span class="inputField short"><input type="text" name="product_id" value="<?= is_object($benchmark->getProduct())? $benchmark->getProduct()->getID() : '' ?>" /></span>
                            </li>
                            <li>
                                <label>Naslov:</label>
                                <span class="inputField wide"><input type="text" name="title" class="required" value="<?= $benchmark->getTitle() ?>" /></span>
                            </li>
                            <li>
                                <label>Kratak opis:</label>
                                <span class="textArea"><textarea name="short_info" class="required"><?= $benchmark->getShortInfo() ?></textarea></span>
                            </li>
                            <li>
	                            <label>Thumb:</label>
	                            <span class="inputField wide"><input type="text" name="thumb_name" disabled="true" /></span>
                                <div class="uploadFile">
                                    <input type="file" name="thumb" />
                                </div>
                            </li>
                            <li>
                                <label>Status:</label>
                                <input type="radio" name="status" value="1"<?= $benchmark->getStatus() ? ' checked="checked"' : '' ?> />
                                <small>Aktivna</small>
                                <input type="radio" name="status" value="0"<?= $benchmark->getStatus() ? '' : ' checked="checked"' ?> />
                                <small>Neaktivna</small>
                            </li>
                            <li>
                                <label>Upload slika:</label>
                                <?= $plupload ?>
                                <div id="uploader" style="width: 632px;" ><p>Vaš browser nema Flash, Silverlight, Gears, BrowserPlus ili HTML5 podršku.</p></div>
                            </li>
                            <li>
                                <label>Stranica:</label>
                                <?= $tinymce ?>
                                <textarea name="description" id="description"><?= $benchmark->getDescription() ?></textarea>
                            </li>
                        </ul>

                    </fieldset>

                    <div class="borderTop">

                        <span class="button back">
                             <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('benchmarksGrid', 'benchmarks/listing') ?>" />
                        </span> 
                        <span class="button save">
                            <input type="button" value="Izmeni" onclick="validateForm('benchmarkForm')" /> 
                        </span>
                        <?php if( isset($message) ) echo $message; ?>

                    </div>
                </form>

            </div>
        </li>
    </ul>
</div>