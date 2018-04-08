



<div id="content">
    <ul>
        <li>
            <h2>Izdvojeni proizvodi:: <?= $category->getName() ?></h2>
            <a href="#" class="collapse">Collapse</a>

            <div class="innerContent">

                <form id="promotionForm" method="post" action="<?= site_url( 'vendor_highlights/edit/'.$cat_id. ($subcat_id ? '/'.$subcat_id : '') ) ?>" enctype="multipart/form-data">
                    <fieldset class="sectionForm half">

                        <ul>
                            
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
                            <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('subcategoryGrid', $route) ?>" />
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