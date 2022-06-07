<body style="background-color:#fff; font-family:Arial, Helvetica, sans-serif; color:#00201F;">
<?php //pr($UserData); ?>
<table style=" max-width:100%; width:600px; border:1px solid #ccc;" align="center" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td align="left" valign="top" height="10">&nbsp;</td>
        </tr>
        <tr>
          <td align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="30" align="left" valign="top">&nbsp;</td>
                <td align="left" valign="top"><img src="<?php echo APIURL ?>img/logo.png" alt="logo" width="43px"></td>
                <td align="left" valign="middle" style=" font-size:18px; text-transform:uppercase; text-align:right; font-weight:bold;">Hello,<?php echo $UserData['first_name']; ?> </td>
				
                <td width="30" align="left" valign="top">&nbsp;</td>
              </tr>
            </table></td>
        </tr>
        <tr>
          <td align="left" valign="top" height="10">&nbsp;</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="border-bottom:1px solid #eeeeee; border-top:1px solid #eeeeee;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
             
            </table></td>
        </tr>
        <tr>
          <td align="left" valign="top" height="10">&nbsp;</td>
        </tr>
        <tr>
          <td align="left" valign="top" style="font-size:12px;">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="15" height="30">&nbsp;</td>
                 <td width="125" height="30" style="font-weight:bold;"><?php echo $UserData['message']; ?>.</td>
                <td width="30" height="30">&nbsp;</td>
              </tr>              
            </table>
		  </td>
        </tr>
        <tr>
          <td align="left" valign="top" height="10">&nbsp;</td>
        </tr>
        
       
        
        
        <tr>
          <td align="left" valign="top">&nbsp;</td>
        </tr>
        <tr>
          <td height="30" align="left" valign="middle" style=" font-size:12px; text-align:center; border-top:1px solid #eaeaea;">PIDA APP. All rights reserved</td>
        </tr>
      </table></td>
  </tr>
</table>
</body>
<?php //exit; ?>	