



<div id="content">
    <ul>
        <li>
            <h2>Detalji promocije</h2>
            <a href="#" class="collapse">Collapse</a>

            <div class="innerContent">

                <form id="promotionForm" method="post" action="<?= site_url( 'promotions/edit/'.$promotion->getID() ) ?>" enctype="multipart/form-data">
                    <fieldset class="sectionForm half">

                        <ul>
                            <li>
                                <label>Link:</label>
                                <p style="padding-top: 10px;"><?= ECOM_APP_URL.'info/'.url_title($promotion->getTitle() , 'underscore', TRUE) . '-' . $promotion->getID()?></p>
                            </li>
                            <li>
                                <label>Status:</label>
                                <input type="radio" name="status" value="1"<?= $promotion->getStatus() ? ' checked="checked"' : '' ?> />
                                <small>Aktivna</small>
                                <input type="radio" name="status" value="0"<?= $promotion->getStatus() ? '' : ' checked="checked"' ?> />
                                <small>Neaktivna</small>
                            </li>
                            <?php if(!$this->session->userdata('application_id')):?>
                            <li>
                                <label class="alignLeft">Vendor:</label>
                                <div class="customComboHolder">
                                    <div><?php if( $promotion->getVendor() ){ echo $promotion->getVendor()->getID(); } else { echo 'Odaberi'; }  ?></div>
                                    <select name="vendor_id" id="vendor_id" >
                                        <option value="" >Odaberi</option> 
                                        <?php foreach( $vendors as $vendor ): ?>
                                            <option value="<?= $vendor->getID() ?>" <?php if ( $promotion->getVendor() ){if ($promotion->getVendor()->getID() == $vendor->getID()){echo 'selected';}}?> ><?= $vendor->getID() ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </li>
                            <?php endif; ?>
                            <li>
                                <label>Naslov:</label>
                                <span class="inputField wide"><input type="text" name="title" class="required" value="<?= $promotion->getTitle() ?>" /></span>
                            </li>
                            <li>
                                <label>Upload slika:</label>
                                <?= $plupload ?>
                                <div id="uploader" style="width: 632px;" ><p>Vaš browser nema Flash, Silverlight, Gears, BrowserPlus ili HTML5 podršku.</p></div>
                            </li>
                            <li>
                                <label>Stranica:</label>
                                <?= $tinymce ?>
                                <textarea name="page" id="page"><?= $promotion->getContent() ?></textarea>
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
                            <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('productsGrid', 'promotions/listing') ?>" />
                        </span> 

                        <span class="button save">
                            <input type="button" value="Izmeni" onclick="validateForm('promotionForm')" /> 
                        </span>
                        <?php if( isset($message) ) echo $message; ?>

                    </div>
                </form>

            </div>
        </li>
    </ul>
</div>