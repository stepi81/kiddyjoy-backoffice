<div id="content">
    <ul>

        <li>
            
            <h2><?= $edit_title ?></h2>
            <a href="#" class="collapse">Collapse</a>

            <div class="innerContent" >
                
                <fieldset class="sectionForm half" style="padding-left: 30px;">
                    <ul>
                        
                        <li>
                            <form method="post" action="<?= site_url( 'inquiry/answers/edit_answer/'.$answer->getID() ) ?>" id="answerForm">
                                <ul>
                                    <li>
                                        <label style="padding-left: 5px;">Tekst:</label><br />
                                        <span class="textArea"><textarea name="answer_text"><?= $answer->getText() ?></textarea></span> 
                                    </li>
                                    <li>
                                        <label style="padding-left: 5px; padding: 0 0 0 5px;">Pozicija:</label><br />
                                        <span class="inputField wide"><input type="text" name="answer_position" id="answer_position" value="<?= $answer->getPosition() ?>" /></span>  
                                    </li>
                                    <li>
                                        <div>
                                            <span class="button back">
                                                <a href="<?= site_url( 'inquiry/answers/listing/'.$answer->getAnswer()->getID() ) ?>" style="text-decoration: none;"><input type="button" value="Nazad" /></a>
                                                <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('productsGrid', 'inquiry/answers/listing/'.$answer->getAnswer()->getID()) ?>" />
                                            </span> 
                                            <span class="button save">
                                                <input type="submit" value="Sačuvaj" onclick="save_brand('answerForm')" id="saveAnswer" /> 
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