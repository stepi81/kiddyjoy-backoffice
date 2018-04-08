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
                 <form method="post" action="<?= site_url( 'product/specifications/save/' .  $subcategory->getID() ) ?>" id="sprecificationsForm" enctype="multipart/form-data">
                         <ul>
                           <li style="padding-top: 0px; width:200px;">
                                <label><b>Specifikacija:</b></label> 
                                <span class="inputField wide"><input type="text" name="name"></span>
                            </li>
                           <li>
                                <label class="alignLeft">Tip specifikacije:</label> 
                           </li>
                           <li>
                                <div class="customComboHolder">
                                    <div>Filter</div>
                                    <select name="type" id="type" >
                                        <option value="1" selected>Filter</option>
                                        <option value="2">Text</option>
                                    </select>
                                </div>
                           </li>
                           <li style="padding-top: 0px; width:200px;">
                                <label>Pozicija:</label>
                                <span class="inputField short"><input type="text" name="position" value="" class="required only_numbers" /></span>
                           </li>  
                           <li style="padding-top: 0px; width:200px;">
                                <label>Info pozicija:</label>
                                <span class="inputField short"><input type="text" name="position_info" value="" class="only_numbers" /></span>
                           </li>
                           <li style="padding-top: 0px; width:200px;">
                                <label>Klirit pozicija:</label>
                                <span class="inputField short"><input type="text" name="position_klirit" value="" class="only_numbers" /></span>
                           </li>
                           <li style="padding-top: 0px; width:200px;">
                                <input type="radio" name="status" value="1"/>
                                <small>Aktivna</small>
                                <input type="radio" name="status" value="0"/>
                                <small>Neaktivna</small>
                            </li>
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
                                        <input type="button" value="Sačuvaj" onclick="validateForm('sprecificationsForm')" id="saveRsponse" /> 
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
                            