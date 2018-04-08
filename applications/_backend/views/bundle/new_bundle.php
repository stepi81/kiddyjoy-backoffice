<div id="content">
    <ul>
        <li>
            <h2>Novi paket</h2>
            <a href="#" class="collapse">Collapse</a>

            <div class="innerContent">

                <form id="bundleForm" method="post" action="<?= site_url( 'bundles/save' ) ?>">
                    <fieldset class="sectionForm">

                        <ul>
                            <li>
                                <label>Naziv:</label>
                                <span class="inputField wide"><input type="text" name="bundle_name" class="required" /></span>
                            </li>
                            <li>
                                <label>Cena:</label>
                                <span class="inputField wide"><input type="text" name="bundle_price" /></span>
                            </li>
                        </ul>

                    </fieldset>

                    <div class="borderTop">

                        <span class="button back">
                            <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('productsGrid', 'bundles/listing') ?>" />
                        </span> 
                        <span class="button save">
                            <input type="button" value="Sačuvaj" onclick="validateForm('bundleForm')" /> 
                        </span>
                        <?php if( isset($message) ) echo $message; ?>
                    </div>
                </form>

            </div>
        </li>
    </ul>
</div>