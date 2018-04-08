<div id="content">
    <ul>
        <li>
            <h2><?= $grid_title ?></h2>
            <a href="#" class="collapse">Collapse</a>
            <div class="innerContent">
                <fieldset class="sectionForm half"> 
                    <div class="innerContent">
                    <?= $grid ?>
                    <table id="grid" style="display:none"></table>
                </fieldset>
            </div>
            <fieldset class="sectionForm half" style="padding-left: 30px; padding-top: 20px;">
                <form method="post" action="<?= site_url( 'product/filters/save/' .  $specification->getID() ) ?>" id="filtersForm" enctype="multipart/form-data">
                    <ul>
                        <li style="padding-top: 0px; width:200px;">
                            <label><b>Filter:</b></label> 
                            <span class="inputField wide"><input type="text" name="name"></span>
                        </li>
                        <li style="padding-top: 0px; width:200px;">
                            <label>Pozicija filtera:</label>
                            <span class="inputField short"><input type="text" name="position" value="" class="only_numbers" /></span>
                        </li>
                        <li>
                            <div>
                                <span class="button back">
                                    <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('specificationsGrid', 'product/specifications/listing/' . $specification->getSubcategory()->getID()) ?>" />
                                </span>
                                <span class="button save">
                                    <input type="button" value="Sačuvaj" onclick="validateForm('filtersForm')" id="saveRsponse" /> 
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
        </li>
    </ul>
</div>                           