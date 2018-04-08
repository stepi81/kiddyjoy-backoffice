<li>
    <label>ID:</label>
    <span class="inputField short"><input type="text" name="id" disabled="disabled" value="<?= $product->getID(); ?>" /></span>
</li>
<li>
    <label>Dobavljač:</label>
    <span class="inputField wide"><input type="text" name="vendor" value="<?= $product->getVendor(); ?>" /></span>
</li>
<li>
    <label>Ime:</label>
    <span class="inputField wide"><input type="text" name="name" value="<?= htmlspecialchars($product->getName(), ENT_QUOTES) ?>"/></span>
</li>
<li>
    <label>Cena:</label>
    <span class="inputField wide"><input type="text" name="price" class="required"  value="<?= $product->getPrice() ?>"/></span>
</li>
<li>
    <label>Stara cena:</label>
    <span class="inputField wide"><input type="text" name="old_price"  value="<?= $product->getOldPrice() ?>"/></span>
</li>
<li>
    <label>PDV:</label>
    <span class="inputField wide"><input type="text" name="vat" class="required"  value="<?= $product->getVat() ?>"/></span>
</li>
<li>
	<label class="alignLeft">Brend:</label>
    <div class="customComboHolder">
        <div><?php is_object($product->getBrand()) ? $brand = $product->getBrand()->getName():''; echo isset($brand) ? $brand : 'Odaberite brend' ?></div>
        <select name="brand" id="brand" class="required">
        	<option value="" selected>Sellect</option> 
            <?php foreach( $brands as $brand): ?>
                <option value="<?= $brand->getID() ?>" <?php if (is_object($product->getBrand())) { if ($product->getBrand()->getID() == $brand->getID()) { echo 'selected'; } } ?> ><?= $brand->getName() ?></option>
            <?php endforeach; ?>
        </select> 
    </div>
</li>
<li>
    <label class="alignLeft">Kategorija:</label>
    <div class="customComboHolder">
        <div>
            <?php is_object($product->getCategory())? $cat=$product->getCategory()->getName():''; echo isset($cat)? $cat : 'Odaberite kategoriju' ?>
        </div>
        <select name="category" id="category" class="required">
            <option value="" <?php echo isset($cat) ? "" : 'selected'; ?> ><?= 'Odaberite kategoriju' ?></option>
            <?php foreach( $categories as $category): ?>
                <option value="<?= $category -> getID() ?>" 
                <?php if (is_object($product->getCategory())) {
                        if ($product->getCategory()->getID() == $category -> getID()) {
                            echo 'selected';
                        }
                    }
                ?> > <?= $category -> getName() ?>
                </option>
            <?php endforeach; ?>
        </select> 
    </div>
</li>
<li>
    <label class="alignLeft">Grupa:</label>
    <div class="customComboHolder">
        <div class="selected_group">
            <?php if ($product->getSubcategory()->getParent()== NULL) {
                        echo $product->getSubcategory()->getName(); 
                  } else {
                       foreach( $subcategories as $subcategory) {
                           if ($subcategory->getID() == $product->getSubcategory()->getParent()->getID()){
                               echo $subcategory->getName(); 
                           }
                       }
                  }
              ?>
        </div>
        <select name="group" id="group" class="required">
            <?php foreach( $subcategories as $subcategory): 
            
                if ($subcategory->getParent () == NULL && $subcategory->getCategory()->getID() == $product->getCategory()->getID() ){ ?>
                <option value="<?= $subcategory->getID() ?>" 
                    <?php if ($product->getSubcategory()->getParent()== NULL) {
                              if ($product->getSubcategory()->getID() == $subcategory->getID()) {
                                  echo 'selected';
                              }
                          } else {
                              if ($subcategory->getID() == $product->getSubcategory()->getParent()->getID()){
                                echo 'selected';
                              } 
                          }
                          ?> >
                    <?= $subcategory->getName() ?></option>
            <?php } endforeach; ?>
        </select>
    </div>
</li> 
<li>
    <label class="alignLeft">Podkategorija:</label>
    <div class="customComboHolder">
        <div class="selected_subcategory">
            <?php if ($product->getSubcategory()->getParent() != NULL ) {
                       foreach( $subcategories as $subcategory ){  
                           if ($product->getSubcategory()->getID() == $subcategory->getID()){
                               echo $subcategory->getName(); 
                           }
                       }    
                   } else {
                       echo "Sellect" ;    
                   }
            ?>
        </div>
        <select name="subcategory" id="subcategory" >
            <?= is_object($product->getSubcategory()->getParent()) ? "":'<option value="" selected>Sellect</option>' ?>
            <?php if (is_object($product->getSubcategory()->getParent())){ ?>    
                    <?php foreach( $subcategories as $subcategory ):
                          if( $product->getSubcategory()->getParent() == $subcategory->getParent()){  ?>
                              <option value="<?= $subcategory->getID() ?>" 
                              <?php if ($product->getSubcategory()->getParent() != NULL) {
                                      if ($product->getSubcategory()->getID() == $subcategory->getID()){
                                         echo 'selected';
                                      }
                                  }?> ><?= $subcategory->getName() ?></option>
                    <?php } 
                          endforeach; 
                  } ?>
                  
                
             <?php
                 if (count($product->getSubcategory()->getChildren())) {
                      $subcategories = $product->getSubcategory()->getChildren();
             ?>
             <?php foreach( $subcategories as $subcategory): ?>
                   <option value="<?= $subcategory->getID() ?>" ><?= $subcategory->getName() ?></option>
             <?php endforeach; 
                }
             ?> 
                  
        </select>
    </div>
</li> 
<li>
    <label class="alignLeft">Garancija:</label>
    <div class="customComboHolder">
        <div>
            <?php is_object($product->getWarranty())? $war=$product->getWarranty()->getName():''; echo isset($war)? $war : 'Odaberite garanciju' ?>
        </div>
        <select name="warranty" id="warranty" >
            <option value="" <?php echo isset($war) ? "" : 'selected'; ?> ><?= 'Odaberite garanciju' ?></option>
            <?php foreach( $warranties as $warranty ): ?>
                <option value="<?= $warranty->getID() ?>" 
                    <?php if (is_object($product->getWarranty())) {
                             if ($product->getWarranty()->getID() == $warranty->getID()) {
                                 echo 'selected';
                             }
                          }
                    ?> > <?= $warranty -> getName() ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
</li>
<li>
	<label class="alignLeft">Sticker:</label>
    <div class="customComboHolder">
        <div><?php is_object($product->getSticker()) ? $sticker = $product->getSticker()->getName():''; echo isset($sticker) ? $sticker : 'Odaberite sticker' ?></div>
        <select name="sticker" id="sticker">
        	<option value="" selected>Sellect</option> 
            <?php foreach( $stickers as $sticker): ?>
                <option value="<?= $sticker->getID() ?>" <?php if (is_object($product->getSticker())) { if ($product->getSticker()->getID() == $sticker->getID()) { echo 'selected'; } } ?> ><?= $sticker->getName() ?></option>
            <?php endforeach; ?>
        </select> 
    </div>
</li>
<li>
    <label>Datum postavljanja:</label>
    <span class="inputField normal"><input type="text" disabled="disabled" value="<?= $product->getReleaseDate() != NULL ? $product->getFormatedReleaseDate() :'' ?>" /></span>
</li>
</fieldset>
<fieldset class="sectionForm half" style="padding-left: 30px;">
	<li>
    <label>Prodato:</label>
    <span class="inputField normal"><input type="text" disabled="disabled" value="<?= $product->getStatisticSold(); ?>" /></span>
</li>
<li>
    <label>Posete:</label>
    <span class="inputField normal"><input type="text" disabled="disabled" value="<?= $product->getStatisticVisits() ?>" /></span>
</li>

<li>
    <label>Ocene:</label>
    <span class="inputField normal"><input type="text" disabled="disabled" value="<?= $product->getStatisticVotes(); ?>" /></span>
</li>
<li>
    <label>Rejting:</label>
    <span class="inputField normal"><input type="text" disabled="disabled" value="<?= $product->getStatisticRating() ?>" /></span>
</li>
<li>
    <label>Akcija:</label>
    <input type="radio" name="promotion" value="1"<?= $product->getPromotion() ? ' checked="checked"' : '' ?>/>
    <small>Da</small>
    <input type="radio" name="promotion" value="0"<?= $product->getPromotion() ? '' : ' checked="checked"' ?>/>
    <small>Ne</small>
</li>
<!--<li>
    <label>Rasprodaja:</label>
    <input type="radio" name="sale" value="1"<?= $product->getSale() ? ' checked="checked"' : '' ?>/>
    <small>Da</small>
    <input type="radio" name="sale" value="0"<?= $product->getSale() ? '' : ' checked="checked"' ?>/>
    <small>Ne</small>
</li>
<li>
    <label>Outlet:</label>
    <input type="radio" name="outlet" value="1"<?= $product->getOutlet() ? ' checked="checked"' : '' ?>/>
    <small>Da</small>
    <input type="radio" name="outlet" value="0"<?= $product->getOutlet() ? '' : ' checked="checked"' ?>/>
    <small>Ne</small>
</li>-->
<li>
    <label>Preporuka:</label>
    <input type="radio" name="featured" value="1" <?= $product->getFeatured() == 1 ? ' checked="checked"' : '' ?>/>
    <small>Da</small>
    <input type="radio" name="featured" value="0" <?= $product->getFeatured() == 0 ? ' checked="checked"' : '' ?>/>
    <small>Ne</small>
</li>
<li>
    <label>Status</label>
    <input type="radio" name="status" value="1"<?= $product->getStatus() ? ' checked="checked"' : '' ?>/>
    <small>Aktivan</small>
    <input type="radio" name="status" value="0"<?= $product->getStatus() ? '' : ' checked="checked"' ?>/>
    <small>Neaktivan</small>
</li>