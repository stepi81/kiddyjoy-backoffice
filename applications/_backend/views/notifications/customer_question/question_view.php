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
                                    	Kao verni kupac u KiddyJoyu, odabrali ste mogućnost da pomognete drugima u odabiru pravog proizvoda kroz uslugu "Pitaj Kupca".
                                    </p>
                                    <p style="font-family:'Ubuntu', Arial, Helvetica, sans-serif; font-size:11px; color:#737272; margin-bottom:10px;">
                                    	Korisnik <?= $customer->getFirstName().' '.$customer->getLastName() ?> Vam je poslao pitanje vezano za <?= $product->getName() ?><br />
                                    </p>
                                    
                                    <p style="font-family:'Ubuntu', Arial, Helvetica, sans-serif; font-size:11px; color:#737272; margin-bottom:10px;">
                                    	Pitanje korisnika:
                                    </p>
                                    <p style="font-family:'Ubuntu', Arial, Helvetica, sans-serif; font-size:11px; color:#737272; margin-bottom:10px;">
                                    	<?= $question->getQuestion(); ?>
                                    </p>
                                    
                                    <p style="font-family:'Ubuntu', Arial, Helvetica, sans-serif; font-size:11px; color:#737272; margin-bottom:10px;">
                                    	Kliknite <a href="<?= ECOM_APP_URL.'korisnik/odgovori/'.$question->getURL() ?>" >OVDE</a> da odete na Vaš korisnički profil gde možete da odgovorite na pitanje.<br />
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