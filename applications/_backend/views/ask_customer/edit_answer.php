<div id="content">
    <ul>
        <li>
            <h2>Detalji odgovora</h2>
            <a href="#" class="collapse">Collapse</a>

            <div class="innerContent">

                <form id="answerForm" method="post" action="<?= site_url( 'customer_questions/edit_answer/'.$answer->getID() ) ?>">
                    <fieldset class="sectionForm">

                        <ul>
                            <li>
                                <label>Status:</label>
                                <input type="radio" name="active" value="1"<?= $answer->getStatus() ? ' checked="checked"' : '' ?>/>
                                <small>Aktivan</small>
                                <input type="radio" name="active" value="0"<?= $answer->getStatus() ? '' : ' checked="checked"' ?>/>
                                <small>Neaktivan</small>
                            </li>
                            <li>
                                <label>Ime korisnika:</label>
                                <span class="inputField wide"><input type="text" disabled="true" name="user_name" class="required" readonly="readonly" value="<?= $answer->getUserName() ?>" /></span>
                            </li>
                            <li>
                                <label>Datum objave:</label>
                                <span class="inputField short"><input type="text" disabled="true" readonly="readonly" value="<?= $answer->getDate() ?>" /></span>
                            </li>
							<li>
                                <label>Pitanje:</label>
                                <span class="inputField textArea"><textarea name="message" readonly="readonly"><?= $answer->getQuestion()->getQuestion() ?></textarea></span>
                            </li>
                            <li>
                                <label>Komentar:</label>
                                <span class="inputField textArea"><textarea name="answer"><?= $answer->getAnswer() ?></textarea></span>
                            </li>
                        </ul>

                    </fieldset>

                    <div class="borderTop">
                        <span class="button back">

                            <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('customerAnswerGrid', 'customer_questions/answers_listing') ?>" />
                        </span> 
                        <span class="button save">
                            <input type="button" value="Sačuvaj" onclick="validateForm('answerForm')" /> 
                        </span>
                        <?php if( isset($message) ) echo $message; ?>

                    </div>
                </form>

            </div>
        </li>
    </ul>
</div>