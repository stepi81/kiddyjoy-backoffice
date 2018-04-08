<div id="content">
    <ul>
        <li>
            <h2>Detalji specifikacije</h2>
            <a href="#" class="collapse">Collapse</a>

            <div class="innerContent">

                <form id="specificationForm" method="post" action="<?= site_url( 'reviews/edit_specification/'.$specification->getID()) ?>">
                    <fieldset class="sectionForm">

                        <ul>
                            <li>    
                                <label>Naziv:</label>
                                <span class="inputField wide"><input type="text" name="name" class="required" value="<?= $specification->getName() ?>"/></span>
                            </li>
                            <li>
                                <label>Pozicija:</label>
                                <span class="inputField short"><input type="text" name="position" class="only_numbers" value = "<?= $specification->getPosition() ?>" /></span>
							</li>
                        </ul>
                    </fieldset>

                    <div class="borderTop">

                        <span class="button back">
                            <input type="button" value="Nazad" onclick="<?= site_url('reviews/specifications/'.$specification->getSubcategory()->getID()) ?>" />
                        </span> 
                        <span class="button save">
                            <input type="button" value="Izmeni" onclick="validateForm('specificationForm')" /> 
                        </span>
                        <?php if( isset($message) ) echo $message; ?>
                    </div>
                </form>

            </div>
        </li>
    </ul>
</div>