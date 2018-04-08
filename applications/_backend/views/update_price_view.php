<div id="content">
    <ul>
        <li>
            <h2>Update cena</h2>
            <a href="#" class="collapse">Collapse</a>

            <div class="innerContent">

                <form id="updateForm" method="post" action="<?= site_url( 'products/price_update' ) ?>" enctype="multipart/form-data">
                    <fieldset class="sectionForm">

                        <ul>
                            <li>
	                            <label>Naziv fajla:</label>
	                            <span class="inputField wide"><input type="text" name="thumb_name" class="required" disabled="true" /></span>
                                <div class="uploadFile">
                                    <input type="file" name="thumb" class="required" />
                                </div>
                            </li>
                        </ul>

                    </fieldset>

                    <div class="borderTop">
                        <span class="button save">
                            <input type="button" value="Izmeni" onclick="validateForm('updateForm')" /> 
                        </span>
                        <?php if( isset($message) ) echo $message; ?>
                    </div>
                </form>

            </div>
        </li>
    </ul>
</div>