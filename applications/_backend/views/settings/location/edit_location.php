<div id="content">
    <ul>
        <li>
            <h2>Detalji lokacije</h2>
            <a href="#" class="collapse">Collapse</a>

            <div class="innerContent">

                <form id="locationForm" method="post" action="<?= site_url( 'settings/locations/edit/'.$location->getID() ) ?>" enctype="multipart/form-data">
                    <fieldset class="sectionForm" style="float: left;">

                        <ul>
                        	<li>
                                <label>&nbsp;</label>
                                <span class="image"><img src="<?= $location->getIconURL() ?>" alt="<?= $location->getAlias() ?>" /></span>
                            </li>
                            <li>
                                <label>Status:</label>
                                <input type="radio" name="public" value="1"<?= $location->getPublic() ? ' checked="checked"' : '' ?>/>
                                <small>Javna</small>
                                <input type="radio" name="public" value="0"<?= $location->getPublic() ? '' : ' checked="checked"' ?>/>
                                <small>Privatna</small>
                            </li>
                            <!--<li>
                                <label>Naziv:</label>
                                <span class="inputField wide"><input type="text" name="name" value="<?= $location->getAlias() ?>" /></span>
                            </li>-->
                            <li>
                                <label>Alias:</label>
                                <span class="inputField wide"><input type="text" name="alias" value="<?= $location->getAlias() ?>" /></span>
                            </li>
                            <li>
                                <label>Email:</label>
                                <span class="inputField wide"><input type="text" name="email" value="<?= $location->getEmail() ?>" /></span>
                            </li>
                            <li>
                                <label>Latitude:</label>
                                <span class="inputField wide"><input type="text" name="latitude" value="<?= $location->getLatitude() ?>" /></span>
                            </li>
                            <li>
                                <label>Longitude:</label>
                                <span class="inputField wide"><input type="text" name="longitude" value="<?= $location->getLongitude() ?>" /></span>
                            </li>
                            <li>
                                <label>&nbsp;</label>
                                <span>&nbsp;</span>
                            </li>
                            <li>
                                <label>Large Icon:</label>
                                <span class="inputField wide"><input type="text" name="image_name" disabled="true" /></span>
                                <div class="uploadFile">
                                    <input type="file" name="icon" />
                                </div>
                            </li>
                            <li>
                                <label>Small Icon:</label>
                                <span class="inputField wide"><input type="text" name="image_name" disabled="true" /></span>
                                <div class="uploadFile">
                                    <input type="file" name="icon_small" />
                                </div>
                            </li>
                            <li>
                                <label>Mobile Icon:</label>
                                <span class="inputField wide"><input type="text" name="image_name" disabled="true" /></span>
                                <div class="uploadFile">
                                    <input type="file" name="icon_mobile" />
                                </div>
                            </li>
                    </fieldset>
                    <fieldset class="sectionForm" style="float: center; width: 300px; padding-left: 50px">
                            <li>
                                <label>Adresa:</label>
                                <span class="textArea"><textarea name="address"><?= $location->getAddress() ?></textarea></span>
                            </li>
                            <li>
                                <label>Telefoni:</label>
                                <span class="textArea"><textarea name="phones"><?= $location->getPhones() ?></textarea></span>
                            </li>
                            <li>
                                <label>Info:</label>
                                <span class="textArea"><textarea name="info"><?= $location->getInfo() ?></textarea></span>
                            </li>
                        </ul>
                    </fieldset>

                    <div class="borderTop">

                        <span class="button back">

                            <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('productsGrid', 'settings/locations/listing') ?>" />
                        </span> 
                        <span class="button save">
                            <input type="button" value="Izmeni" onclick="validateForm('locationForm')" /> 
                        </span>
                        <?php if( isset($message) ) echo $message; ?>

                    </div>
                </form>

            </div>
        </li>
    </ul>
</div>
