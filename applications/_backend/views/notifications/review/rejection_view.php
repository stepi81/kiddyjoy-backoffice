<tr>
	<td style="width:329px; border-right:1px solid #c2c2c2;" valign="top">
		<table border="0" cellpadding="0" cellspacing="0" style="width:100%;">           
			<tr>
				<td style="padding:0px; height:115px; width:329px;">
					<img src="<?= $logo; ?>" alt="KiddyJoy" width="329px" height="115px" />
				</td>
			</tr>
		</table>
	</td>
	 
	<td style="padding:0px;" valign="top">
		<table cellpadding="0" cellspacing="0" style="border-left:1px solid #ffffff;">
			<tr>
				<td style="padding:0px;">
					<p style="width:270px;padding-left:30px; padding-top:20px; padding-bottom:50px; color:#00aeef; font-weight:bold; font-size:14px; margin:0px;">
					UTISCI KORISNIKA
					</p>
				</td>
			</tr>
			<tr>
				<td style="padding-left:30px"> 
                    <p style="font-family:'Ubuntu', Arial, Helvetica, sans-serif; font-size:11px; color:#737272; margin-bottom:10px;">
                    Poštovani <?= $review->getUserId()->getFirstName() . ' ' . $review->getUserId()->getLastName() ?>,<br />
                    </p>
                    <p style="font-family:'Ubuntu', Arial, Helvetica, sans-serif; font-size:11px; color:#737272; margin-bottom:10px;">
                    Vaš opis za <?= $review->getProductId()->getName() ?> nije ispunio minimalne zahteve koji su propisani za odobrenje.<br />
                    Kliknite <a href="<?= ECOM_APP_URL.'korisnik/utisci' ?>" >ovde</a> i upoznajte se sa pravilnikom uslova koje morate ispuniti da bi opis bio objavljen na sajtu.<br />
                    </p>
                    <?php if( $review->comment ): ?>
                        <p style="font-family:'Ubuntu', Arial, Helvetica, sans-serif; font-size:11px; color:#737272; margin-bottom:10px;">
                        Obrazloženje administratora:<br />
                        <?= $review->comment ?>
                        </p>
                    <?php endif; ?>
                    <p style="font-family:'Ubuntu', Arial, Helvetica, sans-serif; font-size:11px; color:#737272; margin-bottom:10px;">
                    Hvala na razumevanju,
                    </p>
                    <p style="font-family:'Ubuntu', Arial, Helvetica, sans-serif; font-size:11px; color:#737272; margin-bottom:10px;">
                    Vaš KiddyJoy
                    </p>
                </td>
			</tr>
		</table>
	</td>                   
</tr>