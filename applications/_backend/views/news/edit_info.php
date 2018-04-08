<div id="content">
    <ul>
        <li>
            <h2><?php if($news->getNewsTypeID() == 1) { echo 'Detalji novosti'; } else { echo 'Detalji akcije'; }  ?></h2>
            <a href="#" class="collapse">Collapse</a>

            <div class="innerContent">

                <form id="newsForm" method="post" action="<?= site_url( 'news/edit/'.$news->getID() ) ?>" enctype="multipart/form-data">
                    <fieldset class="sectionForm half">

                        <ul>
                        	<li>
                                <label>&nbsp;</label>
                                <span class="image"><img src="<?= $news->getThumbURL() ?>" alt="KiddyJoy vesti" /></span>
                            </li>

                            <li>
                                <label>Datum objave:</label>
                                <span class="inputField wide"><input type="text" name="send_date" id="send_date" class="required" value="<?php echo isset($news)? $news->getFormatedDate() : ''?>"/></span>
                            </li>
                            <li>
                            	<label>Status:</label>
                                <input type="radio" name="status" value="1"<?= $news->getStatus() ? ' checked="checked"' : '' ?> />
                                <small>Aktivna</small>
                                <input type="radio" name="status" value="0"<?= $news->getStatus() ? '' : ' checked="checked"' ?> />
                                <small>Neaktivna</small>
                            </li>
                            <li>
                                <label>Naslov:</label>
                                <span class="inputField wide"><input type="text" name="title" class="required" value="<?= $news->getTitle() ?>" /></span>
                            </li>
                            <li>
                                <label>Kratak opis:</label>
                                <span class="textArea"><textarea name="summary" class="required"><?= $news->getSummary() ?></textarea></span>
                            </li>
                            <li>
	                            <label>Thumb:</label>
	                            <span class="inputField wide"><input type="text" name="thumb_name" disabled="true" /></span>
                                <div class="uploadFile">
                                    <input type="file" name="thumb" />
                                </div>
                            </li>
                            <li>
                                <label>Upload slika:</label>
                                <?= $plupload ?>
                                <div id="uploader" style="width: 632px;" ><p>Vaš browser nema Flash, Silverlight, Gears, BrowserPlus ili HTML5 podršku.</p></div>
                            </li>
                            <li>
                                <label>Stranica:</label>
                                <?= $tinymce ?>
                                <textarea name="page" id="page"><?= $news->getPage() ?></textarea>
                            </li>
                        </ul>

                    </fieldset>
                    
                    <fieldset class="sectionForm half">
                    <ul>
                    <div class="product_ids_holder">
                        <?php if(!empty($product_ids)):?>
                            <?php foreach($product_ids as $key=>$val):?>
                            <li class="element-<?= $key + 1 ?>">
                                <label>ID Proizvoda:</label>
                                <span class="inputField short">
                                    <input type="text" id="product_id-<?= $key + 1 ?>" name="product_id[]" class="only_numbers" value="<?=$val?>">
                                </span>
                                <?php if($key == count($product_ids) - 1):?>
                                    <img onclick="delete_product(this)" id="delete-<?= $key + 1 ?>" class="delete_product" alt="delete_product" src="<?= layout_url('flexigrid/delete.png')?>">
                                    <img class="add_new add_button-<?= $key + 1 ?>" onclick="add_product(this)" alt="add_new" src="<?= layout_url('flexigrid/add.png')?>">
                                <?php elseif($key > 0):?>
                                    <img onclick="delete_product(this)" id="delete-<?= $key + 1  ?>" class="delete_product" alt="delete_product" src="<?= layout_url('flexigrid/delete.png')?>">
                                <?php endif;?>
                            </li>
                            <?php endforeach;?>
                        <?php else:?>
                            <li class="element-1">
                            <label>ID Proizvoda:</label>
                            <span class="inputField short">
                                <input type="text" id="product_id-1" name="product_id[]" class="only_numbers">
                            </span>
                            <img class="add_new add_button-1" onclick="add_product(this)" alt="add_new" src="<?= layout_url('flexigrid/add.png')?>">
                        </li>
                        <?php endif?>
                    </div>
                    </ul>
                    </fieldset>

                    <div class="borderTop">

                        <span class="button back">
                             <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('productsGrid', 'news/listing/'.$news->getNewsTypeID()) ?>" />
                        </span> 
                        <span class="button save">
                            <input type="button" value="Izmeni" onclick="validateForm('newsForm')" /> 
                        </span>
                        <?php if( isset($message) ) echo $message; ?>

                    </div>
                </form>

            </div>
        </li>
    </ul>
</div>