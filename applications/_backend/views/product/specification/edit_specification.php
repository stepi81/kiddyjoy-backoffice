
<div id="content">
    <ul>
        <li>
            <h2>Detalji podkategorije</h2>
            <a href="#" class="collapse">Collapse</a>

            <div class="innerContent">

                <form id="specificationForm" method="post" action="<?= site_url( 'product/specifications/edit/' . $specification->getID()) ?>" enctype="multipart/form-data">
                    <fieldset class="sectionForm">

                        <ul>
                            <li>    
                                <label>Ime:</label>
                                <span class="inputField wide"><input type="text" name="name" class="required" value="<?= $specification->getName() ?>"/></span>
                            </li>
                            <li>
                                <label>Pozicija:</label>
                                <span class="inputField short"><input type="text" name="position" class="required only_numbers" value = "<?= $specification->getPosition() ?>" /></span>
                           </li>
                           <li>
                                <label>Info Pozicija:</label>
                                <span class="inputField short"><input type="text" name="position_info" class="only_numbers" value = "<?= $specification->getPositionInfo() ?>" /></span>
                           </li>                           
                           <li>
                                <label>Pozicija u kliritu:</label>
                                <span class="inputField short"><input type="text" name="position_klirit" class="only_numbers" value = "<?= $specification->getPositionKlirit() ?>" /></span>
                           </li>  
                        </ul>
                    </fieldset>

                    <div class="borderTop">

                        <span class="button back">
                            <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('specificationsGrid', 'product/specifications/listing/' . $specification->getSubcategory()->getID()) ?>" />
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
