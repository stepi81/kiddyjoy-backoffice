<script type="text/javascript">

    $(document).ready(function(){

        /* Select product category and product id check based on ID and Category entry */
        $("#link_type").change(function(event){
            if($(this).val() == 1){
                $(".link label").text('ID Proizvoda');
                $(".link input").ForceNumericOnly().val('').addClass('product_id');
                $("span.save").attr('style','display:none');//Removes save button until product id check returns true
            }else{
                $(".link label").text('Link');
                $(".link input").unbind(event.ForceNumericOnly).val('').removeClass('product_id');
                $("span.save").attr('style','display:block');
                $(".link input").parent().removeClass('false');
            }
        });

        $('.selected_category').text($('#product_category option:selected').text());
        $('.selected_collection').text($('#collection option:selected').text());
        $('.selected_subcollection').text($('#subcollection option:selected').text());


        /*Display Subcategories item based on a selected Category*/
        $('#product_category').change(function(){
            $('.selected_group').text('Sellect'); //Removes selected option and displays default option
            //$('.selected_subcategory').text('Sellect');
            $('#group').empty(); //Removes any existing options
            //$('#group').addClass('required');
            var category = $('#product_category option:selected').val(); //Get's value of the selected option
            $.post('<?= site_url('products/get_groups')?>',{category_selection:category}, function(data){
               if(data){
                   $('#group').append(data);//Inserts newely created list
               }
            });
        });

        $('#group').change(function(){

            $('#subcategory').empty(); //Removes any existing options
            var group = $('#group option:selected').val(); //Get's value of the selected option
            $.post('<?= site_url('products/get_subcategories')?>',{group_selection:group}, function(data){

               if(data != '<option value="">Sellect</option>'){
                   $('.selected_subcategory').text('Sellect'); //Removes selected option and displays default option
                   $('#subcategory').append(data);//Inserts newely created list

               } else {
                   $('.selected_subcategory').text('Grupa nema podkategorije');
                   $('#subcategory').removeClass('required')
               }
            });
        });

        $("input:radio[name=type]").change(function() {

            if ( $(this).val() == 1 ){
                $(".campaing").attr('style','display:block');
                $(".product_ids_holder").attr('style','display:none');
                $('[name="product_id[]"]').val('');
            }

            if ( $(this).val() == 0 ){
                $(".product_ids_holder").attr('style','display:block');
               // $('#category').empty();
                $('#product_category>option:eq(0)').attr('selected', true);
                $('#group').empty();
                $('#subcategory').empty();
                $('#brand>option:eq(0)').attr('selected', true);
                $('.selected_category').text('Odaberite kategoriju');
                $('.selected_group').text('Odaberite grupu');
                $('.selected_subcategory').text('Odaberite podkategoriju');
                $('.selected_brand').text('Odaberite brend');
                $(".campaing").attr('style','display:none');

            }
        });
   });


</script>
<div id="content">
    <ul>
        <li>
        	<?php if( $category== 'product') { ?>
        	<h2><?= 'Nova proizvod reklama' ?></h2>
        	<?php } else{ ?>
			<h2><?= 'Nova' . ' ' . $category . ' reklama' ?></h2>
            <?php } ?>
            <a href="#" class="collapse">Collapse</a>

            <div class="innerContent">

                <form id="adsForm" method="post" action="<?= site_url( 'ads/save') ?>" enctype="multipart/form-data">
                    <fieldset class="sectionForm half">

                        <ul>
                            <li>
                                <input type="hidden" name="category" id="category" value="<?= $category ?>"  />
                                <input type="hidden" name="route_id" id="route_id" value="<?= $route_id ?>"  />
                            </li>
                            <li>
                                <label>Status:</label>
                                <input type="radio" name="status" value="1"/>
                                <small>Aktivna</small>
                                <input type="radio" name="status" value="0"/>
                                <small>Neaktivna</small>
                            </li>

                            <?php if( $route_id == 1 ): ?>
                            <!--<li>
                                <label>Status mobile:</label>
                                <input type="radio" name="status_mobile" value="1"/>
                                <small>Aktivna</small>
                                <input type="radio" name="status_mobile" value="0"/>
                                <small>Neaktivna</small>
                            </li>-->
                            <?php endif; ?>

                            <li>
                                <label>Naziv:</label>
                                <span class="inputField wide"><input type="text" name="title" class="required" /></span>
                            </li>

                            <li>
                                <label>Datum početka:</label>
                                <span class="inputField wide"><input type="text" name="start_date" class="date-picker" /></span>
                            </li>
                            <li>
                                <label>Datum kraja:</label>
                                <span class="inputField wide"><input type="text" name="end_date" class="date-picker" /></span>
                            </li>

                            <?php if( $route_id == 5 ) { ?>
                        		<?php if( $this->session->userdata('application_id') ): ?>
	                        	<li>
	                                <label>Meni Kategorija:</label>
	                                <span class="inputField wide"><input type="text" name="menu_id" id="menu_id" class="required" value=""/></span>
	                            </li>
	                            <?php else: ?>
	                            <li>
	                                <label class="alignLeft">Kategorija:</label>
	                                <div class="customComboHolder">
	                                    <div class="selected_category">Odaberite kategoriju</div>
	                                    <select name="filter_ad_category" id="product_category">
	                                        <option value="" selected>Odaberite kategoriju</option>
	                                    <?php foreach( $categories as $category) {?>
	                                        <option value="<?= $category->getID() ?>" ><?= $category->getName() ?></option>
	                                    <?php } ?>
	                                    </select>
	                                </div>
	                            </li>
	                        	<?php endif; ?>
                            <?php } ?>

                            <li>
                                <label class="alignLeft">Tip linka:</label>
                                <div class="customComboHolder">
                                    <div>Nema link</div>
                                    <select name="link_type" id="link_type" >
                                        <option value="" selected="selected">Nema link</option>
                                        <?php foreach( $link_types as $link_type ): ?>
                                            <option value="<?= $link_type->getID() ?>" ><?= $link_type->getName() ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </li>

                             <li>
                                <label class="alignLeft">Tip:</label>
                                <div class="customComboHolder">
                                    <div>Slika</div>
                                    <select name="source_type" id="source_type" >
                                        <option value="1" selected>Slika</option>
                                        <?php echo $route_id == 2 ? '<option value="2">SWF</option> ' : '' ?>
                                        <?php echo $route_id == 8 ? '<option value="2">Tekst</option> ' : '' ?>
                                    </select>
                                </div>
                            </li>
                            <li class="link">
                                <label>Link:</label>
                                <span class="inputField wide"><input type="text" name="link" id="link" value="" onBlur="check_id(this)"/></span>
                            </li>
                            <li>
                                <label>Pozicija:</label>
                                <span class="inputField short"><input type="text" name="position" id="position" class="only_numbers"/></span>
                            </li>
                            <li>
                                <label>Slika:</label>
                                <span class="inputField wide"><input type="text" name="image_name" <?php if( $route_id != 8 ) echo 'class="required"'; ?> disabled="true" /></span>
                                <div class="uploadFile">
                                    <input type="file" name="image" class="required" />
                                </div>
                            </li>

                            <li>
                                <label>Slika mobile:</label>
                                <span class="inputField wide"><input type="text" name="image_mobile_name" disabled="true" /></span>
                                <div class="uploadFile">
                                    <input type="file" name="image_mobile" />
                                </div>
                            </li>
                        </ul>

                    </fieldset>

                    <?php if( $route_id == 6 ) { ?>
                        <fieldset class="sectionForm half" style="padding-left:100px;">
                          <ul>
                            <li>
                                <label>Tip:</label>
                                <input type="radio" name="type" value="1"/>
                                <small>Kampanja</small>
                                <input type="radio" name="type" value="0"/>
                                <small>Proizvod</small>
                            </li>
                             <li> </li>
                             <div class="campaing" style="display: none;">
                             <li>
                                <label class="alignLeft">Kategorija:</label>
                                <div class="customComboHolder">
                                    <div class="selected_category">Odaberite kategoriju</div>
                                    <select name="product_category" id="product_category">
                                        <option value="" selected>Odaberite kategoriju</option>
                                    <?php foreach( $categories as $category) {?>
                                        <option value="<?= $category->getID() ?>" ><?= $category->getName() ?></option>
                                    <?php } ?>
                                    </select>
                                </div>
                             </li>
                             <li>
                                <label class="alignLeft">Grupa:</label>
                                <div class="customComboHolder">
                                    <div class="selected_group">Odaberite grupu</div>
                                    <select name="group" id="group">
                                        <option value="" selected>Prvo odaberite kategoriju</option>
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
                             <li><br></li>
                             <li>
                                <label class="alignLeft">Brend:</label>
                                <div class="customComboHolder">
                                    <div class="selected_brand">Odaberite brend</div>
                                    <select name="brand" id="brand">
                                        <option value="" selected>Odaberite brend</option>
                                    <?php foreach( $brands as $brand) {?>
                                        <option value="<?= $brand->getID() ?>" ><?= $brand->getName() ?></option>
                                    <?php } ?>
                                    </select>
                                </div>
                             </li>
                            </div>
                                <div class="product_ids_holder" style="display: none;">
                                    <li class="element-1">
                                        <label>ID Proizvoda:</label>
                                        <span class="inputField short">
                                            <input type="text" id="product_id-1" name="product_id[]" class="only_numbers">
                                        </span>
                                        <img class="add_new add_button-1" onclick="add_product(this)" alt="add_new" src="<?= layout_url('flexigrid/add.png')?>">
                                    </li>
                                </div>
                            </ul>
                        </fieldset>
                    <?php } ?>

                    <?php if( $route_id == 80 || $route_id == 90 ) { ?>
                        <fieldset class="sectionForm half" style="padding-left:100px;">
                          <ul>
                          	<?php if( $route_id == 8 ) { ?>
	                        <li>
	                            <label>Tekst:</label>
	                            <span class="textArea"><textarea name="text_banner"></textarea></span>
	                        </li>
	                        <?php } ?>
                             <li>
                                <label class="alignLeft">Kategorija:</label>
                                <div class="customComboHolder">
                                    <div class="selected_category">Odaberite kategoriju</div>
                                    <select name="filter_ad_category" id="product_category">
                                        <option value="" selected>Odaberite kategoriju</option>
                                    <?php foreach( $categories as $category) {?>
                                        <option value="<?= $category->getID() ?>" ><?= $category->getName() ?></option>
                                    <?php } ?>
                                    </select>
                                </div>
                             </li>
                             <li>
                                <label class="alignLeft">Grupa:</label>
                                <div class="customComboHolder">
                                    <div class="selected_group">Odaberite grupu</div>
                                    <select name="filter_ad_group" id="group">
                                        <option value="" selected>Prvo odaberite kategoriju</option>
                                    </select>
                                </div>
                             </li>
                             <li>
                                <label class="alignLeft">Podkategorija:</label>
                                <div class="customComboHolder">
                                    <div class="selected_subcategory">Odaberite podkategoriju</div>
                                    <select name="filter_ad_subcategory" id="subcategory" >
                                        <option value="" selected>Prvo odaberite grupu</option>
                                    </select>
                                </div>
                             </li>

                             <?php if( $route_id == 80 ) { ?>
	                        <li>
	                            <label>Minimalna cena:</label>
	                            <span class="inputField wide"><input type="text"  name="min_price" ></span>
	                        </li>
	                        <li>
	                            <label>Maksimalna cena:</label>
	                            <span class="inputField wide"><input type="text"  name="max_price" ></span>
	                        </li>
	                        <?php } ?>

                            </ul>
                        </fieldset>
                    <?php } ?>

                    <div class="borderTop">

                        <span class="button back">

                            <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('productsGrid', 'ads/listing/'.$route_id) ?>" />
                        </span>
                        <span class="button save">
                            <input type="button" value="Sačuvaj" onclick="validateForm('adsForm')" />
                        </span>
                        <?php if( isset($message) ) echo $message; ?>

                    </div>
                </form>

            </div>
        </li>
    </ul>
</div>