<div id="content">
    <ul>

        <li>

            <h2>Paket proizvodi</h2>
            <a href="#" class="collapse">Collapse</a>

            <div class="innerContent" style="height:600px">
                <fieldset class="sectionForm half">
                    <?= $product_bundles_grid ?>
                    <table id="grid" style="display:none"></table>
                </fieldset> 
                <fieldset class="sectionForm half" style="padding-left: 30px;">
                    <?= $bundles_grid ?>
                    <table id="bundles_grid" style="display:none"></table>
                </fieldset>
                
                <div class="borderTop">

                    <span class="button back">
                            <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('productsGrid', 'products/listing/'.$product->getCategory()->getID()) ?>" />
                    </span> 

                </div>
            </div>
            
        </li>

    </ul>
</div>
