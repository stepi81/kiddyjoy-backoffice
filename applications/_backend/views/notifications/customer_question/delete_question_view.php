<table cellpadding="0" cellspacing="0" width="100%" height="312" bgcolor="#fff" style="height:312px; background-color:#fff;">
    <tr>
        <td valign="top">
            <table cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td width="19">&nbsp;</td>
                    <td width="660">
                        <table cellpadding="0" cellspacing="0" width="100%">
                            <tr>
                                <td>
                                    <h1 style="font-family:'Ubuntu', Arial, Helvetica, sans-serif; font-size:14px; color:#717171; font-weight:700; text-align:center; margin-bottom:10px;">PITANJA KUPACA</h1>
                                </td>
                            </tr>
                            <tr>
                                 <td>
                                    <p style="font-family:'Ubuntu', Arial, Helvetica, sans-serif; font-size:11px; color:#737272; margin-bottom:10px;">
                                    Poštovani <?= $user->getFirstName().' '.$user->getLastName() ?>,
                                    </p>
                                    <p style="font-family:'Ubuntu', Arial, Helvetica, sans-serif; font-size:11px; color:#737272; margin-bottom:10px;">
                                    	Vaše pitanje vlasnicima <?= $product->getName() ?> nije ispunio minimalne zahteve koji su propisani za odobrenje.
                                    </p>
                                    <p style="font-family:'Ubuntu', Arial, Helvetica, sans-serif; font-size:11px; color:#737272; margin-bottom:10px;">
                                    	Kliknite <a href="<?= ECOM_APP_URL.'uslovi_koriscenja' ?>" >OVDE</a> i upoznajte se sa pravilnikom uslova koje morate ispuniti da bi Pitanje bilo odobreno.<br />	
                                    </p>
                                    
                                    <p style="font-family:'Ubuntu', Arial, Helvetica, sans-serif; font-size:11px; color:#737272; margin-bottom:10px;">
                                    	Pitanje koje ste postavili:
                                    </p>
                                    <p style="font-family:'Ubuntu', Arial, Helvetica, sans-serif; font-size:11px; color:#737272; margin-bottom:10px;">
                                    	<?= $question->getQuestion(); ?><br />
                                    </p>
                                    
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
                    <td width="20">&nbsp;</td>
                </tr>
            </table>
        </td>
    </tr>
</table>