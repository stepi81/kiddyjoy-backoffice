<div id="content">
    <ul>
        <li>
            <h2>Novi katalog</h2>
            <a href="#" class="collapse">Collapse</a>

            <div class="innerContent">

                <form id="catalogForm" method="post" action="<?= site_url( 'settings/catalogs/save' ) ?>" enctype="multipart/form-data">
                    <fieldset class="sectionForm">

                        <ul>
                            <li>
                                <label>Naslov:</label>
                                <span class="inputField wide"><input type="text" name="title" class="required" /></span>
                            </li>
                            <li>
                                <label>Edicija:</label>
                                <span class="inputField wide"><input type="text" name="edition" class="required" /></span>
                            </li>
                            <li>
                                <label>Status:</label>
                                <input type="radio" name="status" value="1"/>
                                <small>Aktivan</small>
                                <input type="radio" name="status" value="0"/>
                                <small>Neaktivan</small>
                            </li>
                            <li>
                                <label>Slika:</label>
                                <span class="inputField wide"><input type="text" name="image_name" class="required" disabled="true" /></span>
                                <div class="uploadFile">
                                    <input type="file" name="image" class="required" />
                                </div>
                           </li>   
                           <li>
                                <label>Ime katalog fajla:</label>
                                <span class="inputField wide"><input type="text" name="catalog_name" class="required" /></span>
                           </li>  
                        </ul>

                    </fieldset>

                    <div class="borderTop">
                        <span class="button back">
                             <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('productsGrid', 'settings/catalogs/listing') ?>" />
                        </span> 
                        <span class="button save">
                            <input type="button" value="Sačuvaj" onclick="validateForm('catalogForm')" /> 
                        </span>
                        <?php if( isset($message) ) echo $message; ?>

                    </div>
                </form>

            </div>
        </li>
    </ul>
</div>