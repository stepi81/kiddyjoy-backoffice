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
                </fieldset>
            </div>
            <fieldset class="sectionForm half" style="padding-left: 30px; padding-top: 20px;">
                <form method="post" action="<?= site_url( 'product/subcategories/set_brands/' ) ?>" id="menuBrandsForm" enctype="multipart/form-data">
                    <ul>
                    	<li> 
                            <input type="hidden" name="subcategory_id" id="subcategory_id" value="<?= $subcategory->getID() ?>"  />
                        </li>
                        <li style="padding-top: 0px; width:200px;">
                        	<label>Brendovi:</label>
	                        <?php foreach( $brands as $brand ): ?>
	                        <li>
								<input type="checkbox" value="<?= $brand->getID() ?>" name="brand_list[]" <?= $subcategory->getBrands()->contains($brand) ? ' checked="checked"' : '' ?> />
								<small><?= $brand->getName() ?></small>
	                        </li>
	                        <?php endforeach; ?>
	                    </li>
                        <li style="padding-top:20px">
                            <div>
                                <!--<span class="button back">
                                    <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('categoriesGrid', 'product/subcategories/groups_listing/' . $subcategory->getCategory()->getID()) ?>" />
                                </span>-->
                                <span class="button save">
                                    <input type="button" value="Sačuvaj" onclick="validateForm('menuBrandsForm')" id="saveRsponse" /> 
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
        </li>
    </ul>
</div>                           