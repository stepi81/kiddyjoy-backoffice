<div id="content">
    <ul>
        <li>
            <h2>Novi test</h2>
            <a href="#" class="collapse">Collapse</a>
            <div class="innerContent">
                <form id="newsForm" method="post" action="<?= site_url('benchmarks/save')?>" enctype="multipart/form-data">
                    <fieldset class="sectionForm">
                        <ul>
                            <li>
                            <label class="alignLeft">Kategorija:</label>
                                    <div class="customComboHolder">
                                        <div>Odaberite kategoriju</div>
                                        <select name="test_category" id="test_category" class="required">
                                            <option value="" selected>Odaberite kategoriju</option>
                                            <?php foreach( $categories as $category): ?>
                                                <option value="<?= $category->getID() ?>" ><?= $category->getName() ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                            </li>
                            <li>
                                <label>ID proizvoda:</label>
                                <span class="inputField short"><input type="text" name="product_id" class="only_numbers"/></span>
                            </li>
                            <li>
                                <label>Naslov:</label>
                                <span class="inputField wide"><input type="text" name="title" class="required" /></span>
                            </li>
                            <li>
                                <label>Kratak opis:</label>
                                <span class="textArea"><textarea name="short_info" class="required"></textarea></span>
                            </li>
                            <li>
                            <li>
	                            <label>Thumb:</label>
	                            <span class="inputField wide"><input type="text" name="thumb_name" class="required" disabled="true" /></span>
                                <div class="uploadFile">
                                    <input type="file" name="thumb" class="required" />
                                </div>
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
                            <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('benchmarksGrid', 'benchmarks/listing') ?>" />
                        </span> 
                        <span class="button save">
                            <input type="button" value="Nastavi" onclick="validateForm('newsForm')" /> 
                        </span>
                        <?php if( isset($message) ) echo $message; ?>
                    </div>
                </form>
            </div>
        </li>
    </ul>
</div>