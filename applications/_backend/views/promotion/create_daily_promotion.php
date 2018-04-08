<div id="content">
    <ul>
        <li>
            <h2>Dnevna promocija</h2>
            <a href="#" class="collapse">Collapse</a>

            <div class="innerContent">

                <form id="promotionForm" method="post" action="<?= site_url( 'daily_promotions/save' ) ?>">
                    <fieldset class="sectionForm">

                        <ul>
                            <li>
                                <label>Product:</label>
                                <span class="inputField wide"><input type="text" name="product" class="required" /></span>
                            </li>
                            <li>
                                <label>Pocetak promocije:</label>
                                <span class="inputField wide"><input type="text" class="required" name="start_date" id="start_date" /></span>
                            </li>
                            <li>
                                <label>Kraj promocije:</label>
                                <span class="inputField wide"><input type="text" class="required" name="end_date" id="end_date" /></span>
                            </li>
                            <li>
                                <label>Status:</label>
                                <input type="radio" name="status" value="1" />
                                <small>Aktivna</small>
                                <input type="radio" name="status" value="0" />
                                <small>Neaktivna</small>
                            </li>
                        </ul>

                    </fieldset>

                    <div class="borderTop">

                        <span class="button back">
                            
                            <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('productsGrid', 'daily_promotions/listing') ?>" />
                        </span> 
                        <span class="button save">
                            <input type="button" value="Snimi" onclick="validateForm('promotionForm')" /> 
                            
                        </span>
                        <?php if( isset($message) ) echo $message; ?>

                    </div>
                </form>

            </div>
        </li>
    </ul>
</div>
