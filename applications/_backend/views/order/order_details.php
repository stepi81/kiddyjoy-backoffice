<script type="text/javascript">
    
    function confirmOrderActivation(obj){
        if(confirm('Da li ste sigurni da želite da ponovo aktivirate narudžbinu?')){
            window.location = $(obj).attr('rel');
        }else{
            return false;
        }
    }
    
    function deleteOrder(obj){
        if(confirm('Da li ste sigurni da želite da obrišete narudžbinu?')){
            window.location = $(obj).attr('del');
        }else{
            return false;
        }
    }

</script>
<div id="content"> 
    <ul>
                    
        <li>

            <h2>Detalji Narudžbe</h2>
            <a href="#" class="collapse">Collapse</a>

            <div style="padding-top: 15px;">
                <?php if( isset( $prev[0] ) ){ ?>
                    <span class="button prev">
                        <a href="<?= site_url( 'orders/details/'.$prev[0]->getID() ) ?>" style="text-decoration: none;"><input type="button" value="Predhodna" style="padding-left: 42px; width:109px" /></a>
                    </span>
                <?php } ?>
                <?php if( isset( $next[0] ) ){ ?>
                    <span class="button next">
                        <a href="<?= site_url( 'orders/details/'.$next[0]->getID() ) ?>" style="text-decoration: none;"><input type="button" value="Sledeca" /></a>
                    </span>
                <?php } ?>
            </div>
            <div class="innerContent">
            
                <?= $this->session->flashdata('order') ?>

                <form method="post" action="<?= site_url( 'orders/edit/'.$order->getID() ) ?>" id="orderForm"> 
                    <fieldset class="sectionForm half" style="margin-right: 20px;">
                        <h3>Orders Info</h3>
                        <ul>
                            <li>
                                <?php
        
                                    if( $order->getStatus() == 3 ){
                                        $picture = 'flexigrid/order.png';
                                        $status = '<a href="javascript:void(0);" rel="'.site_url('orders/details_status_activation/'.$order->getID().'/1').'" onclick="confirmOrderActivation(this)"><img border="0" src="'.layout_url($picture).'"></a>'; 
                                        $actions = $status; 
                                    } else if ( $order->getStatus() == 2 ) {
                                        $picture = 'flexigrid/archive.png';
                                        $contacted = '<a href="'.site_url('orders/details_status_activation/'.$order->getID().'/1').'"><img border="0" src="'.layout_url('flexigrid/order.png').'"></a>'; 
                                        $status = '<a href="'.site_url('orders/details_status_activation/'.$order->getID().'/3').'"><img border="0" src="'.layout_url($picture).'"></a>'; 
                                        $actions = $contacted.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$status;
                                    } else {
                                        $picture = 'flexigrid/archive.png';
                                        $contacted = '<a href="'.site_url('orders/details_status_activation/'.$order->getID().'/2').'"><img border="0" src="'.layout_url('flexigrid/order_contact.png').'"></a>';
                                        $status = '<a href="'.site_url('orders/details_status_activation/'.$order->getID().'/3').'"><img border="0" src="'.layout_url($picture).'"></a>';
                                        $actions = $contacted.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$status;
                                    }
        
                                ?>
                                
                                <label>Akcije:</label>
                                <p style="padding-top: 10px; width: 200px"><?= $actions; ?></p>
                            </li>
                            <?php 
                                $deliveries = unserialize(DELIVERY);
                                if( $order->getLocation() ) { $location = $order->getLocation()->getID(); } else { $location = ''; }
                                if( $order->getDelivery() ) { if( $order->getDelivery() == 2 ) { $delivery = $deliveries[$order->getDelivery()].' - '.$location; } else { $delivery = $deliveries[$order->getDelivery()]; } } else { $delivery = ''; }
                            ?>
                            <li>
                                <label>Isporuka:</label>
                                <span class="inputField wide"><input readonly type="text" name="delivary_id" id="delivary_id" class="required" value="<?=  $delivery  ?>" /></span>
                            </li>
                            <li>
                                <label>Ukupna cena:</label>
                                <span class="inputField wide"><input readonly type="text" name="total_price" id="total_price" class="required" value="<?= $order->getTotalPrice() ?>" /></span>
                            </li>
                            <li>
                                <label>Popust:</label>
                                <span class="inputField wide"><input readonly type="text" name="discount" id="discount" value="<?= $order->getDiscount() ? $order->getDiscount().'%' : '' ?>" /></span>
                            </li>
                            <li>
                                <label>Datum narudžbe:</label>
                                <span class="inputField wide"><input readonly type="text" name="date" id="date" class="required" value="<?= $order->getFormatedDate() ?>" /></span>
                            </li>
                            <li>
                                <label>Napomena:</label>
                                <span class="textArea"><textarea name="info"><?= $order->getInfo() ?></textarea></span>
                            </li>
                        </ul>
                    </fieldset>
                </form>
                <fieldset class="sectionForm half" style="padding-left: 30px;">
                <ul> 
                <?php switch( get_class($order) ) {
                        case 'models\Entities\Order\Regular':
                            switch( get_class( $order->getUser() ) ) {
                                case 'models\Entities\User\Customer\Personal':
                                $this->load->view('order/customer/private_user');
                                break;
                                case 'models\Entities\User\Customer\Business':
                                $this->load->view('order/customer/business_user');
                                break;
                            }
                            break;
                        case 'models\Entities\Order\Fast':
                             $this->load->view('order/customer/private_user');
                            break;
                } ?>
                </ul>
                </fieldset>
                    
            </div>
            <div class="borderTop">
                <span class="button back">
                     <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('productsGrid', 'orders/listing/' . $order->getStatus()) ?>" />
                </span>
                <span class="button save">
                    <input type="button" value="Sačuvaj" onclick="validateForm('orderForm')" id="saveOrder" />
                </span>
                <?php if ( $order->getStatus() != 3 ) { ?>
                    <span class="button cancel">
                        <a href="javascript:void(0);" del="<?= site_url('orders/delete_order/'.$order->getID()) ?>" onclick="deleteOrder(this)" style="text-decoration: none;"><input type="button" value="Obriši" /></a>
                    </span> 
                <?php } ?>
            </div>

        </li>
        <li>
        
            <h2>Porudžbina</h2>
            <a href="#" class="collapse">Collapse</a>

            <fieldset class="sectionForm half" style="padding-left: 30px;">
                <div class="innerContent">
                    <?= $grid ?>
                    <table id="grid" style="display:none"></table>
                </div>
                <?php if( isset( $configurations_grid ) ) { ?>
                <div class="innerContent">
                    <?= $configurations_grid ?>
                    <table id="configuration_grid" style="display:none"></table>
                </div>
                <?php } ?>
            </fieldset> 

            <?php if( count( $bundle_items ) ) { ?>
            <fieldset class="sectionForm half" style="padding-left: 30px;">
                <div class="innerContent">
                    <?= $bundle_grid ?>
                    <table id="bundle_grid" style="display:none"></table>
                </div>
            </fieldset> 
            <?php } ?>
            
            <div class="borderTop">

                <span class="button back">
                    <input type="button" value="Nazad" onclick="<?= $this->navigation_manager->backToGrid('productsGrid', 'orders/listing/' . $order->getStatus()) ?>" />
                </span>

            </div>
        </li>
    </ul>
</div>
