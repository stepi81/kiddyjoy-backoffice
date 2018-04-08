<div id="content">
    <ul>
        <li>
            <h2>Detalji pitanja</h2>
            <a href="#" class="collapse">Collapse</a>

            <div class="innerContent">

                <form id="questionForm" method="post" action="<?= site_url( 'customer_questions/edit_question/'.$question->getID() ) ?>">
                    <fieldset class="sectionForm">

                        <ul>
                            <li>
                                <label>Status:</label>
                                <input type="radio" name="active" value="1"<?= $question->getStatus() ? ' checked="checked"' : '' ?>/>
                                <small>Aktivan</small>
                                <input type="radio" name="active" value="0"<?= $question->getStatus() ? '' : ' checked="checked"' ?>/>
                                <small>Neaktivan</small>
                            </li>
                            <li>
                                <label>Ime korisnika:</label>
                                <span class="inputField wide"><input type="text" disabled="true" name="user_name" class="required" readonly="readonly" " value="<?= $question->getUserName() ?>" /></span>
                            </li>
                            <li>
                                <label>Proizvod:</label>
                                <span class="inputField wide"><input type="text" disabled="true" name="product" class="required" readonly="readonly" value="<?= $question->getProduct()->getName() ?>" /></span>
                            </li>
                            <li>
                                <label>Datum objave:</label>
                                <span class="inputField short"><input type="text" disabled="true" readonly="readonly" value="<?= $question->getDate() ?>" /></span>
                            </li>

                            <li>
                                <label>Komentar:</label>
                                <span class="inputField textArea"><textarea name="message"><?= $question->getQuestion() ?></textarea></span>
                            </li>
                        </ul>

                    </fieldset>

                    <div class="borderTop">
                        <span class="button back">

                            <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('customerQuestionsGrid', 'customer_questions/listing') ?>" />
                        </span> 
                        <span class="button save">
                            <input type="button" value="Sačuvaj" onclick="validateForm('questionForm')" /> 
                        </span>
                        <?php if( isset($message) ) echo $message; ?>

                    </div>
                </form>

            </div>
        </li>
    </ul>
</div>