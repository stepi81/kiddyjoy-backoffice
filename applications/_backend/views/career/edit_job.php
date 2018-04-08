<div id="content">
    <ul>
        <li>
            <h2>Detalji posla</h2>
            <a href="#" class="collapse">Collapse</a>

            <div class="innerContent">

                <form id="jobsForm" method="post" action="<?= site_url( 'careers/edit_job/'.$job->getID() ) ?>" enctype="multipart/form-data">
                    <fieldset class="sectionForm">

                        <ul>
                            <li>
                                <label>Naziv posla:</label>
                                <span class="inputField wide"><input type="text" name="name" class="required" value="<?= $job->getName() ?>" /></span>
                            </li>
                        </ul>

                    </fieldset>

                    <div class="borderTop">

                        <span class="button back">
                             <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('productsGrid', 'careers/jobs_listing') ?>" />
                        </span> 
                        <span class="button save">
                            <input type="button" value="Izmeni" onclick="validateForm('jobsForm')" /> 
                        </span>
                        <?php if( isset($message) ) echo $message; ?>

                    </div>
                </form>

            </div>
        </li>
    </ul>
</div>