<div id="content">
    <ul>
        <li>
            <h2>Kloniraj proizvod</h2>
            <a href="#" class="collapse">Collapse</a>

            <div class="innerContent">

                <form id="updateForm" method="post" action="<?= site_url( 'products/clone_product' ) ?>" enctype="multipart/form-data">
                    <fieldset class="sectionForm">

                        <ul>
                            <li>
                                <label>ID Proizvoda:</label>
                                <span class="inputField wide"><input type="text" name="product_id" class="required" /></span>
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