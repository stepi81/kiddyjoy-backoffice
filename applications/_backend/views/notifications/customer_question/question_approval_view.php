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
                                    	Vaše pitanje je odobreno od strane administratora i poslato svim vlasnicima <?= $product->getName() ?>
                                    </p>
                                    <p style="font-family:'Ubuntu', Arial, Helvetica, sans-serif; font-size:11px; color:#737272; margin-bottom:10px;">
                                    	U Vašem <a href="<?= ECOM_APP_URL.'korisnik' ?>">KORISNIČKOM PROFILU</a> možete pregledati pitanja koja ste postavili i odgovore koje ste dobili od strane vlasnika tih proizvoda.<br />	
                                    </p>
                                    
                                    <p style="font-family:'Ubuntu', Arial, Helvetica, sans-serif; font-size:11px; color:#737272; margin-bottom:10px;">
                                    	Pitanje koje ste postavili:	
                                    </p>
                                    <p style="font-family:'Ubuntu', Arial, Helvetica, sans-serif; font-size:11px; color:#737272; margin-bottom:10px;">
                                    	<?= $question->getQuestion(); ?><br />
                                    </p>
                                    
                                    <p style="font-family:'Ubuntu', Arial, Helvetica, sans-serif; font-size:11px; color:#737272; margin-bottom:10px;">
                                    	Hvala na ukazanom poverenju,
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