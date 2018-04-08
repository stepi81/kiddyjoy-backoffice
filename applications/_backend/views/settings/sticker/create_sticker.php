<div id="content">
    <ul>
        <li>
            <h2>Novi sticker</h2>
            <a href="#" class="collapse">Collapse</a>

            <div class="innerContent">

                <form id="stickerForm" method="post" action="<?= site_url( 'settings/stickers/save' ) ?>" enctype="multipart/form-data">
                    <fieldset class="sectionForm">

                        <ul>
                            <li>
                                <label>Ime:</label>
                                <span class="inputField wide"><input type="text" name="title" class="required" /></span>
                            </li>
                            <li>
                                <label>Slika:</label>
                                <span class="inputField wide"><input type="text" name="thumb_name" class="required" disabled="true" /></span>
                                <div class="uploadFile">
                                    <input type="file" name="thumb" class="required" />
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