<?php session_start(); 
include_once'actu.php';
include_once'connectes.php';
include_once'fonction.php';


$req1 = $bdd->prepare('SELECT id,points,avatar,etat,id_alliance,apply FROM membres WHERE pseudo = ?');
$req1->execute(array($_GET['pseudo']));
$profil1 = $req1->fetch();

if(!isset($_SESSION['id']) || (!isset($_GET['pseudo']))) header('Location:classement.php'); 


$req2 = $bdd->prepare('SELECT decret,points FROM membres WHERE id=?');
$req2->execute(array($_SESSION['id']));
$profil2 = $req2->fetch();

$req3 = $bdd->prepare('SELECT nom FROM alliance WHERE id=?');
$req3->execute(array($profil1['id_alliance']));
$alliance = $req3->fetch();

$difpts = $profil2['points'] - $profil1['points'];

if($profil2['decret']!=3)
{
	if($profil1['points']>1)
	{
		$or_espio = 5*$profil1['points'];
		$or_espio = 'de <em>'.$or_espio.'</em> <img src="images/gold_icon.png" alt="Icon or" align="top" title="Or"/>';				
	}
	else
	{
		$or_espio = 'de <em>5</em> <img src="images/gold_icon.png" alt="Icon or" align="top" title="Or"/>';  
	}
}
else
{
	if($profil1['points']<=3)
	{
		$or_espio = '<em>gratuit</em>';
	}
	else
	{
		$or_espio = floor((5*$profil1['points'])/15);
		$or_espio = 'de <em>'.$or_espio.'</em> <img src="images/gold_icon.png" alt="Icon or" align="top" title="Or"/>';	
	}
}
?>


<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
		<link rel="stylesheet" href="design.css" />
        <title>Profil</title>
    </head>
        <body onload="augmentation_ressource()">
<?php include_once'header.php'; ?>
	
	
	
<div id="g_section">
	<div id="band_l"></div>	<div id="band_r"></div>
	<section>
	
	
	<?php include_once'menu.php'; ?>
	<div id="corps">
	<h1>Profil joueur</h1>				
					
					<div class="pro1">
						<div class="pro2"><p class="profil_text"><?php echo $_GET['pseudo']; ?><?php if(!empty($alliance['nom']) && $profil1['apply']==1)echo ' - '.$alliance['nom']; ?> - <?php echo $profil1['points']; ?> <img src="images/points.png" class="img_pts" alt="Points" title="Points" height="20"/></p></div>
						<table class="center_align">
							<tr>	
								<td class="pro3"><img src="avatar/<?php echo $profil1['avatar'];?>" alt="Votre avatar" height="100" width="100"/></td>
								<td class="pro3"><a href="gestion_combat.php?id=<?php echo $profil1['id'];?>">Attaquer</a></td>
								<td class="pro4"><a href="espionner.php?id=<?php echo $profil1['id'];?>">Espionner</a></td>		
							</tr>
						</table>
						<div class="pro6"><p class="profil_text"><?php if($profil1['etat']==0){echo 'Ce joueur n\'est plus actif.';}else{echo 'Ce joueur est actif.';} ?></p></div>
						<div class="pro5"><p class="profil_text"><a href="mess_priv.php?nick=<?php echo $_GET['pseudo'];?>#form_mp"><img src="images/enveloppe.png" class="img_pts" alt="Enveloppe" title="Enveloppe" /> Envoyer un message privé</a></p></div>
						</div>
					<?php if((isset($_GET['msg'])) && ($_GET['msg']=='dif')) { ?> <p class="mp_rouge">Vous êtes trop loin de ce joueur pour pouvoir l'espionner !</p> <?php } ?>
					<?php if((isset($_GET['msg'])) && ($_GET['msg']=='money')) { ?> <p class="mp_rouge">Vous n'avez pas assez d'or pour espionner ce joueur.</p> <?php } ?>
					<p class="profil_espio"><?php if($difpts>200 || $difpts<-200){ ?>Vous ne pouvez pas espionner ce joueur.<br/>Vous avez une différence de plus de <strong>200</strong> <img src="images/points.png" alt="Points" title="Points" height="15" class="img_coffre"/><?php }else{ ?>Le coût pour espionner ce joueur est <?php echo $or_espio; } ?></p>
					
			<?php $req_connecte = $bdd->query('SELECT COUNT(*) FROM connectes');
	$connectes = $req_connecte->fetchColumn(); ?>			
		
	</div>
	<?php include_once'footer.php'; ?>
	</section>
	
	
</div>
    </body>
</html>

















