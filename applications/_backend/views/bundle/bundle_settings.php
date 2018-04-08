<script type="text/javascript">
   
    $(document).ready(function(){
        
        $('.selected_category').text($('#product_category option:selected').text());
        $('.selected_collection').text($('#collection option:selected').text());
        $('.selected_subcollection').text($('#subcollection option:selected').text());
        $('.selected_group').text($('#cat_group option:selected').text());

        /*Display Subcategories item based on a selected Category*/
        $('#product_category').change(function(){
            $('.selected_group').text('Sellect'); //Removes selected option and displays default option
            //$('.selected_subcategory').text('Sellect');
            $('#cat_group').empty(); //Removes any existing options
            $('#specifications_content').empty();
            //$('#cat_group').addClass('required');
            var category = $('#product_category option:selected').val(); //Get's value of the selected option
            $.post('<?= site_url('products/get_groups')?>',{category_selection:category}, function(data){
               if(data){
                   $('#cat_group').append(data);//Inserts newely created list
               }
            });
        });

        $('#cat_group').change(function(){
             
            $('#subcategory').empty(); //Removes any existing options
            $('#specifications_content').empty();
            var group = $('#cat_group option:selected').val(); //Get's value of the selected option
            $.post('<?= site_url('products/get_subcategories')?>',{group_selection:group}, function(data){
              
               if(data != '<option value="">Sellect</option>'){
                   $('.selected_subcategory').text('Sellect'); //Removes selected option and displays default option
                   $('#subcategory').append(data);//Inserts newely created list
                   
               } else {
                   $('.selected_subcategory').text('Grupa nema podkategorije');
                   $('#subcategory').removeClass('required')
               }
            });

            $.post('<?= site_url('bundles/get_specifications')?>',{subcategory_id:group}, function(data){
            	$('#specifications_content').append(data);
            });
        });
        
        $('#subcategory').change(function(){
            
            $('#specifications_content').empty();
            var subcategory = $('#subcategory option:selected').val(); //Get's value of the selected option
            $.post('<?= site_url('bundles/get_specifications')?>',{subcategory_id:subcategory}, function(data){
            	$('#specifications_content').append(data);
            });
        });
   });
     
</script>

<div id="content">
    <ul>

        <li>

            <h2>Proizvodi sa paketima</h2>
            <a href="#" class="collapse">Collapse</a>

            <div class="innerContent" style="height:600px">
                <form method="post" id="setBundleForm" action="<?= site_url( 'bundles/set_bundle_on_products' ) ?>" >
                
	                <fieldset class="sectionForm half">
	                    <?= $grid ?>
	                    <table id="grid" style="display:none"></table>
	                </fieldset> 
	                
	                <fieldset class="sectionForm half" style="padding-left: 30px;">
	                	<ul>
	                		<li>
	                            <label class="alignLeft">Paket:</label>
	                            <div class="customComboHolder">
	                                <div class="selected_brand">Odaberite paket</div>
	                                <select name="filter_bundle" id="product_bundle" class="required">
	                                    <option value="" selected>Odaberite paket</option>
		                                <?php foreach( $bundles as $bundle) {?>
		                                    <option value="<?= $bundle->getID() ?>" ><?= $bundle->getName() ?></option>
		                                <?php } ?>
	                                </select>
	                            </div>
	                        </li>
	                		
	                		<li>
	            				<label class="alignLeft">Brend</label>
	            				<div class="customComboHolder">
									<input type="hidden" name="brands[]" value="" />
		                			<div id ="multiSelect" class="multiSelect">Odaberi</div>
                					<div id="multiSelection" class="multiSelection">
                    					<?php foreach( $brands as $brand) {?>
                    						<label><input type="checkbox" class="drop_down" value="<?= $brand->getID() ?>" name="product_brands[]" /><?= $brand->getName() ?></label>
                    					<?php } ?>	
                					</div>
	            				</div>
	        				</li>
	        				<script>multiSelect(false);</script>
	                		
	                    	<li>
	                            <label class="alignLeft">Kategorija:</label>
	                            <div class="customComboHolder">
	                                <div class="selected_category">Odaberite kategoriju</div>
	                                <select name="filter_category" id="product_category" class="required">
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
	                                <select name="filter_group" id="cat_group" class="required">
	                                    <option value="" selected>Prvo odaberite kategoriju</option>
	                                </select>
	                            </div>
	                         </li>
	                         <li>
	                            <label class="alignLeft">Podkategorija:</label>
	                            <div class="customComboHolder">
	                                <div class="selected_subcategory">Odaberite podkategoriju</div>
	                                <select name="filter_subcategory" id="subcategory" >
	                                    <option value="" selected>Prvo odaberite grupu</option>
	                                </select>
	                            </div>
	                         </li>
	                         <div id="specifications_content"></div>
	                         <li>
	                            <label>&nbsp;</label>
	                            <span class="button save">
                                	<input type="button" value="Dodaj" onclick="validateForm('setBundleForm')" />
                            	</span>
	                        </li>
	                        <li>
	                        	<?= $message; ?>
	                        </li>
	                	</ul> 
		 
	                </fieldset>	                 

	        	 </form>

            </div> 
        </li>

    </ul>
</div>