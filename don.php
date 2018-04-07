<?php session_start();
include_once'actu.php';
include_once'connectes.php'; ?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
		<link rel="stylesheet" href="design.css" />
        <title>Faire un don</title>
		<meta name="description" content="Jeu de stratégie et de gestion gratuit en ligne par navigateur, avec une époque terrestre puis une époque spatiale">
		<meta name="keywords" content="gestion,stratégie,jeu en ligne,gratuit,navigateur,rapide,simple,guerre">
		<meta name="robots" content="index">
		<meta name="REVISIT-AFTER" content="7 days">
		<meta http-equiv="Content-Language" content="fr">
    </head>
        <body onload="augmentation_ressource()">

<?php include_once'header.php'; ?>



<div id="g_section">
	<div id="band_l"></div>	<div id="band_r"></div>
	<section>


		<?php include_once'menu.php'; ?>
		<div id="corps">
		<h1>Aider Dematale</h1>
		<div class="corps2">


		<p class="padd">Les dons servent à ameliorer l'experience du jeu. Chaque don permet de payer une partie de l'hébergeur ainsi que le nom de domaine.</p>

	<!-- <form class="center_align" action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
		<input type="hidden" name="cmd" value="_s-xclick">
		<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
		">
		<input type="image" src="https://www.paypalobjects.com/fr_FR/FR/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - la solution de paiement en ligne la plus simple et la plus sécurisée !">
		<img alt="" border="0" src="https://www.paypalobjects.com/fr_FR/i/scr/pixel.gif" width="1" height="1">
	</form> -->
    <i>Page don en cours de création</i>


	<?php $req_connecte = $bdd->query('SELECT COUNT(*) FROM connectes');
	$connectes = $req_connecte->fetchColumn(); ?>

	</div></div>
	<?php include_once'footer.php'; ?>
	</section>


</div>

    </body >
</html>
