 <div id="content">
    <ul>
        <li>
            <h2>Detalji administratorske grupe</h2>
            <a href="#" class="collapse">Collapse</a>

            <div class="innerContent">

                <form id="adminGroupForm" method="post" action="<?= site_url( 'users/admin_group/edit/'.$group->getID() ) ?>">
                    <fieldset class="sectionForm">

                        <ul>
                            <li>
                                <label>Naziv grupe:</label>
                                <span class="inputField wide"><input type="text" name="group_name" id="group_name" class="required" value="<?= $group->getName() ?>" /></span>
                            </li>
                            <li><label>Privilegije pristupa:</label>
                            <?php foreach( $sections as $section ): ?>
                            <li>
                            	<label>&nbsp;</label>
								<input type="checkbox" value="<?= $section->getID() ?>" name="sections[]"<?= $group->getSections()->contains($section) ? ' checked="checked"' : '' ?> />
								<small><?= $section->getName() ?></small>
                            </li>
                            <?php endforeach; ?>
                        </ul>

                    </fieldset>

                    <div class="borderTop">

                        <span class="button back">
                            <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('productsGrid', 'users/admin_group/listing') ?>" />
                        </span> 
                        <span class="button save">
                            <input type="button" value="Izmeni" onclick="validateForm('adminGroupForm')" />
                        </span>
                        <?php if( isset($message) ) echo $message; ?>

                    </div>
                </form>

            </div>
        </li>
    </ul>
</div>