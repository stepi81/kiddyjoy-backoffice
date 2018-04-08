<div id="content">
    <ul>

        <li>
            <h2>Pitanja</h2>
            <a href="#" class="collapse">Collapse</a>

            <div class="innerContent" style="height:600px">

                <fieldset class="sectionForm half"> 
                    <?= $grid ?>
                    <table id="grid" style="display:none"></table>
                </fieldset>
                <fieldset class="sectionForm half" style="padding-left: 30px;">

                    <h3 style="margin-top: 10px;">Detalji ankete:</h3>
                    <form method="post" action="<?= site_url( 'inquiry/questions/edit/'.$questionnaire->getID() ) ?>" id="questionnaireForm" enctype="multipart/form-data">
                        <ul>
                            <li>
                                <label>&nbsp;</label>
                                <span class="image"><img src="<?= $questionnaire->getImageURL() ?>" alt="KiddyJoy" /></span>
                            </li>
                            <li>
                                <label style="padding-left: 5px;">Slika:</label><br />
                                <span class="inputField wide"><input type="text" name="image_name" disabled="true" /></span>
                                <div class="uploadFile" style="margin-left: 0px;">
                                    <input type="file" name="image" />
                                </div>
                            </li>
                            <li>
                                <label style="padding-left: 5px;">Datum:</label><br />
                                <span class="inputField wide"><input type="text" name="date" id="date" readonly="readonly" value="<?= $questionnaire->getFormatedDate() ?>"  /></span> 
                            </li>
                            <li>
                                <label style="padding-left: 5px;">Naziv ankete:</label><br />
                                <span class="inputField wide"><input type="text" name="title" id="title" value="<?= $questionnaire->getTitle() ?>"  /></span> 
                            </li>
                            <li>
                                <label style="padding-left: 5px; width: 50px;">Aktivna:</label>
                                <input type="radio" name="status" value="1" <?= ( $questionnaire->getStatus() == '1' ) ? 'checked' : '' ?>/>
                                <small>Da</small>
                                <input type="radio" name="status" value="0" <?= ( $questionnaire->getStatus() == '1' ) ? '' : 'checked' ?>/>
                                <small>Ne</small>
                            </li>
                            <li>
                                <div>

                                    <span class="button back">
                                        <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('productsGrid', 'inquiry/questionnaires/listing') ?>" />
                                    </span> 
                                    <span class="button save">
                                        <input type="button" value="Sačuvaj" onclick="validateForm('questionnaireForm')" id="saveQuestionanire" /> 
                                    </span>
                                    <br>
                                    <li>
                                    <?php if( isset($message) ) echo $message; ?> 
                                    </li>                                
                                </div>
                            </li>
                        </ul>
                    </form>
                </fieldset>
            </div>
            
            </li>

    </ul>
</div>
