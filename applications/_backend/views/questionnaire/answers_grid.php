<div id="content">
    <ul>

        <li>
            <h2><?= 'Pitanje:' . ' ' .  $question->getText() ?></h2>
            <a href="#" class="collapse">Collapse</a>

            <div class="innerContent" >
                
                <?php if($question->getType() == 2) { ?>
                    <fieldset class="sectionForm half"> 
                        <?= $grid ?>
                        <table id="grid" style="display:none"></table>
                    </fieldset>
                <?php } ?>
                
                <fieldset class="sectionForm half" style="padding-left: 30px;">
                    <ul>
                        <?php if($question->getType() == 2) { ?> 
                            <li>
                                <h3>Novi odgovor:</h3>
                                <form method="post" action="<?= site_url( 'inquiry/answers/save/'.$question->getID() ) ?>" id="answerForm">
                                    <ul>
                                        <li>
                                            <label style="padding-left: 5px;">Tekst odgovora:</label><br />
                                            <span class="textArea"><textarea name="answer_text"></textarea></span> 
                                        </li>
                                        <li>
                                            <label style="padding-left: 5px; padding: 0 0 0 5px;">Pozicija:</label><br />
                                            <span class="inputField wide"><input type="text" name="answer_position" id="answer_position" /></span>  
                                        </li>
                                        <li>
                                            <div>
                                                <span class="button save" style="padding-bottom: 30px;">
                                                    <input type="submit" value="Sačuvaj" id="saveAnswer" onclick="validateForm('answerForm')"/>
                                                </span>

                                            </div>
                                        </li>
                                    </ul>
                                </form>
                            </li>
                        <?php } ?>
                        
                        <li>
                            <form method="post" action="<?= site_url( 'inquiry/answers/edit/'.$question->getID() ) ?>" id="questionForm">
                                <ul>
                                    <h3 style="float: left">Detalji pitanja:</h3><br/>
                                    <li>
                                        <label style="padding-left: 5px;">Tekst:</label><br />
                                        <span class="textArea"><textarea name="question_text"><?= $question->getText() ?></textarea></span> 
                                    </li>
                                    <li>
                                        <label style="padding-left: 5px; padding: 0 0 0 5px;">Pozicija:</label><br />
                                        <span class="inputField wide"><input type="text" name="position" id="position" value="<?= $question->getPosition() ?>" /></span>  
                                    </li>
                                    <li style="padding-top: 20px;">
                                        <div>

                                            <span class="button back">
                                                
                                                <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('productsGrid', 'inquiry/questions/listing/'.$question->getQuestionnaire()->getID()) ?>" />
                                            </span> 
                                            <span class="button save">
                                                <input type="submit" value="Sačuvaj" onclick="validateForm('questionForm')" id="saveQuestion" /> 
                                            </span>

                                        </div>
                                    </li>
                                </ul>
                            </form>
                        </li>
                    </ul>
                </fieldset>
            </div>
            
            </li>

    </ul>
</div>
