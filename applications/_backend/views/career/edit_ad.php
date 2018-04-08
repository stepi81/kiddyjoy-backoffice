<div id="content">
    <ul>
        <li>
            <h2>Detalji oglasa</h2>
            <a href="#" class="collapse">Collapse</a>

            <div class="innerContent">

                <form id="adsForm" method="post" action="<?= site_url( 'careers/edit_ad/'.$ad->getID() ) ?>" enctype="multipart/form-data">
                    <fieldset class="sectionForm">

                        <ul>
                             <li>
                                <label class="alignLeft">Naziv posla:</label>
                                <div class="customComboHolder">
                                    <div class="selected_element"><?= $job_name; ?></div>
                                    <select name="career_job_id" id="career_job_id">
                                        <?php foreach( $jobs as $job ) {?>
                                            <option value="<?= $job->getID() ?>" <?= $ad->getCareerJobId() == $job->getID() ? 'selected' : '' ?> ><?= $job->getName() ?></option>
                                        <?php } ?> 
                                    </select>
                                </div>
                            </li>
                            <li>
                                <label >Aktivan:</label>
                                <input type="radio" name="active" value="1" <?= ( $ad->getStatus() == '1' ) ? 'checked' : '' ?>/>
                                <small>Da</small>
                                <input type="radio" name="active" value="0" <?= ( $ad->getStatus() == '1' ) ? '' : 'checked' ?>/>
                                <small>Ne</small>
                            </li>
                            <li>
                                <label>Tekst Oglasa:</label>
                                <?= $tinymce ?>
                                <textarea name="page" id="page"><?= $ad->getText() ?></textarea>
                            </li>
                        </ul>

                    </fieldset>

                    <div class="borderTop">

                        <span class="button back">
                             <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('productsGrid', 'careers/ads_listing') ?>" />
                        </span> 
                        <span class="button save">
                            <input type="button" value="Izmeni" onclick="validateForm('adsForm')" /> 
                        </span>
                        <?php if( isset($message) ) echo $message; ?>

                    </div>
                </form>

            </div>
        </li>
    </ul>
</div>