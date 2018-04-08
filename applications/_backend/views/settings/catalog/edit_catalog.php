<div id="content">
    <ul>
        <li>
            <h2>Detalji kataloga</h2>
            <a href="#" class="collapse">Collapse</a>

            <div class="innerContent">

                <form id="catalogForm" method="post" action="<?= site_url( 'settings/catalogs/edit/'.$catalog->getID() ) ?>" enctype="multipart/form-data">
                    <fieldset class="sectionForm">

                        <ul>
                            <li>
                                <label>&nbsp;</label>
                                <span class="image"><img src="<?= $catalog->getImage() ?>" alt="KiddyJoy katalog" /></span>
                            </li>
                            <li>
                                <label>Datum objave:</label>
                                <span class="inputField short"><input type="text" disabled="true" value="<?= $catalog->getFormatedDate() ?>" /></span>
                            </li>
                            <li>
                                <label>Naslov:</label>
                                <span class="inputField wide"><input type="text" name="title" class="required" value="<?= $catalog->getTitle() ?>" /></span>
                            </li>
                            <li>
                                <label>Edicija:</label>
                                <span class="inputField wide"><input type="text" name="edition" class="required" value="<?= $catalog->getEdition() ?>" /></span>
                            </li>
                            <li>
                                <label>Status:</label>
                                <input type="radio" name="status" value="1"<?= $catalog->getStatus() ? ' checked="checked"' : '' ?>/>
                                <small>Aktivan</small>
                                <input type="radio" name="status" value="0"<?= $catalog->getStatus() ? '' : ' checked="checked"' ?>/>
                                <small>Neaktivan</small>
                            </li>
                            <li>
                                <label>Slika:</label>
                                <span class="inputField wide"><input type="text" name="image_name" disabled="true" /></span>
                                <div class="uploadFile">
                                    <input type="file" name="image" />
                                </div>
                           </li>     
                           <li>
                                <label>Ime katalog fajla:</label>
                                <span class="inputField wide"><input type="text" name="catalog_name" class="required" value="<?= $catalog->getPDFName() ?>"/></span>
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