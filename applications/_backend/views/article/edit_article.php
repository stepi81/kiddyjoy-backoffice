<div id="content">
    <ul>
        <li>
            <h2>Detalji bloga</h2>
            <a href="#" class="collapse">Collapse</a>

            <div class="innerContent">

                <form id="articleForm" method="post" action="<?= site_url( 'articles/edit/'.$article->getID() ) ?>" enctype="multipart/form-data">
                    <fieldset class="sectionForm half">

                        <ul>
                        	<li>
                                <label>&nbsp;</label>
                                <span class="image"><img src="<?= $article->getThumbURL() ?>" alt="blog" /></span>
                            </li>
                            
                            <li>
                                <label style="width:760px"><a href="<?= $article->getFrontURL() ?>" target="_blank"><?= $article->getFrontURL() ?></a></label>
                            </li>

                            <li>
                                <label>Datum objave:</label>
                                <span class="inputField wide"><input type="text" name="send_date" id="send_date" class="required" value="<?php echo isset($article)? $article->getFormatedDate() : ''?>"/></span>
                            </li>
                            <li>
                            	<label>Status:</label>
                                <input type="radio" name="status" value="1"<?= $article->getStatus() ? ' checked="checked"' : '' ?> />
                                <small>Aktivna</small>
                                <input type="radio" name="status" value="0"<?= $article->getStatus() ? '' : ' checked="checked"' ?> />
                                <small>Neaktivna</small>
                            </li>
                        	<li>
							    <label class="alignLeft">Podkategorija:</label>
							    <div class="customComboHolder">
							        <div><?= $article->getCategory()->getName() ?></div>
							        <select name="category_id" id="category_id">
							            <?php foreach( $article_categories as $category): ?>
							                <option value="<?= $category->getID() ?>" <?= $article->getCategory()->getID() == $category->getID() ? 'selected' : '' ?> ><?= $category->getName() ?></option>
							            <?php endforeach; ?>
							        </select> 
							    </div>
							</li>
                            <li>
                                <label>Naslov:</label>
                                <span class="inputField wide"><input type="text" name="title" class="required" value="<?= $article->getTitle() ?>" /></span>
                            </li>
                            <li>
                                <label>Kratak opis:</label>
                                <span class="textArea"><textarea name="summary"><?= $article->getSummary() ?></textarea></span>
                            </li>
                            <li>
	                            <label>Thumb:</label>
	                            <span class="inputField wide"><input type="text" name="thumb_name" disabled="true" /></span>
                                <div class="uploadFile">
                                    <input type="file" name="thumb" />
                                </div>
                            </li>
                            <li>
	                            <label>Glavna slika:</label>
	                            <span class="inputField wide"><input type="text" name="main_image_name" disabled="true" /></span>
                                <div class="uploadFile">
                                    <input type="file" name="main_image" />
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
                                <textarea name="page" id="page"><?= $article->getContent() ?></textarea>
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
                             <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('articlesGrid', 'articles/listing/'.$article->getCategory()->getID()) ?>" />
                        </span> 
                        <span class="button save">
                            <input type="button" value="Izmeni" onclick="validateForm('articleForm')" /> 
                        </span>
                        <?php if( isset($message) ) echo $message; ?>

                    </div>
                </form>

            </div>
        </li>
    </ul>
</div>