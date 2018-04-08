<div id="content">
    <ul>
        <li>
            <h2>Novi vendor</h2>
            <a href="#" class="collapse">Collapse</a>

            <div class="innerContent">

                <form id="vendorForm" method="post" action="<?= site_url( 'vendors/save' ) ?>" enctype="multipart/form-data">
                    <fieldset class="sectionForm">

                        <ul>
                            <li>
                                <label>Naziv:</label>
                                <span class="inputField wide"><input type="text" name="vendor_name" class="required" /></span>
                            </li>
                        </ul>

                    </fieldset>

                    <div class="borderTop">

                        <span class="button back">
                            <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('productsGrid', 'vendors/listing') ?>" />
                       
                        </span> 
                        <span class="button save">
                            <input type="button" value="Sačuvaj" onclick="validateForm('vendorForm')" /> 
                        </span>
                        <?php if( isset($message) ) echo $message; ?>

                    </div>
                </form>

            </div>
        </li>
    </ul>
</div>