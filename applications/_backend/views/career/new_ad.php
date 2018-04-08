<div id="content">
    <ul>
        <li>
            <h2>Nov oglas</h2>
            <a href="#" class="collapse">Collapse</a>

            <div class="innerContent">

                <form id="adForm" method="post" action="<?= site_url( 'careers/save_ad' ) ?>" enctype="multipart/form-data">
                    <fieldset class="sectionForm">

                        <ul>
                             <li>
                                <label class="alignLeft">Naziv posla:</label>
                                <div class="customComboHolder">
                                    <div>Odaberi</div>
                                    <select name="career_job_id" id="career_job_id"  class="required" >
                                        <option value="" >Odaberi</option>
                                        <?php foreach( $jobs as $job ) {?>
                                            <option value="<?= $job->getID() ?>" ><?= $job->getName() ?></option>
                                        <?php } ?> 
                                    </select>
                                </div>
                            </li>
                            <li>
                                <label >Aktivan:</label>
                                <input type="radio" name="active" value="1" />
                                <small>Da</small>
                                <input type="radio" name="active" value="0" />
                                <small>Ne</small>
                            </li>
                            <li>
                                <label>Tekst Oglasa:</label>
                                <?= $tinymce ?>
                                <textarea name="page" id="page"></textarea>
                            </li>
                        </ul>

                    </fieldset>

                    <div class="borderTop">

                        <span class="button back">
                             <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('productsGrid', 'careers/ads_listing') ?>" />
                        </span> 
                        <span class="button save">
                            <input type="button" value="Sačuvaj" onclick="validateForm('adForm')" /> 
                        </span>
                        <?php if( isset($message) ) echo $message; ?>

                    </div>
                </form>

            </div>
        </li>
    </ul>
</div>