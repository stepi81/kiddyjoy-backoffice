<div id="content">
    <ul>
        <li>
            <h2>Nova socialna mreža</h2>
            <a href="#" class="collapse">Collapse</a>

            <div class="innerContent">

                <form id="socialForm" method="post" action="<?= site_url( 'settings/social_networks/save' ) ?>" enctype="multipart/form-data">
                    <fieldset class="sectionForm">

                        <ul>
                            <li>
                                <label>Ime:</label>
                                <span class="inputField wide"><input type="text" name="title" class="required" /></span>
                            </li>
                            <li>
                                <label>URL:</label>
                                <span class="inputField wide"><input type="text" name="social_url" class="required" /></span>
                            </li>
                            <li>
                                <label>Status:</label>
                                <input type="radio" name="status" value="1" />
                                <small>Aktivna</small>
                                <input type="radio" name="status" value="0" />
                                <small>Neaktivna</small>
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
                            <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('socialGrid', 'settings/social_networks/listing') ?>" />
                        </span> 
                        <span class="button save">
                            <input type="button" value="Sačuvaj" onclick="validateForm('socialForm')" /> 
                        </span>
                        <?php if( isset($message) ) echo $message; ?>

                    </div>
                </form>

            </div>
        </li>
    </ul>
</div>