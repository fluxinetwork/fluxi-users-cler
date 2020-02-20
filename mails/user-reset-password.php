<?php

$email;
$prenom;
$refer_url;
$password;
$footer_mail;

if ( $vars ) :    
  	$email = $vars[0];  	
  	$prenom = $vars[1];
  	$refer_url = $vars[2];
    $password = $vars[3];
    $footer_mail = $vars[4];
endif;


$contenu_mail = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title>CLER - Réinitialisation de mot de passe</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="background-color: #fff5e5; color: #333; margin:0; font-family: gotham,helvetica,arial,sans-serif; font-size: 16px;">
  <table width="100%" style="background-color: #fff5e5; color: #333; font-family: gotham,helvetica,arial,sans-serif; font-size: 16px; text-align:center; margin:0; padding:0;" border="0" cellpadding="0" cellspacing="0">
    <tr style="margin:0;padding:0;">
      <td style="margin:0;padding:0;">
        <table style="text-align:center; max-width:600px; width:100%;margin:0 auto 40px;padding:20px;" border="0" cellpadding="0" cellspacing="0">
          <tr style="margin:0;padding:0;">
            <td style="margin:0;padding:0;">
              <table width="100%" style="text-align:left; margin:20px 0;" border="0" cellpadding="0" cellspacing="0">
                <tr style="margin:0;padding:0;">
                  <td style="background: #fff; padding:20px 30px 30px;">
                    <h3 style="text-align:left; font-family: gotham,helvetica,arial,sans-serif; font-size:20px;line-height: 22px;">Bonjour '.$prenom.',</h3>

                    <p style="text-align:left; font-family: gotham,helvetica,arial,sans-serif; font-size:16px;line-height: 22px;">Vous venez de réinitialiser votre mot de passe sur le site du CLER - Réseau pour la transition énergétique.</p>

                    <p style="text-align:left; font-family: gotham,helvetica,arial,sans-serif; font-size:16px;line-height: 22px;"><strong>Votre nouveau mot de passe :</strong> '.$password.'</p>

                    <p style="text-align:center;"><a style="padding: 30px;color: #fff; display: inline-block; font-size: 13px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; border-radius: 100px; text-decoration: none; background-color:#00c15f;" href="'.$refer_url.'">Connexion</a></p>

                    <p style="color:#ff9933;text-align:left; font-family: gotham,helvetica,arial,sans-serif; font-size:14px;line-height: 22px;">Si vous n\'avez pas effectué cette action, veuillez contacter rapidement l\'administrateur du site par téléphone ou à cette adresse : info@cler.org.</p>
                  </td>
                </tr>
              </table>
            '.$footer_mail.'
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>';
?>