<div id="content">
    <ul>

        <li>
            <h2>Izdvajamo - <?= $article_category->getName() ?></h2>
            <a href="#" class="collapse">Collapse</a>

            <div class="innerContent" >

                <fieldset class="sectionForm half"> 
                    <?= $grid ?>
                    <table id="grid" style="display:none"></table>
                </fieldset>
                
                <fieldset class="sectionForm half" style="padding-left: 30px;">
                    <ul>
                        <li>
                            <form method="post" action="<?= site_url( 'article/article_highlights/save/' ) ?>" id="createHighlightForm">
                                <ul>
                                	<input type="hidden" value="<?= $article_category->getID() ?>" name="category_id" />
                                    <li>
                                        <label style="padding-left: 5px; padding: 0 0 0 5px;">Blog ID:</label><br />
                                        <span class="inputField wide required"><input type="text" name="article_id" id="article_id" /></span>  
                                    </li>
                                    <li>
                                        <label style="padding-left: 5px; padding: 0 0 0 5px;">Pozicija:</label><br />
                                        <span class="inputField wide"><input type="text" name="position" id="position" /></span>  
                                    </li>
                                    <li>
		                                <label>Tip:</label>
		                                <input type="radio" name="type" value="1" checked="checked" />
		                                <small>Slideshow</small>
		                                <input type="radio" name="type" value="2"/>
		                                <small>Slider</small>
		                            </li>
                                    <li style="padding-top: 20px;">
                                        <div>
                                            <span class="button back">
                                                <?php if( $article_category->getParent() ): ?>
										        	<input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('articleCategoriesGrid', 'article/article_categories/listing/'.$article_category->getParent()->getID()) ?>" />		
												<?php else: ?>
										        	<input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('articleCategoriesGrid', 'article/article_categories/listing/') ?>" />
												<?php endif; ?>
                                            </span> 
                                            <span class="button save">
                                                <input type="submit" value="Sačuvaj" onclick="validateForm('addLevelForm')" id="createHighlightForm" /> 
                                            </span>

                                        </div>
                                    </li>
                                </ul>
                            </form>
                        </li>
                        <li>
                        	<?= $this->session->flashdata('create_highlight_message'); ?>
                        </li>
                    </ul>
                </fieldset>
            </div>
		</li>
    </ul>
</div>