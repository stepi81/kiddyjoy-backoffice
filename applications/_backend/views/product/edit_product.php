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

                        <li>
                       		<label>ID:</label>
                            <span class="inputField short"><input type="text" name="id" disabled="disabled" value="<?= $product->getID(); ?>" /></span>
                        </li>
                        <li>
                            <label>Dobavljač:</label>
                            <span class="inputField wide"><input type="text" name="vendor" value="<?= $product->getVendor(); ?>" /></span>
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
                            <label>Price:</label>
                            <span class="inputField wide"><input type="text" name="price" disabled="disabled"  value="<?= $product->getPrice() ?>"/></span>
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
                                    
                                        if ($subcategory->getParent () == NULL && $subcategory->getCategory()->getID() == $product-->getCategory()->getID() ){ ?>
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
                                  
                                  <!-- This part nead to bee uncomented if its gonna be problem with group subcategories. Some product have set group
                                      even if that group have subcategories... For exsample some product have set laptop (without selecting subcategory personal/busines)
                                      -->
                                          
                                  <!--         
                                     <?php
                                         if (count($product->getSubcategory()->getChildren())) {
                                              $subcategories = $product->getSubcategory()->getChildren();
                                     ?>
                                     <?php foreach( $subcategories as $subcategory): ?>
                                           <option value="<?= $subcategory->getID() ?>" ><?= $subcategory->getName() ?></option>
                                     <?php endforeach; 
                                        }
                                     ?>  -->
                                          
                                </select>
                            </div>
                        </li> 
                        <li>
                            <label>Ime:</label>
                            <span class="inputField wide"><input type="text" name="name" value="<?= htmlspecialchars($product->getName(), ENT_QUOTES) ?>"/></span>
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
                            <label>Datum postavljanja:</label>
                            <span class="inputField normal"><input type="text" disabled="disabled" value="<?= $product->getReleaseDate() != NULL ? $product->getFormatedReleaseDate() :'' ?>" /></span>
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
                            <label>Status:</label>
                            <input type="radio" name="status" value="1"<?= $product->getStatus() ? ' checked="checked"' : '' ?>/>
                            <small>Aktivan</small>
                            <input type="radio" name="status" value="0"<?= $product->getStatus() ? '' : ' checked="checked"' ?>/>
                            <small>Neaktivan</small>
                        </li>
                        </fieldset>
                        <fieldset class="sectionForm half" style="padding-left: 30px;">
                        <li>
                            <label>Lista cena:</label>
                            <span class="textArea"><textarea name="price_list" disabled="disabled"><?= $product->getPriceList() ?></textarea></span>
                        </li>
                        <li>
                            <label>Sticker:</label>
                            <select multiple="multiple" id="stickers" name="stickers[]">
                                  <?php foreach ($stickers as $sticker){ ?>
                                     <option value="<?= $sticker->getID() ?>" <?php foreach ($product->getStickers() as $x){ if ($x->getID() == $sticker->getID()) { echo  'selected' ; }}?>><?=$sticker->getName()?></option>
                                  <?php } ?>
                            </select>
                        </li>
                        <br>
                        </fieldset>
                    </ul>
                 </fieldset>
             </div>
        </li>
        <li>
            <h2>Ostalo</h2>
            <a href="#" class="collapse">Collapse</a>
            <div class="innerContent">
                <form id="productForm" method="post" action="<?= site_url( 'products/edit/'.$product->getID() ) ?>" enctype="multipart/form-data">
                <fieldset class="sectionForm">
                    <ul>
                        <fieldset class="sectionForm half" style="clear:both">
                            <li>
                                <label>Ostalo:</label>
                                <?= $tinymce ?>
                                <textarea name="other" id="other"><?= $product->getOther() ?></textarea>
                            </li>
                        </fieldset>
                    </ul>
                 </fieldset>
            </div>
        </li>                
            <li>                      
                <h2>Specifikacije</h2>
                <a href="#" class="collapse">Collapse</a>
                  <div class="innerContent">
                      <fieldset class="sectionForm">
                      <ul>
                      <fieldset class="sectionForm half" style="clear:both">
                            <?php
                            $specifications_num = floor (count ($specifications)/2);
                            $i = 0;
                            foreach ($specifications as $specification): 
                                $i++;
                                if ($i == $specifications_num + 1 ) { ?> 
                            </fieldset>
                            <fieldset class="sectionForm half" style="padding-left: 30px;">
                            <?php } ?>
                            <?php if ($specification->getTypeID()==2){ ?>
                                    <li>
                                        <label><?= $specification -> getName() ?>:</label>
                                        <span class="textArea">
                                                <textarea name="<?= $specification -> getID() . 't' ?>" ><?php
                                                    foreach ($textFilter as $z) {
                                                        if ($z -> getSpecification()->getID() == $specification -> getID()) {
                                                            echo $z -> getDescription();
                                                        };
                                                    }
                                                ?></textarea>
                                        </span>
                                    </li>
                            <?php
                                goto a;
                                }
                            ?>
                            <li>      
                                <label class="alignLeft"><?= $specification->getName()?>:</label>
                                <div class="customComboHolder">
                                    <div>
                                        <?php 
                                            foreach ($filters as $z){
                                               if ( $z->getSpecification()->getID() == $specification->getID()){
                                                    $tittle = $z->getName();
                                                    break;
                                               }else{
                                                    $tittle = 'Odaberite filter';
                                               }
                                            } 
                                            isset ($tittle) ? '' : $tittle = 'Odaberite filter';
                                            echo $tittle;  
                                        ?>
                                    </div>
                                    <select name="<?= $specification->getID() . 's'?>" id="<?= $specification->getID() . 's'?>" >
                                        <option value="" selected="selected">Odaberite filter</option>
                                        <?php foreach( $specification->getFilters() as $filter ){ ?>
                                            <option value="<?= $filter->getID() ?>" <?php 
                                                foreach ($filters as $x){
                                                     if ($x->getID() == $filter->getID()) {
                                                          echo  'selected' ; 
                                                     }
                                                }
                                                ?>><?= $filter->getName() ?>
                                        </option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </li>
                            
                            <?php 
                                a:
                                endforeach; 
                            ?> 
                            </fieldset>
                            
                            
                        </ul>
                    </fieldset>
                    <div class="borderTop">
                        <span class="button back">
                            <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('productsGrid', 'products/listing/' . $product->getCategory()->getID()) ?>" />
                        </span> 
                        <span class="button save">
                            <input type="button" value="Izmeni" onclick="validateForm('productForm')" /> 
                        </span>
                        <?php if( isset($message) ) echo $message; ?>
                    </div>
                
            </div>
        </li>
        </form> 
    </ul>
</div>