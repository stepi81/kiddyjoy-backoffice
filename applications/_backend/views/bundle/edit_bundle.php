<div id="content">
    <ul>
        <li>
            <h2>Detalji paketa</h2>
            <a href="#" class="collapse">Collapse</a>

            <div class="innerContent">

                <form id="bundleForm" method="post" action="<?= site_url( 'bundles/edit/'.$bundle->getID() ) ?>">
                    <fieldset class="sectionForm">

                        <ul>
                            <li>
                                <label>Naziv:</label>
                                <span class="inputField wide"><input type="text" name="bundle_name" class="required" value="<?= $bundle->getName() ?>" /></span>
                            </li>
                             <li>
                                <label>Cena:</label>
                                <span class="inputField wide"><input type="text" name="bundle_price" value="<?= $bundle->getPrice() ?>" /></span>
                            </li>
                        </ul>

                    </fieldset>

                    <div class="borderTop">

                        <span class="button back">
                            
                            <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('productsGrid', 'bundles/listing') ?>" />
                        </span> 
                        <span class="button save">
                            <input type="button" value="Izmeni" onclick="validateForm('bundleForm')" /> 
                        </span>
                        <?php if( isset($message) ) echo $message; ?>

                    </div>
                </form>

            </div>
        </li>
    </ul>
    <ul>

        <li>

            <h2>Paket proizvodi</h2>
            <a href="#" class="collapse">Collapse</a>

            <div class="innerContent" style="height:600px">
                <fieldset class="sectionForm half">
                    <?= $grid ?>
                    <table id="grid" style="display:none"></table>
                </fieldset>
                <fieldset class="sectionForm half" style="padding-left: 30px;">
                    <form id="bundleProductForm" method="post" action="<?= site_url( 'bundles/insert_product/'.$bundle->getID() ) ?>">
                    	<ul>
	                        <li>
	                            <label>ID Proizvoda:</label>
	                            <span class="inputField short"><input type="text" id="product_id" name="product_id" class="required" value="" /></span>
	                        </li>
	                        <li>
	                            <label>Fixna cena:</label>
	                            <span class="inputField short"><input type="text" name="fix_price" /></span>
	                        </li>
	                        <li>
	                            <label>Popust( % ):</label>
	                            <span class="inputField short"><input type="text" name="discount" /></span>
	                        </li>
	                        <li>
	                            <label>&nbsp;</label>
	                            <span class="button save">
                                	<input type="button" value="Unesi" onclick="validateForm('bundleProductForm')" /> 
                            	</span>
	                        </li>
                        </ul>
                    </form>
                </fieldset>   
            </div>
            
        </li>

    </ul>
</div>
