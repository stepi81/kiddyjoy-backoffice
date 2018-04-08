<li>
    <label class="alignLeft">Kategorija:</label>
    <div class="customComboHolder">
        <div class="selected_category">Odaberite kategoriju</div>
        <select name="category" id="category" class="required">
            <option value="" selected>Odaberite kategoriju</option>
            <?php foreach( $categories as $category) {?>
                <option value="<?= $category->getID() ?>" ><?= $category->getName() ?></option>
            <?php } ?>
        </select>
    </div>
 </li>
 <li>
    <label class="alignLeft">Grupe:</label>
    <div class="customComboHolder">
        <div class="selected_group">Odaberite grupu</div>
        <select name="group" id="group" class="required">
            <option value="" selected>Provo odaberite kategoriju</option>
        </select>
    </div>
 </li>
 <li>
    <label class="alignLeft">Podkategorija:</label>
    <div class="customComboHolder">
        <div class="selected_subcategory">Odaberite podkategoriju</div>
        <select name="subcategory" id="subcategory" >
            <option value="" selected>Prvo odaberite grupu</option>
        </select>
    </div>
</li>
<li>
    <label>Ime:</label>
    <span class="inputField wide"><input type="text" name="name" class="required" /></span>
</li>
<li>
    <label class="alignLeft">Garancija:</label>
    <div class="customComboHolder">
        <div>Odaberite garanciju</div>
        <select name="warranty" id="warranty" >
            <option value="" >Odaberite garanciju</option> 
            <?php foreach( $warranties as $warranty ) {?>
                <option value="<?= $warranty->getID() ?>" ><?= $warranty->getName() ?></option>
            <?php } ?>
        </select>
    </div>
</li>
<li>
    <label>Akcija:</label>
    <input type="radio" name="promotion" value="1"/>
    <small>Da</small>
    <input type="radio" name="promotion" value="0"/>
    <small>Ne</small>
</li>
<!--<li>
    <label>Rasprodaja:</label>
    <input type="radio" name="sale" value="1"/>
    <small>Da</small>
    <input type="radio" name="sale" value="0"/>
    <small>Ne</small>
</li>
<li>
    <label>Outlet:</label>
    <input type="radio" name="outlet" value="1"/>
    <small>Da</small>
    <input type="radio" name="outlet" value="0"/>
    <small>Ne</small>
</li>-->
<li>
    <label>Status:</label>
    <input type="radio" name="status" value="1"/>
    <small>Aktivan</small>
    <input type="radio" name="status" value="0"/>
    <small>Neaktivan</small>
</li>
</fieldset>
<fieldset class="sectionForm half" style="padding-left: 30px;">
    <li>
        <label>Lista cena:</label>
        <span class="textArea"><textarea name="price_list" disabled="disabled"></textarea></span>
    </li>
    <li>
        <label>Sticker:</label>
        <select multiple="multiple" id="stickers" name="stickers[]">
          <?php foreach ($stickers as $sticker){ ?>
          <option value="<?= $sticker->getID() ?>"><?=$sticker->getName()?></option>
          <?php } ?>
        </select>
    </li>
</fieldset>