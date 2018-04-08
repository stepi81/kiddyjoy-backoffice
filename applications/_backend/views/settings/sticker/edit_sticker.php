<div id="content">
    <ul>
        <li>
            <h2>Detalji stickera</h2>
            <a href="#" class="collapse">Collapse</a>

            <div class="innerContent">

                <form id="stickerForm" method="post" action="<?= site_url( 'settings/stickers/edit/'.$sticker->getID() ) ?>" enctype="multipart/form-data">
                    <fieldset class="sectionForm">

                        <ul>
                            <li>
                                <label>&nbsp;</label>
                                <span class="image"><img src="<?= $sticker->getImageURL() ?>" alt="KiddyJoy katalog" /></span>
                            </li>
                            <li>
                                <label>Ime:</label>
                                <span class="inputField wide"><input type="text" name="title" class="required" value="<?= $sticker->getName() ?>" /></span>
                            </li>
                            <li>
                                <label>Slika:</label>
                                <span class="inputField wide"><input type="text" name="thumb_name" disabled="true" /></span>
                                <div class="uploadFile">
                                    <input type="file" name="thumb" />
                                </div>
                           </li>     
                        </ul>

                    </fieldset>

                    <div class="borderTop">
                        <span class="button back">
                            <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('productsGrid', 'settings/stickers/listing') ?>" />
                        </span> 
                        <span class="button save">
                            <input type="button" value="Sačuvaj" onclick="validateForm('stickerForm')" /> 
                        </span>
                        <?php if( isset($message) ) echo $message; ?>

                    </div>
                </form>

            </div>
        </li>
    </ul>
</div>
