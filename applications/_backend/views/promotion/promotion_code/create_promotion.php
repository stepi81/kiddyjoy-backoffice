<div id="content">
	
    <ul>
        <li>
            <h2>Nova promocija</h2>
            <a href="#" class="collapse">Collapse</a>
            <div class="innerContent" >

                <form id="promotionForm" method="post" action="<?= site_url( 'promotion_code/submit' ) ?>">
                    <fieldset class="sectionForm half" style="float:left;">
					<input type="hidden" name="productid" id="productid" value="<?=@$id?>" />
                        <ul>
                            <li>
                                <label>Naslov:</label>
                                <span class="inputField wide"><input type="text" name="title" class="required" id="title" value="<?=@$name?>" /></span>
                            </li>
                            <!--<li><label>Tip</label>
                                <div class="customComboHolder">
                                	<div><?=@$tip?></div>
                                    <select name="type" id="type" >
                                        <option value="1" selected="selected" <?=@$t1?>>Ostalo</option> 
                                        <option value="2" <?=@$t2?>>Rođendanska čestitka</option>
                                    </select>
                                </div>
                            </li>-->
                            <li>
                                <label>Pocetak promocije:</label>
                                <span class="inputField wide"><input type="text" name="start_date" class="required date-picker" value="<?=@$start?>" id="start_date"/></span>
                            </li>
                            <li>
                                <label>Kraj promocije:</label>
                                <span class="inputField wide"><input type="text" name="end_date"  class="required date-picker" value="<?=@$end?>" id="end_date"/></span>
                            </li>
                            <li>
                            	<label>Popust u procentima</label>
                            	 <span class="inputField wide">
                                <input type="text"  name="discount" class="number-range" value="<?=@$percent?>" id="discount"  required />
								<div class='nr-buttons'><div class='nr-up'></div><div class='nr-down'></div></div>
								</span>
                            </li>
                            <li>
                            	<label>Brand:</label>
                            	<div class="dropbox">
                            		<?php foreach($brand_list as $brand): ?>
									<p>
										<input name="brand[]" type="checkbox" value="<?=$brand->getID()?>" /><label><?=$brand->getName()?></label>
									</p>
									<?php endforeach; ?>
                            	</div>
                            	
                            	<label>Kategorija:</label>
                            	<div class="dropbox">
                            		<?php foreach($category_list as $cat): ?>
									<p>
										<input name="category[]" type="checkbox" value="<?=$cat->getID()?>" /><label><?=$cat->getName()?></label>
									</p>
									<?php endforeach; ?>
                            	</div>

                            	<label>Podategorija:</label>
                            	<div class="dropbox">
                            		<?php foreach($subcategory_list as $sub): ?>
									<p>
										<input name="subcategory[]" type="checkbox" value="<?=$sub->getID()?>" /><label><?=$sub->getName()?></label>
									</p>
									<?php endforeach; ?>
                            	</div>
                            </li>
                             
                            <li>
                                <label>Status:</label>
                                <input type="radio" name="status" value="1"  <?=@$status ? 'selected' : ''?> />
                                <small>Aktivna</small>
                                <input type="radio" name="status" value="0" <?=@$status ? '' : 'selected'?> />
                                <small>Neaktivna</small>
                            </li>
                        </ul>

                    </fieldset>
                    <fieldset class="sectionForm" style="width:226px; float:left;  padding-left: 20px;">
                    				<legend>Dodaj proizvod:</legend>
                    				<span class="afteritem" style="position:relative;top:5px;"><a href="javascript: addProductItem('.afteritem','product')" ><img src="<?= layout_url('flexigrid/add.png')?>"/></a></span>
                   </fieldset>

                    <div class="borderTop">

                        <span class="button back">
                            
                            <input type="button" value="Nazad" onclick="location.href='<?=site_url('promotion_code/listing')?>'" />
                        </span> 
                        <span class="button save">
                            <input type="button" value="Snimi" onclick="validateForm('promotionForm');" /> 
                            
                        </span>
                        <div class="msg"></div>
                        <?php if( isset($message) ) echo $message; ?>

                    </div>
                </form>

            </div>
        </li>
    </ul>
</div>
<script type="text/javascript">
	function addProductItem(cl,name, value)
	{
		if(!value)
		value='';
		
		var code = '<li><label style="display:inline-block; width:40px;">ID:</label>';
		code 	+= '<span class="inputField wide" style="display: inline-block; width:60px;"><input type="text" name="'+name+'[]" class="required input-'+name+'" style="width:40px;" maxlength="6" value="'+value+'" /></span>';
		code 	+= '<a href="#" onclick="deleteProductItem(this);" style="margin-left:3px;top:5px; position:relative;"><img src="<?= layout_url('flexigrid/delete.png')?>" /></a>';
		code 	+= '</li>';                     	 
		$(cl).before(code);
		
	}
	
	function deleteProductItem(obj)
	{
		var rem = $(obj).parent('li');
		rem.remove();
	}
	
	$( ".date-picker" ).datepicker();
</script>