<?php

$nom_prenom_admin_adh=$nom_prenom_membre_adh=$mail_membre_adh=$refer_url=$footer_mail='';

if ( $vars ) :
    $nom_prenom_admin_adh = $vars[0];
  	$nom_prenom_membre_adh = $vars[1];
  	$mail_membre_adh = $vars[2];
  	$refer_url = $vars[3];
  	$footer_mail = $vars[4];
endif; 


$contenu_mail = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title>CLER - Confirmation de raatachement à votre structure</title>
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
                    <h3 style="text-align:left; font-family: gotham,helvetica,arial,sans-serif; font-size:20px;line-height: 22px;">Bonjour '.$nom_prenom_admin_adh.'</h3>

                    <p style="text-align:left; font-family: gotham,helvetica,arial,sans-serif; font-size:16px;line-height: 22px;">
                      L\'utilisateur "'.$nom_prenom_membre_adh.'" ('.$mail_membre_adh.') demande à être rattaché à votre structure.<br><br>
                      Rendez-vous sur le site du CLER, dans <a href="'.$refer_url.'/mon-profil/#membres-structure">"Mon profil"</a> pour valider cette demande.
                    </p> 

                    <p style="text-align:center;"><a style="padding: 30px;color: #fff; display: inline-block; font-size: 13px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; border-radius: 100px; text-decoration: none; background-color:#00c15f;" href="'.$refer_url.'/mon-profil/#membres-structure">Mon profil</a></p> 

                    <p style="font-family: gotham,helvetica,arial,sans-serif;font-size:14px;padding:20px 0; color:#999;line-height: 20px;"> Les utilisateurs rattachés à votre structure peuvent publier des offres d\'emploi gratuitement et accèder aux contenus réservés aux adhérents du CLER. Il n\'ont pas accès aux informations liées à votre fiche adhérent (ré-adhésion, appels à cotisation, reçus).</p>
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