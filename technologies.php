<?php session_start(); 
if(!isset($_SESSION['pseudo'])) header('Location:index.php'); 
include_once'actu.php'; 
include_once'connectes.php'; ?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
		<link rel="stylesheet" href="design.css" />
        <title>Technologies</title>
    </head>
        <body onload="augmentation_ressource()">
<?php include_once'header.php'; ?>
	
	
	
<div id="g_section">
	<div id="band_l"></div>	<div id="band_r"></div>
	<section>
	
	
	<?php include_once'menu.php'; ?>
	<div id="corps">
	<h1>Département des technologies</h1>
	
	
<?php 
$req01 = $bdd->prepare('SELECT coupe,level FROM membres WHERE id=?');
$req01->execute(array($_SESSION['id']));
$info = $req01->fetch();

$req1 = $bdd->prepare('SELECT vitesse,centre_travail,grenier,hotel_ventes FROM production WHERE id=?');		
$req1->execute(array($_SESSION['id']));									
$tech = $req1->fetch(); 

$req2 = $bdd->prepare('SELECT espio,vitesse,centre_travail,grenier,hv_fer,hv_roche,co_fer,co_gold FROM cout WHERE id=?');					
$req2->execute(array($_SESSION['id']));									
$cout = $req2->fetch(); 

$req3 = $bdd->prepare('SELECT espionnage,vitesse,grenier,commerce,hotel_ventes FROM niveau WHERE id=?');					
$req3->execute(array($_SESSION['id']));									
$niveau = $req3->fetch();

$req4 = $bdd->prepare('SELECT paysan FROM ressources WHERE id=?');					
$req4->execute(array($_SESSION['id']));									
$ressources = $req4->fetch();

$centre_travail_f = $tech['centre_travail']+5;

if($info['coupe']==1 || $info['coupe']==4)
{
	$espio_gold = $cout['espio']*0.95;
	$vitesse_gold = $cout['vitesse']*0.95;
	$centre_paysan = $cout['centre_travail']-1;
	$grenier_gold = $cout['grenier']*0.95;
	$hv_fer = $cout['hv_fer']*0.95;
	$hv_roche = $cout['hv_roche']*0.95;
	$co_fer = $cout['co_fer']*0.95;
	$co_gold = $cout['co_gold']*0.95;
}
else
{
	$espio_gold = $cout['espio'];
	$vitesse_gold = $cout['vitesse'];
	$centre_paysan = $cout['centre_travail'];
	$grenier_gold = $cout['grenier'];
	$hv_fer = $cout['hv_fer'];
	$hv_roche = $cout['hv_roche'];
	$co_fer = $cout['co_fer'];
	$co_gold = $cout['co_gold'];
}
?>

<h3>Technologies de base : </h3>

<table class="bat05">
	<tr class="bat1">
		<td class="bat-a"><img src="images/technologies/espionnage.jpg" alt="L'espionnage" title="L'espionnage"/></td>
		<td class="bat-r">
			<div class="bat2"><p>Espionnage de <em> niveau <?php echo $niveau['espionnage'];?> </em></div>
			<div class="bat3"><p>Espionnage niveau superieur</p></div>
			<div class="bat4"><p>Coût : <strong><?php echo number_format($espio_gold, 0, '.', ' '); ?></strong> <img src="images/gold_icon.png" alt="Icon or" align="top" title="Or"/></p>
				<form method="post" action="traitement/technologies/espionnage.php" class="center_align">
					<input type="submit" name="sube" value="Niveau suivant"/>
				</form>
		<td class="debug"><h5 class="bat_titre">Espionnage</h5><p>Plus votre technique d'espionnage est haut-niveau, plus vous arriverez à subtiliser des informations à votre adversaire !</p></td>
	</tr>
</table>
<?php if((isset($_GET['msg'])) && ($_GET['msg']=='orspy')) { ?> <p class="msg_ress">Vous n'avez pas assez d'or !</p> <?php } ?>
<br/>

<table class="bat05">
	<tr class="bat1">
		<td class="bat-a"><img src="images/technologies/vitesse.png" alt="La vitesse" title="La vitesse"/></td>
		<td class="bat-r">
			<div class="bat2"><p>Vitesse de <em> niveau <?php echo $niveau['vitesse'];?> </em></div>
			<div class="bat3"><?php if($niveau['vitesse']<5){ ?><p>Vitesse niveau superieur</p><?php }else{ ?><p class="green">Niveau max. atteint</p><?php } ?></div>
			<div class="bat4"><p><?php if($niveau['vitesse']<5){ ?>Coût : <strong><?php echo number_format($vitesse_gold, 0, '.', ' '); ?></strong> <img src="images/gold_icon.png" alt="Icon or" align="top" title="Or"/></p>
				<form method="post" action="traitement/technologies/vitesse.php" class="center_align">
					<input type="submit" name="subra" value="Niveau suivant" />
				</form><?php }else{ ?><p>Vos troupes sont maintenant les rois de<br/> la vitesse !</p><?php } ?>
		<td class="debug"><h5 class="bat_titre">Vitesse</h5><p>Vos troupes augmentent leur vitesse d'attaque en fonction du niveau de la technologie</p></td>
	</tr>
</table>
<?php if(isset($_GET['error2'])) { ?> <p class="msg_ress">Vous n'avez pas assez d'or !</p> <?php } ?>
<br/>

<table class="bat05">
	<tr class="bat1">
		<td class="bat-a"><img src="images/technologies/centre_travail.png" alt="Centre du travail" title="Centre du travail"/></td>
		<td class="bat-r">
			<div class="bat2"><p>Production actuelle :<em> + <?php echo $tech['centre_travail'];?> <img src="images/roche_icon.png" alt="Icon roche" align="top" title="Roche"/><img src="images/fer_icon.png" alt="Icon fer" align="top" title="Fer"/><img src="images/bois_icon.png" alt="Icon Bois" align="top" title="Bois"/></em></div>
			<div class="bat3"><p>Prochain bonus :<em> + <?php echo $centre_travail_f;?> <img src="images/roche_icon.png" alt="Icon roche" align="top" title="Roche"/><img src="images/fer_icon.png" alt="Icon fer" align="top" title="Fer"/><img src="images/bois_icon.png" alt="Icon Bois" align="top" title="Bois"/></em></div>
			<div class="bat4"><p>Coût : <strong><?php echo number_format($centre_paysan, 0, '.', ' '); ?></strong> paysans</p>
				<form method="post" action="traitement/centre_travail.php" class="center_align_t"> 
					<input type="submit" name="subct" value="Faire travailler <?php echo $centre_paysan;?> paysans" />
				</form>
				<?php if($ressources['paysan']>=20)
				{ ?>
				<form method="post" action="traitement/batiments/paysan_full.php" class="center_align_t"> 
					<input type="submit" name="subfp" value="Faire travailler tous ses paysans" />
				</form>
				<?php } ?>
		<td class="debug"><h5 class="bat_titre">Centre du travail</h5><p>C'est ici que vous pouvez faire travailler vos paysans. Ils seront affectés à des taches et vous aideront à récolter du fer, de la roche et du bois <strong>toutes les heures</strong>.</p></td>
	</tr>
</table>
<?php if(isset($_GET['erreur']) && $_GET['erreur']=='ct' ) { ?> <p class="msg_ress">Vous n'avez pas assez de paysans à envoyer travailler !</p> <?php } ?>
<br/>

<?php 
$grenier_f = $tech['grenier'] + $niveau['grenier']*75;
$niv_grenier_f =  $niveau['grenier']+1;
?>


<table class="bat05">
	<tr class="bat1">
		<td class="bat-a"><img src="images/technologies/grenier.png" alt="Grenier" title="Grenier"/></td>
		<td class="bat-r">
			<div class="bat2"><p>Cachette actuelle : <em><?php echo number_format($tech['grenier'], 0, '.', ' ');?> ressources</em></div>
			<div class="bat3"><p>Niveau  <?php echo $niv_grenier_f;?> : <em><?php echo number_format($grenier_f, 0, '.', ' ');?> ressources</em></div>
			<div class="bat4"><p>Coût : <strong><?php echo number_format($grenier_gold, 0, '.', ' ');?></strong> <img src="images/bois_icon.png" alt="Icon du bois" align="top" title="Bois"/></p>
				<form method="post" action="traitement/technologies/grenier.php" class="center_align"> 
					<input type="submit" name="subgr" value="Niveau suivant" />
				</form>
		<td class="debug"><h5 class="bat_titre">Grenier</h5><p>Le grenier permet de cacher des ressources lors d'une attaque. Attention, le grenier ne permet pas de cacher son Titan !</p></td>
	</tr>
</table>
<?php if(isset($_GET['erreur']) && $_GET['erreur']=='gr' ) { ?> <p class="msg_ress">Vous n'avez pas assez de bois !</p> <?php } ?>
<br/>


<?php if($info['level']>0)
{ 
$niv_hv_f = $niveau['hotel_ventes']+1;
$hotel_ventes_f = $tech['hotel_ventes']+30000+(5000*$niveau['hotel_ventes']);


?>
	<h3>Technologies de niveau 1 : </h3>
	<table class="bat05">
		<tr class="bat1">
			<td class="bat-a"><img src="images/technologies/hotel_ventes.png" alt="Hôtel des ventes" title="Hôtel des ventes"/></td>
			<td class="bat-r">
				<div class="bat2"><p>Actuel : <em><?php echo number_format($tech['hotel_ventes'], 0, '.', ' ');?> <img src="images/gold_icon.png" alt="Icon or" align="top" title="Or"/> / jour</em></div>
				<div class="bat3"><p>Niveau <?php echo $niv_hv_f; ?> : <em><?php echo number_format($hotel_ventes_f, 0, '.', ' ');?> <img src="images/gold_icon.png" alt="Icon or" align="top" title="Or"/> / jour</em></div>
				<div class="bat4"><p>Coût : <strong><?php echo number_format($hv_fer, 0, '.', ' ');?></strong> <img src="images/fer_icon.png" alt="Icon fer" align="top" title="Fer"/> | <strong><?php echo number_format($hv_roche, 0, '.', ' ');?></strong> <img src="images/roche_icon.png" alt="Icon roche" align="top" title="Roche"/></p>
					<form method="post" action="traitement/batiments/hotel_ventes.php" class="center_align"> 
						<input type="submit" name="subhv" value="Niveau suivant" />
					</form>
			<td class="debug"><h5 class="bat_titre">Hôtel des ventes</h5><p>L'Hôtel des ventes vous rapporte une certaine quantité d'or chaque jour lors du récapitulatif. Le montant n'est jamais fixe car il dépend de la bourse !</p></td>
		</tr>
	</table>
	<?php if(isset($_GET['erreur']) && $_GET['erreur']=='hv' ) { ?> <p class="msg_ress">Vous n'avez pas assez de fer !</p> <?php } ?>
	<br/> <?php
}; ?>

<?php if($info['level']>1)
{ $niv_commerce_f = $niveau['commerce']+1;
$echange_actuel = $niveau['commerce']-1;
$échange_futur = $niveau['commerce'];
?>
	<h3>Technologies de niveau 2 : </h3>
	<table class="bat05">
		<tr class="bat1">
			<td class="bat-a"><img src="images/technologies/commerce.png" alt="Commerce" title="Commerce"/></td>
			<td class="bat-r">
				<div class="bat2"><p><?php if($niveau['commerce']==1){echo '<span class="red">Technologie commerce non débloquée</span>';}else{echo 'Actuel : <em>'.$echange_actuel.' échange(s)</em>';} ?></div>
				<div class="bat3"><p><?php if($niveau['commerce']==1){echo '<span class="color_blue">Payez pour pouvoir faire des échanges !</span>';}else{echo 'Niveau '.$niv_commerce_f.' : <em>'.$échange_futur.' échange(s)</em>';} ?></div>
				<div class="bat4"><p>Coût : <strong><?php if($niveau['commerce']==1){echo '<strong>30 000<img src="images/bois_icon.png" alt="Icon bois" align="top" title="Bois"/> | 30 000 <img src="images/roche_icon.png" alt="Icon roche" align="top" title="Roche"/> | 30 000 <img src="images/fer_icon.png" alt="Icon fer" align="top" title="Fer"/> | 50 000</strong> <img src="images/gold_icon.png" alt="Icon or" align="top" title="Or"/></p>';}else{echo ''.number_format($co_fer, 0, '.', ' ').' <img src="images/fer_icon.png" alt="Icon fer" align="top" title="Fer"/> | '.number_format($co_gold, 0, '.', ' ').' <img src="images/gold_icon.png" alt="Icon or" align="top" title="Or"/>';} ?></strong></p>
					<form method="post" action="traitement/batiments/commerce.php" class="center_align"> 
						<input type="submit" name="subcom" <?php if($niveau['commerce']==1){echo 'value="Débloquer le commerce !"';}else{echo 'value="Niveau suivant"';} ?> />
					</form>
			<td class="debug"><h5 class="bat_titre">Commerce</h5><p>Cette technologie sert à débloquer le commerce. Une fois que le commerce est débloqué, augmenter la technologie permet d'échanger plus de ressources en même temps.</p></td>
		</tr>
	</table>
	<?php if(isset($_GET['error']) && $_GET['error']=='com' ) { ?> <p class="msg_ress">Vous n'avez pas assez de ressources !</p> <?php } ?>
	<br/> <?php
}; ?>




	<?php $req_connecte = $bdd->query('SELECT COUNT(*) FROM connectes');
	$connectes = $req_connecte->fetchColumn(); ?>

	</div>
	<?php include_once'footer.php'; ?>
	</section>
	
	
	</div>
    </body >
</html>

