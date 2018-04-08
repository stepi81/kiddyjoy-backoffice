<div id="content">
    <ul>
        <li>
        	<?php if( $article_category->getParent() ): ?>
            	<h2>Detalji blog podkategorije</h2>
            <?php else: ?>
            	<h2>Detalji blog kategorije</h2>
            <?php endif; ?>
            <a href="#" class="collapse">Collapse</a>

            <div class="innerContent">

                <form id="articleCategoryForm" method="post" action="<?= site_url( 'article/article_categories/edit/'.$article_category->getID() ) ?>" enctype="multipart/form-data">
                    <fieldset class="sectionForm">
                        <ul>
                        	<li>
                                <label>&nbsp;</label>
                                <span class="image"><img src="<?= $article_category->getImageURL() ?>" alt="" /></span>
                            </li>
                            <li>
                                <label>Naslov:</label>
                                <span class="inputField wide"><input type="text" name="name" value="<?= $article_category->getName() ?>" required="required"/></span>
                            </li>
                            <li>
                                <label>Pozicija:</label>
                                <span class="inputField short"><input type="text" name="position" id="position" value="<?= $article_category->getPosition() ?>" class="only_numbers"/></span>
                            </li>
                            <li>
							    <label>Seo Title:</label>
							    <span class="textArea"><textarea name="seo_title" ><?= $article_category->getSeoTitle() ?></textarea></span>
							</li>
							<li>
							    <label>SEO Keywords:</label>
							    <span class="textArea"><textarea name="seo_keywords" ><?= $article_category->getSeoKeywords() ?></textarea></span>
							</li>
                            <li>
                            	<label>Status:</label>
                                <input type="radio" name="status" value="1"<?= $article_category->getStatus() ? ' checked="checked"' : '' ?> />
                                <small>Aktivna</small>
                                <input type="radio" name="status" value="0"<?= $article_category->getStatus() ? '' : ' checked="checked"' ?> />
                                <small>Neaktivna</small>
                            </li>
                            <li>
	                            <label>Slika:</label>
	                            <span class="inputField wide"><input type="text" name="thumb_name" disabled="true" /></span>
                                <div class="uploadFile">
                                    <input type="file" name="thumb" />
                                </div>
                            </li>
                        </ul>

                    </fieldset>

                    <div class="borderTop">

                        <span class="button back">
                            <?php if( $article_category->getParent() ): ?>
                            	<input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('articleCategoriesGrid', 'article/article_categories/listing/'.$article_category->getParent()->getID()) ?>" />
                            <?php else: ?>
                            	<input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('articleCategoriesGrid', 'article/article_categories/listing/') ?>" />	
                            <?php endif; ?>
                        </span> 
                        <span class="button save">
                            <input type="button" value="Sačuvaj" onclick="validateForm('articleCategoryForm')" /> 
                        </span>
                        <?php if( isset($message) ) echo $message; ?>

                    </div>
                </form>

            </div>
        </li>
    </ul>
</div>
