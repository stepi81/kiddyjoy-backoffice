<div id="content">
    <ul>
        <li>
            <h2>Poštanski broj</h2>
            <a href="#" class="collapse">Collapse</a>
            <div class="innerContent">
                <form id="postalCodeForm" method="post" action="<?= site_url( 'settings/postal_codes/edit/'.$postal_code->getPostalCode() ) ?>">
                    <fieldset class="sectionForm">
                        <ul>
                            <li>
                                <label>Poštanski broj:</label>
                                <span class="inputField wide"><input type="text" name="postal_code" readonly="readonly" value="<?= $postal_code->getPostalCode() ?>" /></span>
                            </li>
                            <li>
                                <label>Grad:</label>
                                <span class="inputField wide"><input type="text" name="city" id="city" readonly="readonly" value="<?= $postal_code->getCity() ?>" /></span>  
                            </li>
                            <li>
                                <label>Longitude:</label>
                                <span class="inputField wide"><input type="text" name="longitude" id="longitude" value="<?= $postal_code->getLongitude() ?>" /></span>  
                            </li>
                            <li>
                                <label>Latitude:</label>
                                <span class="inputField wide"><input type="text" name="latitude" id="latitude" value="<?= $postal_code->getLatitude() ?>" /></span>  
                            </li>
                        </ul>
                    </fieldset>
                    <div class="borderTop">
                        <span class="button back">
                            
                            <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('postalCodeGrid', 'settings/postal_codes/listing') ?>" />
                        </span> 
                        <span class="button save">
                            <input type="button" value="Sačuvaj" onclick="validateForm('postalCodeForm')" /> 
                        </span>
                        <?php if( isset($message) ) echo $message; ?>

                    </div>
                </form>

            </div>
        </li>
    </ul>
</div>
