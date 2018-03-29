<?php session_start(); 
if(!isset($_SESSION['pseudo'])) {header('Location:index.php');}
include_once'actu.php';
include_once'connectes.php'; ?>


<!DOCTYPE html>
<html>
	<link rel="icon" type="image/ico" href="favicon.ico" />
    <head>
        <meta charset="utf-8"/>
		<link rel="stylesheet" href="design.css" />
        <title>Bâtiments</title>
    </head>
        <body onload="augmentation_ressource()">


<?php include_once'header.php'; ?>
	
	
	
<div id="g_section">
	<div id="band_l"></div>	<div id="band_r"></div>
	<section>
	
	
	<?php include_once'menu.php'; ?>
	<div id="corps">
	<h1>Bâtiments</h1>


<?php 
require_once'cnx.php';
											
$req1 = $bdd->prepare('SELECT fer,roche,gold,bois,titan,mur FROM production WHERE id=?');			
$req1->execute(array($_SESSION['id']));									
$production = $req1->fetch(); 

$req2 = $bdd->prepare('SELECT fer_bois,fer_roche,bois_fer,bois_roche,roche_fer,roche_bois,gold_fer,gold_bois,gold_roche,titan_bois,titan_fer,roche_mur,forge,fer_temple,bois_temple FROM cout WHERE id = ?');			
$req2->execute(array($_SESSION['id']));									
$cout = $req2->fetch(); 

$req3 = $bdd->prepare('SELECT level,decret,coupe FROM membres WHERE id=?');
$req3->execute(array($_SESSION['id']));
$membre = $req3->fetch();

$req4 = $bdd->prepare('SELECT fer,roche,gold,bois,titan,mur,forge,temple FROM niveau WHERE id=?');			
$req4->execute(array($_SESSION['id']));									
$niveau = $req4->fetch(); 

$req5 = $bdd->prepare('SELECT guerrisseur FROM ressources WHERE id=?');			
$req5->execute(array($_SESSION['id']));									
$ressources = $req5->fetch(); 
?>	



<!--------------On calcul les valeurs des next niveau/prod--------------->
<?php 
include_once'fonction.php';

$prod_fer_f = ($production['fer']*1.1)+(8*$niveau['fer']);	
$prod_fer_f = DegTruncation(floor($prod_fer_f));						
$niv_fer_f=$niveau['fer']+1;								

$prod_bois_f = ($production['bois']*1.1)+(8*$niveau['bois']);	
$prod_bois_f = DegTruncation(floor($prod_bois_f));					
$niv_bois_f=$niveau['bois']+1;								

$prod_roche_f = ($production['roche']*1.1)+(8*$niveau['roche']);	
$prod_roche_f = DegTruncation(floor($prod_roche_f));						
$niv_roche_f=$niveau['roche']+1;

$prod_gold_f = ($production['gold']*1.1)+(8*$niveau['gold']);	
$prod_gold_f = DegTruncation(floor($prod_gold_f));						
$niv_gold_f = $niveau['gold']+1;	


$def_mur_f=DegTruncation($production['mur']*2);
$roche_mur=$cout['roche_mur'];	
$niv_mur_f=$niveau['mur']+1;		

$prod_gold = $production['gold'];
$prod_fer = $production['fer'];
$prod_bois = $production['bois'];
$prod_roche = $production['roche'];
if($membre['decret'] == 1)
{
	$prod_gold = floor($production['gold']*1.05);
	$prod_gold_f = floor($prod_gold_f*1.05);
}
if($membre['decret'] == 2)
{
	$prod_fer = floor($production['fer']*1.02);
	$prod_bois = floor($production['bois']*1.02);
	$prod_roche = floor($production['roche']*1.02);
	
	$prod_fer_f = floor($prod_fer_f*1.02);
	$prod_bois_f = floor($prod_bois_f*1.02);
	$prod_roche_f = floor($prod_roche_f*1.02);
}
?>


<h3>Bâtiments de base :</h3>



<?php
if($membre['coupe']==3 || $membre['coupe']==4)
{
	$fer_bois = floor($cout['fer_bois']*0.85);
	$fer_roche = floor($cout['fer_roche']*0.85);
	$bois_fer = floor($cout['bois_fer']*0.85);
	$bois_roche = floor($cout['bois_roche']*0.85);
	$roche_fer = floor($cout['roche_fer']*0.85);
	$roche_bois = floor($cout['roche_bois']*0.85);
	$gold_fer = floor($cout['gold_fer']*0.85);
	$gold_bois = floor($cout['gold_bois']*0.85);
	$gold_roche = floor($cout['gold_roche']*0.85);
	$roche_mur = floor($cout['roche_mur']*0.85);
	if($membre['level'] >= 3)
	{	
		$fer_temple = $cout['fer_temple']*0.85;
		$bois_temple = $cout['bois_temple']*0.85;
	}
	if($membre['level'] >= 5)
	{	
		$titan_fer = $cout['titan_fer']*0.85;
		$titan_bois = $cout['titan_bois']*0.85;
	}
}
else
{
	$fer_bois = $cout['fer_bois'];
	$fer_roche = $cout['fer_roche'];
	$bois_fer = $cout['bois_fer'];
	$bois_roche = $cout['bois_roche'];
	$roche_fer = $cout['roche_fer'];
	$roche_bois = $cout['roche_bois'];
	$gold_fer = $cout['gold_fer'];
	$gold_bois = $cout['gold_bois'];
	$gold_roche = $cout['gold_roche'];
	$roche_mur = $cout['roche_mur'];
	if($membre['level'] >= 3)
	{	
		$fer_temple = $cout['fer_temple'];
		$bois_temple = $cout['bois_temple'];
	}
	if($membre['level'] >= 5)
	{	
		$titan_fer = $cout['titan_fer'];
		$titan_bois = $cout['titan_bois'];
	}
}
?>




<table class="bat05">
	<tr class="bat1">
		<td class="bat-a"><img src="images/batiments/fer.png" alt="Mine de fer" title="Mine de fer"/></td>
		<td class="bat-r">
			<div class="bat2"><p>Actuel :<strong> <?php if($membre['decret'] == 2){ echo '<span class="bleu">'.number_format($prod_fer, 0, '.', ' ').'</span>';}else{echo number_format($prod_fer, 0, '.', ' ');}?></strong> <img src="images/fer_icon.png" alt="Icon fer" align="top" title="Fer"/> / heure</p></div>
			<div class="bat3"><p>Niveau <?php echo $niv_fer_f;?> : <strong><?php if($membre['decret'] == 2){ echo '<span class="bleu">'.number_format($prod_fer_f, 0, '.', ' ').'</span>';}else{echo number_format($prod_fer_f, 0, '.', ' ');}?></strong> <img src="images/fer_icon.png" alt="Icon fer" align="top" title="Fer"/> / heure</p></p></div>
			<div class="bat4"><p>Coût : <strong><?php echo number_format($fer_bois, 0, '.', ' ');?></strong> <img src="images/bois_icon.png" alt="Icon bois" align="top" title="Bois"/> | <strong><?php echo number_format($fer_roche, 0, '.', ' ');?></strong> <img src="images/roche_icon.png" alt="Icon roche" align="top" title="Roche"/></p>
				<form method="post" action="traitement/batiments/fer.php" class="center_align">
					<input type="submit" name="subf" value="Niveau suivant" />
				</form></div></td>
		<td class="debug"><h5 class="bat_titre">Mine de fer</h5><p>Ce batiment fourni votre production principale de fer. </p></td>
	</tr>
</table>
<?php if((isset($_GET['error'])) && ($_GET['error']=='b_fer')) { ?> <p class="msg_ress">Vous n'avez pas assez de ressources !</p> <?php } ?>
<br/>


<table class="bat05">
	<tr class="bat1">
		<td class="bat-a"><img src="images/batiments/bois.png" alt="Mine de bois" title="Mine de bois"/></td>
		<td class="bat-r">
			<div class="bat2"><p>Actuel :<strong> <?php if($membre['decret'] == 2){ echo '<span class="bleu">'.number_format($prod_bois, 0, '.', ' ').'</span>';}else{echo number_format($prod_bois, 0, '.', ' ');}?></strong>  <img src="images/bois_icon.png" alt="Icon bois" align="top" title="Bois"/> / heure</p></div>
			<div class="bat3"><p>Niveau <?php echo $niv_bois_f;?> : <strong><?php if($membre['decret'] == 2){ echo '<span class="bleu">'.number_format($prod_bois_f, 0, '.', ' ').'</span>';}else{echo number_format($prod_bois_f, 0, '.', ' ');}?></strong>  <img src="images/bois_icon.png" alt="Icon bois" align="top" title="Bois"/> / heure</p></p></div>
			<div class="bat4"><p>Coût : <strong><?php echo number_format($bois_fer, 0, '.', ' ');?></strong> <img src="images/fer_icon.png" alt="Icon fer" align="top" title="Fer"/> | <strong><?php echo number_format($bois_roche, 0, '.', ' ');?></strong> <img src="images/roche_icon.png" alt="Icon roche" align="top" title="Roche"/></p>
				<form method="post" action="traitement/batiments/bois.php" class="center_align">
					<input type="submit" name="subb" value="Niveau suivant" />
				</form></div></td>
		<td class="debug"><h5 class="bat_titre">Récolteur de bois</h5><p>Ce batiment fourni votre production principale de bois.</p></td>
	</tr>
</table>
<?php if((isset($_GET['error'])) && ($_GET['error']=='b_bois')) { ?> <p class="msg_ress">Vous n'avez pas assez de ressources !</p> <?php } ?>
<br/>

<table class="bat05">
	<tr class="bat1">
		<td class="bat-a"><img src="images/batiments/roche.png" alt="Mine de roche" title="Mine de roche"/></td>
		<td class="bat-r">
			<div class="bat2"><p>Actuel :<strong> <?php if($membre['decret'] == 2){ echo '<span class="bleu">'.number_format($prod_roche, 0, '.', ' ').'</span>';}else{echo number_format($prod_roche, 0, '.', ' ');}?></strong> <img src="images/roche_icon.png" alt="Icon roche" align="top" title="Roche"/> / heure</p></div>
			<div class="bat3"><p>Niveau <?php echo $niv_roche_f;?> : <strong><?php if($membre['decret'] == 2){ echo '<span class="bleu">'.number_format($prod_roche_f, 0, '.', ' ').'</span>';}else{echo number_format($prod_roche_f, 0, '.', ' ');}?></strong> <img src="images/roche_icon.png" alt="Icon roche" align="top" title="Roche"/> / heure</p></p></div>
			<div class="bat4"><p>Coût : <strong><?php echo number_format($roche_fer, 0, '.', ' ');?></strong> <img src="images/fer_icon.png" alt="Icon fer" align="top" title="Fer"/> | <strong><?php echo number_format($roche_bois, 0, '.', ' ');?></strong>  <img src="images/bois_icon.png" alt="Icon bois" align="top" title="Bois"/></p>
				<form method="post" action="traitement/batiments/roche.php" class="center_align">
					<input type="submit" name="subr" value="Niveau suivant" />
				</form></div></td>
		<td class="debug"><h5 class="bat_titre">Extracteur de roche</h5><p>Ce batiment vous fourni en roche.</td>
	</tr>
</table>
<?php if((isset($_GET['error'])) && ($_GET['error']=='b_roche')) { ?> <p class="msg_ress">Vous n'avez pas assez de ressources !</p> <?php } ?>
<br/>

<table class="bat05">
	<tr class="bat1">
		<td class="bat-a"><img src="images/batiments/or.png" alt="Générateur d'or" title="Générateur d'or"/></td>
		<td class="bat-r">
			<div class="bat2"><p>Actuel :<strong> <?php if($membre['decret'] == 1){ echo '<span class="bleu">'.number_format($prod_gold, 0, '.', ' ').'</span>';}else{echo number_format($prod_gold, 0, '.', ' ');}?></strong> <img src="images/gold_icon.png" alt="Icon or" align="top" title="Or"/> / heure</p></div>
			<div class="bat3"><p>Niveau <?php echo $niv_gold_f;?> : <strong><?php if($membre['decret'] == 1){ echo '<span class="bleu">'.number_format($prod_gold_f, 0, '.', ' ').'</span>';}else{echo number_format($prod_gold_f, 0, '.', ' ');}?></strong> <img src="images/gold_icon.png" alt="Icon or" align="top" title="Or"/> / heure</p></p></div>
			<div class="bat4"><p>Coût : <strong><?php echo number_format($gold_fer, 0, '.', ' ');?></strong> <img src="images/fer_icon.png" alt="Icon fer" align="top" title="Fer"/> | <strong><?php echo number_format($gold_bois, 0, '.', ' ');?></strong>  <img src="images/bois_icon.png" alt="Icon bois" align="top" title="Bois"/> | <strong><?php echo number_format($gold_roche, 0, '.', ' ');?></strong> <img src="images/roche_icon.png" alt="Icon roche" align="top" title="Roche"/></p>
				<form method="post" action="traitement/batiments/gold.php" class="center_align">
					<input type="submit" name="subo" value="Niveau suivant" />
				</form></div></td>
		<td class="debug"><h5 class="bat_titre">Générateur d'or</h5><p>Ce batiment fourni votre production principale d'or. Il fournit une des ressources la plus importante du jeu. </p></td>
	</tr>
</table>
<?php if((isset($_GET['error'])) && ($_GET['error']=='b_gold')) { ?> <p class="msg_ress">Vous n'avez pas assez de ressources !</p> <?php } ?>
<br/>


<table class="bat05">
	<tr class="bat1">
		<td class="bat-a"><img src="images/batiments/mur.jpg" alt="Le mur" title="Le mur" height="100" width="100" /></td>
		<td class="bat-r">
			<div class="bat2"><p>Actuel :<strong> +<?php echo number_format($production['mur'], 0, '.', ' ');?></strong> Défense</p></div>
			<div class="bat3"><p>Niveau <?php echo $niv_mur_f;?> : <strong> +<?php echo number_format($def_mur_f, 0, '.', ' ');?></strong> Défense</p></p></div>
			<div class="bat4"><p>Coût : <strong><?php echo number_format($roche_mur, 0, '.', ' ');?></strong> <img src="images/roche_icon.png" alt="Icon roche" align="top" title="Roche"/></p> 
				<form method="post" action="traitement/batiments/mur.php" class="center_align">
					<input type="submit" name="subm" value="Niveau suivant" />
				</form></div></td>
		<td class="debug"><h5 class="bat_titre">Le mur</h5><p>Le mur vous protège de vos ennemis. Il augmente votre défense mais il vous faut beaucoup de roche pour le construire.</p></td>
	</tr>
</table>
<?php if((isset($_GET['error'])) && ($_GET['error']=='r_mur')) { ?> <p class="msg_ress">Vous n'avez pas assez de roche !</p> <?php } ?>
<br/>


<?php 
if($membre['level']>=3)
{ ?> 
	<h3>Bâtiments de niveau 3 :</h3> <?php
	$temple_future = $niveau['temple']*2+2;
	$niv_temple_f = $niveau['temple']+1;
	?> 
	<table class="bat05">
	<tr class="bat1">
		<td class="bat-a"><img src="images/batiments/temple.png" alt="Temple des voeux" title="Temple des voeux"/></td>
		<td class="bat-r">
			<div class="bat2"><p>Actuel : Permet <strong><?php echo number_format(($niveau['temple']*2), 0, '.', ' ');?> guérisseurs </strong></p></div>
			<div class="bat3"><p>Niveau <?php echo $niveau['temple'];?> : Permet <strong><?php echo number_format($niveau['temple']*2+2, 0, '.', ' ');?> guérisseurs</strong></p></p></div>
			<div class="bat4"><p>Coût : <strong><?php echo number_format($fer_temple, 0, '.', ' ');?></strong> <img src="images/fer_icon.png" alt="Icon fer" align="top" title="Fer"/> | <strong><?php echo number_format($bois_temple, 0, '.', ' ');?></strong>  <img src="images/bois_icon.png" alt="Icon bois" align="top" title="Bois"/></p>
				<form method="post" action="traitement/batiments/temple.php" class="center_align">
					<input type="submit" name="subtemple" value="Niveau suivant" />
				</form><p>Vous avez actuellement <strong><?php echo $ressources['guerrisseur']; ?></strong> guérrisseur(s)</p></div></td>
		<td class="debug"><h5 class="bat_titre">Temple des voeux</h5><p>Le temple des voeux permet d'accueillir des guerrisseurs. Chaque guérisseur permet de réssuciter 0.5% de ses troupes après chaque combat. La limite de ce bâtiment est de 20% des troupes perdues</td>
	</tr>
	</table><br/>
	<?php if((isset($_GET['msg'])) && ($_GET['msg']=='temple')) { ?> <p class="msg_ress">Vous n'avez pas assez de ressources pour augementer le temple.</p> <?php }}
	
	

if($membre['level']>=5)
{ 	
	?> 
	
	<h3>Bâtiments de niveau 5 :</h3> <?php
	$prod_titan_f = DegTruncation(floor($production['titan']*1.1+4*$niveau['titan']));
	$niv_titan_f = $niveau['titan']+1;
	?> 
	<table class="bat05">
	<tr class="bat1">
		<td class="bat-a"><img src="images/batiments/titan.png" alt="Mine de titan" title="Mine de titan"/></td>
		<td class="bat-r">
			<div class="bat2"><p>Actuel :<strong> <?php echo number_format($production['titan'], 0, '.', ' ');?></strong> <img src="images/titane_icon.png" alt="Icon titane" align="top" title="Titane"/>/ heure</p></div>
			<div class="bat3"><p>Niveau <?php echo $niv_titan_f;?> : <strong><?php echo number_format($prod_titan_f, 0, '.', ' ');?></strong> <img src="images/titane_icon.png" alt="Icon titane" align="top" title="Titane"/>/ heure</p></p></div>
			<div class="bat4"><p>Coût : <strong><?php echo number_format($titan_fer, 0, '.', ' ');?></strong> <img src="images/fer_icon.png" alt="Icon fer" align="top" title="Fer"/> | <strong><?php echo number_format($titan_bois, 0, '.', ' ');?></strong>  <img src="images/bois_icon.png" alt="Icon bois" align="top" title="Bois"/></p>
				<form method="post" action="traitement/batiments/titan.php" class="center_align">
					<input type="submit" name="subt" value="Niveau suivant" />
				</form></div></td>
		<td class="debug"><h5 class="bat_titre">Mine de titan</h5><p>Ce batiment fourni votre production principale de titan. Il se débloque après avoir passé le niveau 5</td>
	</tr>
	</table><br/>
	<?php if((isset($_GET['error'])) && ($_GET['error']=='b_titan')) { ?> <p class="msg_ress">Vous n'avez pas assez de ressources !</p> <?php } ?>
	
	
	
<?php $niv_forge_f=$niveau['forge']+1; $prod_forge = $niveau['forge']*3; $prod_forge_f = $prod_forge+3; $forge_titane=$cout['forge']; ?>
	<table class="bat05">
	<tr class="bat1">
		<td class="bat-a"><img src="images/batiments/forge.png" alt="Forge" title="Forge"/></td>
		<td class="bat-r">
			<div class="bat2"><p>Actuel :<strong> <?php if($niveau['forge']>=1){echo number_format($prod_forge, 0, '.', ' ').'</strong> % d\'attaque supplémentaire';}else{echo 'Aucun bonus';}?></strong></p></div>
			<div class="bat3"><p><?php if($niveau['forge']==5){echo 'Niveau max atteint.';}else{ ?> Niveau <?php echo $niv_forge_f;?> : <strong><?php echo number_format($prod_forge_f, 0, '.', ' ');?>%</strong> d'attaque supplémentaire <?php } ?></p></p></div>
			<div class="bat4"><p><?php 
			if($niveau['forge']==5){echo 'Votre forgeron développe les meilleurs armes qui existent sur le marché !';}
			else{ ?>
			Coût : <strong><?php echo number_format($forge_titane, 0, '.', ' '); ?></strong> <img src="images/titane_icon.png" alt="Icon titane" align="top" title="Titane"/></p>
				<form method="post" action="traitement/batiments/forge.php" class="center_align">
					<input type="submit" name="subfo" value="Niveau suivant" />
				</form></div></td> <?php } ?>
		<td class="debug"><h5 class="bat_titre">Forge</h5><p>La forge est un outil puissant qui vous permet d'augmenter l'attaque des vos troupes en leur fournissant des armes de qualité.</td>
	</tr>
	</table>
	<?php if((isset($_GET['erreur'])) && ($_GET['erreur']=='forge')) { ?> <p class="msg_ress">Vous n'avez pas assez de titan pour ameliorer la forge !</p> <?php } ?>





<?php } ?> <!--Fin du level 5--> 
	
	
	 



<?php 
$req1->closeCursor();
$req2->closeCursor(); 
 $req_connecte = $bdd->query('SELECT COUNT(*) FROM connectes');
	$connectes = $req_connecte->fetchColumn(); ?>
	
	</div>
	<?php include_once'footer.php'; ?>
	</section>
	
	</div>
    </body >
</html>