<div id="content">
    <ul>
        <form id="productForm" method="post" action="<?= site_url( 'products/edit/'.$product->getID() ) ?>" enctype="multipart/form-data">
	        <li>
	            <h2>Detalji proizvoda</h2>
	            <a href="#" class="collapse">Collapse</a>
	              <div class="innerContent">
	                  <fieldset class="sectionForm">
	                    <ul>
	                        <fieldset class="sectionForm half" style="clear:both">
	                            <?= $this->load->view( 'product/master/product_details_view' ) ?>
	                            <br>
	                        </fieldset>
	                    </ul>
	                 </fieldset>
	             </div>
	        </li>
	        <?= $this->load->view( 'product/master/others_view' ) ?>
	        <?= $this->load->view( 'product/master/specification_view' ) ?>
	</ul>
                </fieldset>
                <div class="borderTop">
                    <span class="button back">
                        <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('productsGrid', 'products/listing/' . $product->getCategory()->getID()) ?>" />
                    </span>
                    <span class="button save">
                        <input type="button" value="Izmeni" onclick="validateForm('productForm')" />
                    </span>
                    <span class="button save">
                        <a style="position:relative;top:8px;left:52px;font-size:14px" href="<?= site_url( 'products/clone_product_details/'.$product->getID()) ?>">Kloniraj</a>
                    </span>
                    <?php if( isset($message) ) echo $message; ?>
                </div>
            </div>
        </li>
        </form>
    </ul>
</div>