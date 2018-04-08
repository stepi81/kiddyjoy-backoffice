<div id="content">
    <ul>
        <li>
            <h2>Detalji preorder stranice</h2>
            <a href="#" class="collapse">Collapse</a>

            <div class="innerContent">

                <form id="preorderForm" method="post" action="<?= site_url( 'preorder_pages/edit/'.$preorder->getID() ) ?>" enctype="multipart/form-data">
                    <fieldset class="sectionForm half">

                        <ul>
                        	<li>
                                <label>&nbsp;</label>
                                <span class="image"><img src="<?= $preorder->getThumbURL() ?>" alt="KiddyJoy vesti" /></span>
                            </li>
                            
                            

                            <li>
                                <label>Datum isteka:</label>
                                <span class="inputField wide"><input type="text" name="send_date" id="send_date" class="required" value="<?php echo isset($preorder)? $preorder->getFormatedDate() : ''?>"/></span>
                            </li>
                            <li>
                            	<label>Status:</label>
                                <input type="radio" name="status" value="1"<?= $preorder->getStatus() ? ' checked="checked"' : '' ?> />
                                <small>Aktivna</small>
                                <input type="radio" name="status" value="0"<?= $preorder->getStatus() ? '' : ' checked="checked"' ?> />
                                <small>Neaktivna</small>
                            </li>
                            <li>
                                <label>Naslov:</label>
                                <span class="inputField wide"><input type="text" name="title" class="required" value="<?= $preorder->getTitle() ?>" /></span>
                            </li>
                            <li>
                                <label>Kratak opis:</label>
                                <span class="textArea"><textarea name="summary" class="required"><?= $preorder->getSummary() ?></textarea></span>
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
                                <textarea name="page" id="page"><?= $preorder->getPage() ?></textarea>
                            </li>
                        </ul>

                    </fieldset>
                    
                    <fieldset class="sectionForm half">
                    <ul>
                    <div class="product_ids_holder">
                        <?php if(!empty($product_ids)):?>
                            <?php foreach($product_ids as $key=>$val):?>
                            <li class="element-<?= $key + 1 ?>">
                                <label>ID ili ime proizvoda:</label>
                                <span class="inputField short">
                                    <input type="text" id="product_id-<?= $key + 1 ?>" name="product_id[]" value="<?=$val?>">
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
                            <label>ID ili ime proizvoda:</label>
                            <span class="inputField short">
                                <input type="text" id="product_id-1" name="product_id[]">
                            </span>
                            <img class="add_new add_button-1" onclick="add_preorder_product(this)" alt="add_new" src="<?= layout_url('flexigrid/add.png')?>">
                        </li>
                        <?php endif?>
                    </div>
                    </ul>
                    </fieldset>

                    <div class="borderTop">

                        <span class="button back">
                             <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('productsGrid', 'preorder_pages/listing/'.$preorder->getPreorderTypeID()) ?>" />
                        </span> 
                        <span class="button save">
                            <input type="button" value="Izmeni" onclick="validateForm('preorderForm')" /> 
                        </span>
                        <?php if( isset($message) ) echo $message; ?>

                    </div>
                </form>

            </div>
        </li>
    </ul>
</div>