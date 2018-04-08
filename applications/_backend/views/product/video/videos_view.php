<div id="content">
    <ul>

        <li>

            <h2>Video</h2>
            <a href="#" class="collapse">Collapse</a>

            <div class="innerContent" style="height:600px">
                <fieldset class="sectionForm half">
                    <?= $grid ?>
                    <table id="grid" style="display:none"></table>
                </fieldset>
                <fieldset class="sectionForm half" style="padding-left: 30px;">
                    <form id="productVideoForm" method="post" action="<?= site_url( 'product/videos/insert/'.$product->getID() ) ?>">
                        <li>
                            <label style="padding-left: 5px;">Naziv Videa:</label>
                            <span class="inputField wide"><input type="text" name="video_name" class="required" value="" /></span>
                        </li>
                        <li>
                            <label style="padding-left: 5px;">ID Videa:</label>
                            <span class="inputField wide"><input type="text" name="video_id" class="required" value="" /></span>
                        </li>
                        <li>
                            <label style="padding-left: 5px;">Pozicija:</label>
                            <span class="inputField wide"><input type="text" name="position" value="" /></span>
                        </li>
                        <div>
                            <span class="button back">
                                 <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('productsGrid', 'products/listing/'.$product->getCategory()->getID()) ?>" />
                            </span>  
                            <span class="button save">
                                <input type="button" value="Unesi" onclick="validateForm('productVideoForm')" /> 
                            </span>
                        </div> 
                    </form>
                </fieldset>   
            </div>
            
        </li>

    </ul>
</div>