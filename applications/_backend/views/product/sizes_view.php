<div id="content">
    <ul>

        <li>

            <h2>Boje proizvoda</h2>
            <a href="#" class="collapse">Collapse</a>

            <div class="innerContent" style="height:600px">
                <fieldset class="sectionForm half">
                    <?= $grid ?>
                    <table id="grid" style="display:none"></table>
                </fieldset> 
                
                <form id="colorForm" method="post" action="<?= site_url( 'products/add_product_size') ?>" >
	                <fieldset class="sectionForm half" style="padding-left: 30px;">
	                    <ul>
	                    	<li>
                                <input type="hidden" name="product_id" id="product_id" value="<?= $product->getID() ?>"  />  
                            </li>
	                    	<li>
	                        	<label class="alignLeft">Veličina:</label>
	                            <div class="customComboHolder">
	                                <div>Odaberi veličinu</div>
	                                <select name="size" id="size" class="required">
	                                	<option value="" selected>Sellect</option> 
	                                    <?php foreach( $sizes as $size): ?>
	                                        <option value="<?= $size->getID() ?>"><?= $size->getName() ?></option>
	                                    <?php endforeach; ?>
	                                </select> 
	                            </div>
	                        </li>
	                        <li>
	                            <label>Pozicija:</label>
	                            <span class="inputField short"><input type="text" name="position" id="position" class="only_numbers"/></span>
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
                </form>
                
                <div class="borderTop">

                    <span class="button back">
                        <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('productsGrid', 'products/listing/'.$product->getCategory()->getID()) ?>" />
                    </span> 
                    <span class="button save">
                        <input type="button" value="Sačuvaj" onclick="validateForm('colorForm')" /> 
                    </span>

                </div>
            </div>
            
        </li>

    </ul>
</div>