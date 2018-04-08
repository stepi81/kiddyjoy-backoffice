<div id="content">
    <ul>
        <li>
            <h2>Detalji socialne mreže</h2>
            <a href="#" class="collapse">Collapse</a>

            <div class="innerContent">

                <form id="socialForm" method="post" action="<?= site_url( 'settings/social_networks/edit/'.$social_network->getID() ) ?>" enctype="multipart/form-data">
                    <fieldset class="sectionForm">

                        <ul>
                            <li>
                                <label>&nbsp;</label>
                                <span class="image"><img src="<?= $social_network->getImageURL() ?>" alt="KiddyJoy katalog" /></span>
                            </li>
                            <li>
                                <label>Ime:</label>
                                <span class="inputField wide"><input type="text" name="title" class="required" value="<?= $social_network->getName() ?>" /></span>
                            </li>
                            <li>
                                <label>URL:</label>
                                <span class="inputField wide"><input type="text" name="social_url" class="required" value="<?= $social_network->getSocialURL() ?>" /></span>
                            </li>
                            <li>
                                <label>Status:</label>
                                <input type="radio" name="status" value="1"<?= $social_network->getStatus() ? ' checked="checked"' : '' ?> />
                                <small>Aktivna</small>
                                <input type="radio" name="status" value="0"<?= $social_network->getStatus() ? '' : ' checked="checked"' ?> />
                                <small>Neaktivna</small>
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
