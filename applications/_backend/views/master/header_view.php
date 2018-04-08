<ul class="topSettings">
	
	<li>
		<form id="applicationForm" action="<?= site_url( 'home/change_application' ) ?>" method="post">
	        <div class="customComboHolder">
	        	<div><?= $this->session->userdata('application_id') ? ucfirst(strtolower($this->session->userdata('application_id'))) : 'KiddyJoy' ?></div>
	            <select id="application" name="application_id">
					<option value=''>KiddyJoy</option>
	            	<?php foreach( $vendors as $vendor): ?>
	                <option value='<?= $vendor->getID() ?>' <?= $this->session->userdata('application_id') == $vendor->getID() ? 'selected' : '' ?>><?= ucfirst(strtolower($vendor->getID())) ?> Shop</option>
	                <?php endforeach ?>
	            </select>
	        </div>
        </form>
        <label>Izaberite aplikaciju</label>
    </li>
	
	<li class="settingsNav">
        <a href="<?= site_url('users/account') ?>">Moj nalog</a>
    </li>
                
    <li class="logoutNav">
        <a href="<?= site_url( 'logout' ) ?>">Odjavi se</a>
    </li>

</ul>

<?= $this->navigation_manager->breadcrumbs() ?>