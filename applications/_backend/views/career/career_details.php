<div id="content">
    <ul>
        <li>
            <h2>Detalji</h2>
            <a href="#" class="collapse">Collapse</a>

            <div class="innerContent">

                    <fieldset class="sectionForm">

                        <ul>
                            <li>
                                <label>Datum prijave:</label>
                                <span class="inputField wide"><input readonly="readonly" type="text" name="registration_date" id="name" class="required" value="<?= $career->getFormatedDate() ?>"/></span>
                            </li>
                            <li>
                                <label>Naziv posla:</label>
                                <span class="inputField wide"><input readonly="readonly" type="text" name="name" id="name" class="required" value="<?= $job_name ?>"/></span>
                            </li>
                            <li>
                                <label>Ime:</label>
                                <span class="inputField wide"><input readonly="readonly" type="text" name="name" id="name" class="required" value="<?= $career->getName() ?>"/></span>
                            </li>
                            <li>
                                <label>E-mail:</label>
                                <span class="inputField wide"><input readonly="readonly" type="text" name="email" id="email" class="required" value="<?= $career->getEmail() ?>"/></span>
                            </li>
                            <li>
                                <label>Telefon:</label>
                                <span class="inputField wide"><input readonly="readonly" type="text" name="phone" id="phone" class="required" value="<?= $career->getPhone() ?>"/></span>
                            </li>
                            <li>
                                <label>Web adresa:</label>
                                <span class="inputField wide"><input readonly="readonly" type="text" name="url" id="url" class="required" value="<?= $career->getUrl() ?>"/></span>
                            </li>
                            <?php if( $career->getCv() != "" ){ ?>
                                <li>
                                    <label>CV:</label>
                                    <a href="<?= APP_URL.'assets/cv/'.$career->getCv(); ?>" target="_blank" ><img src="<?= layout_url('/flexigrid/backoffice_document_big.png') ?>" /></a>
                                </li>
                            <?php } ?>
                            <li>
                                <label>Tekst oglasa:</label>
                                <span class="textArea"><textarea readonly="readonly" name="text"><?= $career->getMessage() ?></textarea></span>
                            </li>
                        </ul>

                    </fieldset>

                    <div class="borderTop">

                        <span class="button back">
                             <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('productsGrid', 'careers/listing') ?>" />
                        </span> 

                    </div>
                </form>

            </div>
        </li>
    </ul>
</div>