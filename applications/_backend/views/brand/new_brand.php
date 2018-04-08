<div id="content">
    <ul>
        <li>
            <h2>Novi proizvođač</h2>
            <a href="#" class="collapse">Collapse</a>

            <div class="innerContent">

                <form id="brandForm" method="post" action="<?= site_url( 'brands/save' ) ?>" enctype="multipart/form-data">
                    <fieldset class="sectionForm">

                        <ul>
                            <li>
                                <label>Naziv:</label>
                                <span class="inputField wide"><input type="text" name="brand_name" class="required" /></span>
                            </li>
                            <li>
                                <label>Pozicija:</label>
                                <span class="inputField short"><input type="text" name="position" id="position" class="only_numbers"/></span>
                            </li>
                            <li>
	                            <label>Logotip:</label>
	                            <span class="inputField wide"><input type="text" name="image_name" class="required" disabled="true" /></span>
                                <div class="uploadFile">
                                    <input type="file" name="logo" class="required" />
                                </div>
                            </li>
                            <li>
                                <span class="image">*Preporučena veličina logotipa 150px X 120px</span>
                            </li>
                        </ul>

                    </fieldset>

                    <div class="borderTop">

                        <span class="button back">
                            <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('brandGrid', 'brands/listing') ?>" />
                       
                        </span> 
                        <span class="button save">
                            <input type="button" value="Sačuvaj" onclick="validateForm('brandForm')" /> 
                        </span>
                        <?php if( isset($message) ) echo $message; ?>

                    </div>
                </form>

            </div>
        </li>
    </ul>
</div>