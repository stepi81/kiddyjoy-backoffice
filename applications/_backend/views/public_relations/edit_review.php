<div id="content">
    <ul>
        <li>
            <h2>Detalji utiska</h2>
            <a href="#" class="collapse">Collapse</a>

            <div class="innerContent">
                <fieldset class="sectionForm">
                    <form id="reviewForm" method="post" action="<?= site_url( 'reviews/submit/1/'.$review->getID() ) ?>"> 
                        <ul>
                        	<li>
                                <label>Avatar:</label>
                                <img alt="" src="<?= $review->getUserId()->getAvatarURL() ?>">
                            </li>
                            <li>
                                <label>Korisnik:</label>
                                <span class="inputField wide"><input type="text" disabled="disabled" name="user_name" class="required" value="<?= $user_name ?>" /></span>
                            </li>
                            <li>
                                <label>Broj bodova:</label>
                                <span class="inputField short"><input type="text" disabled="disabled" name="user_name" class="required" value="<?= $review->getUserId()->getPoints() ?>" /></span>
                            </li>
                            <li>
                                <label>Datum objave:</label>
                                <span class="inputField short"><input type="text" disabled="disabled" value="<?= $review->getFormatedDate() ?>" /></span>
                            </li>
                            <li>
                                <label><a href="<?= $review->getProductId()->getURL() ?>" target="_blank">Proizvod:</a></label>
                                <span class="inputField wide"><input type="text" disabled="disabled" name="product" class="required" value="<?= $review->getProductId()->getName() ?>" /></span>
                            </li>
                            <?php foreach( $review->getProductId()->getSubcategory()->getReviewSpecifications() as $specification ): ?>
                            <li>
                                <label><?= $specification->getName() ?>:</label>
                                <span class="inputField short"><input type="text" disabled="disabled" value="<?= $review->getRating( $specification->getID() ) ?>" /></span>
                            </li>
                            <?php endforeach; ?>
                            <li>
                                <label>Rejting:</label>
                                <span class="inputField short"><input type="text" disabled="disabled" value="<?= $review->getOverall() ?>" /></span>
                            </li>
                            <li>
                                <label>Pozitivna ocena:</label>
                                <span class="inputField short"><input type="text" disabled="disabled" value="<?= $review->getPositive() ?>" /></span>
                            </li>
                            <li>
                                <label>Negativna ocena:</label>
                                <span class="inputField short"><input type="text" disabled="disabled" value="<?= $review->getNegative() ?>" /></span>
                            </li>
                            <li>
                                <label>Za:</label>
                                <span class="inputField textArea">
                                	<textarea name="textAdvantage"<?= !$review->getStatus() ? '' : ' disabled="disabled"' ?>><?= $review->getTextAdvantage() ?></textarea>
                                </span>
                            </li>
                            <li>
                                <label>Protiv:</label>
                                <span class="inputField textArea">
                                	<textarea name="textAgainst"<?= !$review->getStatus() ? '' : ' disabled="disabled"' ?>><?= $review->getTextAgainst() ?></textarea>
                                </span>
                            </li>
                            <?php if( !$review->getStatus() ): ?>
                            <li>
                            	<label>Bodovi:</label>
						        <div class="customComboHolder">
						        	<div>5 KiddyJoy bodova</div>
						            <select name="">
						                <option>5 KiddyJoy bodova</option>
						            </select>
						        </div>
						    </li>
						    <?php else: ?>
						    <li>
                                <label>Bodovi:</label>
                                <span class="inputField wide"><input type="text" disabled="disabled" value="5 KiddyJoy bodova" /></span>
                            </li>
						    <?php endif; ?>
                            
                        </ul>
                    </form>
                    <form id="rejactionForm" method="post" action="<?= site_url( 'reviews/submit/0/'.$review->getID() ) ?>">
                        <ul>
                            <li>
                                <label>Obrazloženje administratora:</label>
                                <span class="inputField textArea">
                                    <textarea name="comment"></textarea>
                                </span>
                            </li>
                        </ul>
                    </form>    
                </fieldset>
				
				
				<div class="borderTop">
                    <?php if( !$review->getStatus() ): ?>
					<span class="button back">
                            <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('productsGrid', 'reviews/listing') ?>" />
					</span> 
					<?php else: ?>
					<span class="button back">
						<input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('productsGrid', 'reviews/history') ?>" />
					</span> 
					<?php endif; ?>
					<span class="button save">
						<input type="button" value="Potvrdi" onclick="if(confirm('Potvrdi utisak?')) document.getElementById('reviewForm').submit(); else return false;" />
					</span>
					<span class="button cancel">
						<input type="button" value="Poništi" onclick="if(confirm('Poništi utisak?')) document.getElementById('rejactionForm').submit(); else return false;" />
					</span>
				</div>

            </div>
        </li>
    </ul>
</div>
