<div id="content">
    <ul>
        <li>
            <h2>Detalji filtera</h2>
            <a href="#" class="collapse">Collapse</a>

            <div class="innerContent">

                <form id="filterForm" method="post" action="<?= site_url( 'product/filters/edit/' . $filter->getID()) ?>" enctype="multipart/form-data">
                    <fieldset class="sectionForm">

                        <ul>
                            <li>    
                                <label>Ime filtera:</label>
                                <span class="inputField wide"><input type="text" name="name" class="required" value="<?= htmlspecialchars($filter->getName(), ENT_QUOTES) ?>"/></span>
                            </li>
                            <li>
                                <label>Pozicija filtera:</label>
                                <span class="inputField short"><input type="text" name="position" class="only_numbers" value = "<?= $filter->getPosition() ?>" /></span>
                            </li>
                        </ul>
                    </fieldset>

                    <div class="borderTop">

                        <span class="button back">
                            <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('filtersGrid', 'product/filters/listing/' . $filter->getSpecification()->getID()) ?>" />
                        </span> 
                        <span class="button save">
                            <input type="button" value="Izmeni" onclick="validateForm('filterForm')" /> 
                        </span>
                        <?php if( isset($message) ) echo $message; ?>

                    </div>
                </form>

            </div>
        </li>
    </ul>
</div>