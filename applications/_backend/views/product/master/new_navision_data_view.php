<li>
    <h2>Navision podaci proizvoda</h2>
    <a href="#" class="collapse">Collapse</a>
    <div class="innerContent">
        <fieldset class="sectionForm">
            <ul>
                <fieldset class="sectionForm half" style="clear:both">
                <li>
                    <label>ID:</label>
                    <span class="inputField short"><input type="text" name="id" disabled="disabled" value="<?= $product->getID(); ?>" /></span>
                </li>
                <li>
				    <label>EAN ID:</label>
				    <span class="inputField wide"><input type="text" name="manufacturer_id" /></span>
				</li>
                <li>
                    <label>&nbsp;</label>
                    <span class="image"><img src="<?= $product->getBrandImage() ?>" alt="Brand" /></span>
                </li>
                <li>
                    <label>Brend:</label>
                    <span class="inputField wide"><input type="text" name="brand" disabled="disabled" value="<?= $product->getBrandName(); ?>" /></span>
                </li>
                <li>
                    <label>Navision ime:</label>
                    <span class="inputField wide"><input type="text"  disabled="disabled" value="<?= $product->getName() ?>" /></span>
                </li>
                <li>
                    <label>Price:</label>
                    <span class="inputField wide"><input type="text" name="price" disabled="disabled"  value="<?= $product->getPrice() ?>"/></span>
                </li>
                </fieldset>
            </ul>
         </fieldset>
     </div>
</li>