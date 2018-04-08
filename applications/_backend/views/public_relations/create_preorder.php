<div id="content">
    <ul>
        <li>
            <h2>Preorder stranica</h2>
            <a href="#" class="collapse">Collapse</a>

            <div class="innerContent">

                <form id="preorderForm" method="post" action="<?= site_url( 'preorder_pages/save' ) ?>" enctype="multipart/form-data">
                    <fieldset class="sectionForm">
                        <input type="hidden" value="<?= $this->session->userdata('preorder_type_id') ?>" name="preorder_type_id" />
                        <ul>
                            <li>
                                <label>Datum isteka:</label>
                                <span class="inputField wide"><input type="text" class="required" name="send_date" id="send_date" /></span>
                            </li>
                            <li>
                                <label>Naslov:</label>
                                <span class="inputField wide"><input type="text" name="title" class="required" /></span>
                            </li>
                            <li>
                                <label>Kratak opis:</label>
                                <span class="textArea"><textarea name="summary" class="required"></textarea></span>
                            </li>
                            <li>
	                            <label>Thumb:</label>
	                            <span class="inputField wide"><input type="text" name="thumb_name" class="required" disabled="true" /></span>
                                <div class="uploadFile">
                                    <input type="file" name="thumb" class="required" />
                                </div>
                            </li>
                        </ul>

                    </fieldset>

                    <div class="borderTop">

                        <span class="button back">
                            
                            <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('productsGrid', 'preorder_pages/listing/'.$this->session->userdata('preorder_type_id')) ?>" />
                        </span> 
                        <span class="button save">
                            <input type="button" value="Sačuvaj" onclick="validateForm('preorderForm')" /> 
                        </span>
                        <?php if( isset($message) ) echo $message; ?>

                    </div>
                </form>

            </div>
        </li>
    </ul>
</div>
