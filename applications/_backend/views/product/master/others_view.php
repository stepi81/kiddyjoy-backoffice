<li>
<h2>Ostalo</h2>
<a href="#" class="collapse">Collapse</a>
<div class="innerContent">
    <fieldset class="sectionForm">
        <ul>
            <fieldset class="sectionForm half" style="clear:both">
                <li>
                    <label>Ostalo:</label>
                    <?= $tinymce_other ?> 
                    <textarea name="other" id="other"><?php if ( $new_product == FALSE) { echo $product->getDescription(); } ?></textarea>
                </li>
            </fieldset>
        </ul>
     </fieldset>
</div>
</li> 
<li>
<h2>Informacije</h2>
<a href="#" class="collapse">Collapse</a>
<div class="innerContent">
    <fieldset class="sectionForm">
        <ul>
            <fieldset class="sectionForm half" style="clear:both">
                <li>
                    <label>Ostalo:</label>
                    <?= $tinymce_information ?> 
                    <textarea name="information" id="information"><?php if ( $new_product == FALSE) { echo $product->getInformation(); } ?></textarea>
                </li>
            </fieldset>
        </ul>
     </fieldset>
</div>
</li>    