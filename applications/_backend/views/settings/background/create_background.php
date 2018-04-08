<script type="text/javascript">
   
    $(document).ready(function(){
        
        /* Select product category and product id check based on ID and Category entry */
        
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
            <h2>Nova pozadina</h2>
            <a href="#" class="collapse">Collapse</a>

            <div class="innerContent">

                <form id="backgroundForm" method="post" action="<?= site_url( 'settings/backgrounds/save' ) ?>" enctype="multipart/form-data">
                    <fieldset class="sectionForm">

                        <ul>
                            <li>
                                <label>Status:</label>
                                <input type="radio" name="status" value="1" />
                                <small>Aktivna</small>
                                <input type="radio" name="status" value="0" />
                                <small>Neaktivna</small>
                            </li>
                            <li>
                                <label>Naziv:</label>
                                <span class="inputField wide"><input type="text" name="background_name" class="required" /></span>
                            </li>
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
                            <!--<?php if(!$this->session->userdata('application_id')):?>
                            <li>
                                <label class="alignLeft">Vendor:</label>
                                <div class="customComboHolder">
                                    <div>Odaberi</div>
                                    <select name="vendor_id" id="vender_id" >
                                        <option value="" selected="selected">Odaberi</option> 
                                        <?php foreach( $vendors as $vendor ): ?>
                                        	<?php if( $vendor->getID() != 10 ): ?>
                                            <option value="<?= $vendor->getID() ?>" ><?= $vendor->getID() ?></option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </li>
                            <?php endif; ?>-->
                            <li>
                                <label>Cela Slika:</label>
                                <span class="inputField wide"><input type="text" name="image_name" disabled="true" /></span>
                                <div class="uploadFile">
                                    <input type="file" name="background_image" />
                                </div>
                            </li>
                            <li>
                                <label>URL:</label>
                                <span class="inputField wide"><input type="text" name="background_url" /></span>
                            </li>
                            <li>
                                <label>Leva Slika:</label>
                                <span class="inputField wide"><input type="text" name="image_name" disabled="true" /></span>
                                <div class="uploadFile">
                                    <input type="file" name="left_background_image" />
                                </div>
                            </li>
                            <li>
                                <label>Levi URL:</label>
                                <span class="inputField wide"><input type="text" name="left_url" /></span>
                            </li>
                            <li>
                                <label>Desna Slika:</label>
                                <span class="inputField wide"><input type="text" name="image_name" disabled="true" /></span>
                                <div class="uploadFile">
                                    <input type="file" name="right_background_image" />
                                </div>
                            </li>
                            <li>
                                <label>Desni URL:</label>
                                <span class="inputField wide"><input type="text" name="right_url" /></span>
                            </li>
                        </ul>

                    </fieldset>

                    <div class="borderTop">

                        <span class="button back">
                            <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('productsGrid', 'settings/backgrounds/listing') ?>" />
                        </span> 
                        <span class="button save">
                            <input type="button" value="Sačuvaj" onclick="validateForm('backgroundForm')" /> 
                        </span>
                        <?php if( isset($message) ) echo $message; ?>

                    </div>
                </form>

            </div>
        </li>
    </ul>
</div>