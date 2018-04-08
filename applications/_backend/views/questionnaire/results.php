<div id="content">
    <ul>

        <li>

            <h2><?= $questionnaire_title ?></h2>
            <a href="#" class="collapse">Collapse</a>

            <div class="innerContent">

                <?php if(!isset($empty)):?>
                <?php foreach($questions as $question){
                	if(isset($results_type_1))
                        foreach($results_type_1 as $key=>$val){
                            if($question->getID() == $key){
                                isset ($val[0])? $val0=$val[0] : $val0='0';
                                isset ($val[1])? $val1=$val[1] : $val1='0';
                                echo '<h3 style="height:25px;margin-top:10px">' . $question->getText() . '</h3>';
                                echo '<ul style="list-style-type:disc;margin-left:30px;margin-bottom:20px">';
                                echo '<li>' . 'DA' . ' (' . $val1 . ')</li>';
                                echo '<li>' . 'NE' . ' (' . $val0 . ')</li>';
                                echo '</ul>';
                            } 
                        }
                	if(isset($results_type_2))
                        foreach($results_type_2 as $key=>$val){
                            if($question->getID() == $key){
                                echo '<h3 style="height:25px;margin-top:10px">' . $question->getText() . '</h3>';
                                echo '<ul style="list-style-type:disc;margin-left:30px;margin-bottom:20px">';
                                foreach($answers as $answer){
                                    if($answer->getAnswer()->getID() == $key ){
                                        if(isset($val[$answer->getID()])){
                                            $count = $val[$answer->getID()];
                                        }else{
                                            $count = 0;
                                        }
                                        echo '<li>' . $answer->getText() . ' (' . $count . ')</li>';
                                    }
                                }
                                echo '</ul>';
                            } 
                        }
                	if(isset($results_type_3))
                        foreach($results_type_3 as $key=>$val){
                            if($question->getID() == $key){
                                echo '<h3 style="height:25px;margin-top:10px">' . $question->getText() . '</h3>';
                                echo '<ul style="list-style-type:disc;margin-left:30px;margin-bottom:20px">';
                                foreach($val as $answer){
                                    if(trim((string)$answer) != 'Vaš odgovor...'){
                                        echo '<li>' .$answer . '</li>';
                                    }
                                }
                                echo '</ul>';
                            }
                        }
                    }
                ?>
                <?php else: ?>
                <h3><?= $empty ?></h3> 
                <?php endif ?>
                <div class="borderTop">  
                    <span class="button back">
                        <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('productsGrid', 'inquiry/questionnaires/listing') ?>" />
                    </span>
                </div>
            </div>

        </li>

    </ul>
</div>