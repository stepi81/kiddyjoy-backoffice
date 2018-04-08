<div id="content">
    <ul>
        <li>
            <h2><?= $grid_title ?></h2>
            <a href="#" class="collapse">Collapse</a>
            <div class="innerContent">
                <fieldset class="sectionForm half"> 
                    <div class="innerContent">
                        <?= $grid ?>
                        <table id="grid" style="display:none"></table>
                    </div>
                </fieldset>
                <fieldset class="sectionForm half" style="padding-left: 30px; padding-top: 20px;">
                    <form method="post" action="<?= site_url( 'product/subcategories/save_price_range/' ) ?>" id="subcategoryPriceRangeForm">
                         <ul>
                            <li style="padding-top: 0px; width:200px;">
                                <label>Rang cene:</label>
                                <span class="inputField short"><input type="text" name="range" value="" class="only_numbers" /></span>
                           </li>
                           <input type="hidden" name="subcategory_id" id="subcategory_id" value="<?= $subcategory->getID() ?>"  />
                           <li>
                                <div>
                                	<span class="button back">
                                    <?php if( $subcategory->getParent() ) { ?>
                                        <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('subcategoryGrid', 'product/subcategories/listing/' . $subcategory->getParent()->getID()) ?>" />
                            		<?php } else { ?>
                            			<input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('subcategoryGrid', 'product/subcategories/groups_listing/' . $subcategory->getCategory()->getID()) ?>" />
                                    <?php } ?>
                                    </span>
                                    <span class="button save">
                                        <input type="button" value="Snimi" onclick="validateForm('subcategoryPriceRangeForm')"/> 
                                    </span>
                                    <br>
                                    <li>
                                        <?php if( isset($message) ) echo $message; ?>
                                    </li>
                                </div>
                            </li>
                        </ul>
                    </form>
                </fieldset>
            </div>
        </li>
    </ul>
</div>
                            