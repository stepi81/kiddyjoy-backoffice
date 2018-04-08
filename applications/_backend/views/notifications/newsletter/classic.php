<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>KiddyJoy Shop</title>
</head>
<body style="margin:0; padding:0;">
	
	<table border="0" cellpadding="10" cellspacing="0" style="background:#3299ba; width:100%;">
		<tr>
			<td>
				<table border="0" cellpadding="0" cellspacing="0" style=" width:100%; background:#fff;  font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; line-height:18px; border-collapse:collapse; margin:0px;">
					<tfoot>
						<tr>
							<td style="background:#ffffff; border-top:1px solid #ffffff; width:auto; padding:0px;" colspan="2">
								<a href="http://www.kiddyjoy.com/" style="color:#ff9900; font-size:12px; text-decoration:none; line-height:24px;  margin:0px; padding-left:15px; float:left;">http://www.kiddyjoy.com</a>
	                            <p style="color:#3299ba; line-height:24px; font-size:12px;  margin:0px; padding-left:15px; float:left;">Call centar</p>
	                            <p style="color:#ff9900; line-height:24px; font-size:12px;  margin:0px; padding-left:15px; float:left;">069 35 67 375</p>
	                            <p style="color:#3299ba; line-height:24px; font-size:10px;  margin:0px; padding-left:15px; float:left;">Ponedeljak - Petak: od 09 do 17h, Subota: od 10 do 14h</p>
	                            <p style="color:#ff9900; line-height:24px; font-size:12px;  margin:0px; padding-left:15px; float:left;">Poziv je besplatan</p>
	                            <p style="color:#3299ba; line-height:24px; font-size:10px;  margin:0px; padding-left:15px; float:left;">Ukoliko ne želite da primate KiddyJoy shop e-mailove, kliknite <a href="<?= ECOM_APP_URL.'newsletter/odjava/'.$request_token ?>" style="color:#000; text-decoration:none;">ovde</a></p>
							</td>
						</tr>
					</tfoot>
					
					<tbody>
						<tr>
							<!--<td style="width:329px; border-right:1px solid #c2c2c2;" valign="top">
								<table border="0" cellpadding="0" cellspacing="0" style="width:100%;">           
									<tr>
										<td style="padding:0px; height:115px; width:329px;">
											<img src="<?= $logo; ?>" alt="KiddyJoy" width="329px" height="115px" />
										</td>
									</tr>
								</table>
							</td>-->
							 
							<td style="padding-left:30px;" valign="top">
								<table cellpadding="0" cellspacing="0" style="border-left:1px solid #ffffff;">
									<tr>
										<?= $message; ?>
									</tr>
								</table>
							</td>                   
						</tr>
					</tbody>
				</table>
			</td>
		</tr>
	</table>
	
</body>
</html>
