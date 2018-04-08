<div id="content">
    <ul>
        <li>
            <h2>Novi utisak</h2>
            <a href="#" class="collapse">Collapse</a>

            <div class="innerContent">

                <form id="reviewForm" method="post" action="<?= site_url( 'reviews/save/' ) ?>" enctype="multipart/form-data">
                    <fieldset class="sectionForm">

                        <ul>
                            <li>
                                <label>Status:</label>
                                <input type="radio" name="active" value="1" />
                                <small>Aktivan</small>
                                <input type="radio" name="active" value="0" />
                                <small>Neaktivan</small>
                            </li>
                            <li>
                                <label>ID korisnika:</label>
                                <span class="inputField wide"><input type="text" name="user_name" class="required" value="" /></span>
                            </li>
                            <li>
                                <label>ID Proizvoda:</label>
                                <span class="inputField wide"><input type="text" name="product" class="required" value="" /></span>
                            </li>
                            <li>
                                <label>Rejting:</label>
                                <span class="inputField wide"><input type="text" name="rating" class="required" value="" /></span>
                            </li>
                            <li>
                                <label>Datum objave:</label>
                                <span class="inputField short"><input type="text" value="<?= date('d.m.Y') ?>" /></span>
                            </li>
                            <li>
                                <label>Pozitivna ocena:</label>
                                <span class="inputField wide"><input type="text" name="positive" class="required" value="" /></span>
                            </li>
                            <li>
                                <label>Negativna ocena:</label>
                                <span class="inputField wide"><input type="text" name="negative" class="required" value="" /></span>
                            </li>
                            <li>
                                <label>Utisak:</label>
                                <span class="inputField textArea"><textarea name="text"></textarea></span>
                            </li>
                        </ul>

                    </fieldset>

                    <div class="borderTop">
                        <span class="button back">
                            
                            <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('productsGrid', 'reviews/listing') ?>" />
                        </span> 
                        <span class="button save">
                            <input type="button" value="Sačuvaj" onclick="validateForm('reviewForm')" /> 
                        </span>
                        <?php if( isset($message) ) echo $message; ?>
                    </div>
                </form>

            </div>
        </li>
    </ul>
</div>
