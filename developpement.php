<?php session_start(); 
if(!isset($_SESSION['pseudo'])) header('Location:index.php');
include_once'actu.php';
include_once'connectes.php'; 

$req1 = $bdd->prepare('SELECT id_alliance,apply FROM membres WHERE id=?');
$req1->execute(array($_SESSION['id']));
$membre = $req1->fetch();

$req2 = $bdd->prepare('SELECT id,nbr_membre FROM alliance WHERE id=?');
$req2->execute(array($membre['id_alliance']));
$alliance = $req2->fetch(); 

if(empty($alliance['nbr_membre']) || $membre['apply']==0)
{
	header('Location:index.php');
} ?>


<!DOCTYPE html>
<html>
	<link rel="icon" type="image/ico" href="favicon.ico" />
    <head>
        <meta charset="utf-8"/>
		<link rel="stylesheet" href="design.css" />
        <title>Développement de l'alliance</title>
    </head>
        <body onload="augmentation_ressource()">


<?php include_once'header.php'; ?>
	
	
	
<div id="g_section">
	<div id="band_l"></div>	<div id="band_r"></div>
	<section>
	
	
	<?php include_once'menu.php'; ?>
	<div id="corps">
	<h1>Développement de l'alliance</h1>

<p class="menu_alliance"><a href="alliance.php">Gestion</a> | <a href="chat_alliance.php">Chat</a> | <a href="coffre.php">Coffre-fort</a> | <a href="alliance/guerre.php">Guerre</a></p>
<?php 
require_once'cnx.php';
				
$req1 = $bdd->prepare('SELECT corruption FROM developpement WHERE id=?');			
$req1->execute(array($alliance['id']));									
$developpement = $req1->fetch(); 	

$req2 = $bdd->prepare('SELECT corruption,popularite FROM cout_alliance WHERE id=?');			
$req2->execute(array($alliance['id']));									
$cout = $req2->fetch(); 		

$req3 = $bdd->prepare('SELECT corruption,popularite FROM niveau_alliance WHERE id=?');			
$req3->execute(array($alliance['id']));									
$niveau = $req3->fetch(); 	

$req4 = $bdd->prepare('SELECT chef FROM alliance WHERE id=?');			
$req4->execute(array($alliance['id']));									
$alliance = $req4->fetch(); 	

?>	



<!--------------On calcul les valeurs des next niveau/prod--------------->
<?php 
?>


<h3> Partie commerciale de l'alliance : </h3>



<?php
$taxe = 30-($niveau['corruption']-1);
$taxe_f = 30-$niveau['corruption'];
?>
<table class="bat05">
	<tr class="bat1">
		<td class="bat-a"><img src="images/alliance/chevalier.png" alt="Corruption" title="Corruption"/></td>
		<td class="bat-r">
			<div class="bat2"><p>Actuel : Taxe de <strong><?php echo $taxe; ?>%</strong></p></div>
			<div class="bat3"><?php if($niveau['corruption']<21){ ?><p>Niveau <?php echo $niveau['corruption']+1;?> : Taxe de <strong><?php echo $taxe_f; ?>%</strong></p><?php }else{ ?><p class="green">Vous avez atteint la taxe minimale !</p><?php } ?></div>
			<div class="bat4"><?php if($niveau['corruption']<21){ ?><p>Coût : <strong><?php echo number_format($developpement['corruption'], 0, '.', ' '); ?></strong> sur <strong/><?php echo number_format($cout['corruption'], 0, '.', ' ');?></strong> <img src="images/paysan.png" height="17" alt="Paysans" align="top" title="Paysans"/></p>
				<form method="post" action="traitement/alliance/developpement/corruption.php" class="center_align">
					<input type="text" name="corruption" id="corruption" size="5"/>
					<input type="submit" name="formuruption" value="Donner des paysans"/>
				</form> <?php }else{ ?><p><br/>Le gardien du coffre est très content d'avoir fait affaire avec vous.</p><?php } ?></div></td>
		<td class="debug"><h5 class="bat_titre">Corruption</h5><p>Vous offrez des paysans au gardien du coffre. En échange, il accepte de faire baisser la taxe qu'il prend à chaque transaction</p></td>
	</tr>
</table>
<?php if((isset($_GET['msg'])) && ($_GET['msg']=='notcorruption')) { ?> <p class="mp_rouge">Vous n'avez pas assez de paysans !</p> <?php } ?>
<?php if((isset($_GET['msg'])) && ($_GET['msg']=='coutcorruption')) { ?> <p class="mp_rouge">Vous ne pouvez pas donner autant de paysans d'un seul coup.</p> <?php } ?>
<?php if((isset($_GET['msg'])) && ($_GET['msg']=='succescorrupt')) { ?> <p class="mp_vert">Vous avez contribué à la technologie corruption !</p> <?php } ?>
<?php if((isset($_GET['msg'])) && ($_GET['msg']=='nbrpaycor')) { ?> <p class="mp_rouge">Vous devez donner au moins 1 paysan !</p> <?php } ?>
<br/>

<table class="bat05">
	<tr class="bat1">
		<td class="bat-a"><img src="images/alliance/popularite.png" alt="Popularité" title="Popularité"/></td>
		<td class="bat-r">
			<div class="bat2"><p>Actuel :  <?php if($niveau['popularite']==1){echo 'Aucun bonus'; }else{ echo'<strong>'.$niveau['popularite'].'x</strong> plus de paysans'; } ?></p></div>
			<div class="bat3"><p>Niveau <?php echo $niveau['popularite']+1;?> : <strong><?php echo $niveau['popularite']+1; ?>x</strong> plus de paysans</p></div>
			<div class="bat4"><p>Coût : <strong><?php echo number_format($cout['popularite'], 0, '.', ' '); ?></strong> <img src="images/coffre.png" height="17" alt="Coffre" align="top" title="Coffre"/> <img src="images/gold_icon.png" height="17" alt="or" align="top" title="or"/></p>
				<?php if($alliance['chef']===$_SESSION['pseudo']){ ?> <form method="post" action="traitement/alliance/developpement/popularite.php" class="center_align">
					<input type="submit" name="formupopu" value="Niveau suivant"/>
				</form>  <?php }else{echo '<p class="color_blue"><br/>Seul le chef peut monter ce bâtiment.<p>'; } ?>
				

		
				
				
				</div></td>
		<td class="debug"><h5 class="bat_titre">Popularité</h5><p>Votre alliance prend de plus en plus d'envergure, ce qui attire les paysans à venir vous rejoindre. Vous devez en contre partie leur donner un peu d'or !</p></td>
	</tr>
</table>
<?php if((isset($_GET['msg'])) && ($_GET['msg']=='coutpopu')) { ?> <p class="mp_rouge">Vous n'avas pas assez d'or dans le coffre pour augmenter la popularité de votre alliance !</p> <?php } ?>
<?php if((isset($_GET['msg'])) && ($_GET['msg']=='succespopu')) { ?> <p class="mp_vert">Bravo, la popularité de votre alliance monte d'un cran !</p> <?php } ?>
	
	
	 



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