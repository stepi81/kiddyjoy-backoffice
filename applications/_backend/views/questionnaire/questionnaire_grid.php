<div id="content">
    <ul>

        <li>

            <h2><?= $grid_title ?></h2>
            <a href="#" class="collapse">Collapse</a>

            <div class="innerContent" style="height:600px">
                <fieldset class="sectionForm half">
                    <?= $grid ?>
                    <table id="grid" style="display:none"></table>
                </fieldset>
                <fieldset class="sectionForm half" style="padding-left: 30px;">

                    <?= $this->session->flashdata('questionnaires') ?>

                    <h3>Nova Anketa:</h3>
                    <form method="post" action="<?= site_url( 'inquiry/questionnaires/save' ) ?>" id="questionnaireForm" enctype="multipart/form-data">
                        <ul>
                            <li>
                                <label style="padding-left: 5px;">Naziv Ankete:</label><br />
                                <span class="inputField wide"><input type="text" name="title" id="title" class="required" /></span>
                            </li>
                            <li>
                                <label style="padding-left: 5px;">Slika:</label><br />
                                <span class="inputField wide"><input type="text" name="image_name" disabled="true" /></span>
                                <div class="uploadFile" style="margin-left: 0px;">
                                    <input type="file" name="image"  />
                                </div>
                            </li>
                            <li>
                                <label style="padding-left: 5px; width: 50px;">Status:</label>
                                <input type="radio" name="status" value="1" />
                                <small>Aktivna</small>
                                <input type="radio" name="status" value="0" />
                                <small>Neaktivna</small>
                            </li>
                            <li>
                                <div>
                                    <span class="button save">
                                        <input type="button" value="Sačuvaj" onclick="validateForm('questionnaireForm')" id="saveQuestionnaire" />
                                    </span>
                                    <?php if( isset($message) ) echo $message; ?> 
                                </div>
                            </li>
                        </ul>
                    </form>
                </fieldset>
            </div>

            </li>

    </ul>
</div>