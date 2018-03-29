<?php session_start(); 
if(!isset($_SESSION['pseudo'])) header('Location:index.php');
include_once'actu.php'; 
include_once'connectes.php'; 

$req1 = $bdd->prepare('SELECT id_alliance,apply FROM membres WHERE id=?');
$req1->execute(array($_SESSION['id']));
$membre = $req1->fetch();

$req2 = $bdd->prepare('SELECT coffre,nbr_membre FROM alliance WHERE id=?');
$req2->execute(array($membre['id_alliance']));
$alliance = $req2->fetch(); 

if(empty($alliance['nbr_membre']) || $membre['apply']==0)
{
	header('Location:index.php');
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
		<link rel="stylesheet" href="design.css" />
        <title>Dematale, le jeu de strategie en ligne</title>
    </head>
        <body onload="augmentation_ressource()">
<?php include_once'header.php'; ?>
<div id="g_section">
	<div id="band_l"></div>	<div id="band_r"></div>
	<section>
	
	
	<?php include_once'menu.php'; ?>
	<div id="corps">
	<h1>Chat d'alliance</h1>
	<div class="corps2">
	
	
	<p class="menu_alliance"><a href="alliance.php">Gestion</a> | <a href="alliance/guerre.php">Guerre</a> | <a href="developpement.php">Développement</a> | <a href="coffre.php">Coffre-fort</a></p>


<table class="ally_chat">
	<tr>
		<td class="tdchat3">
			<form method="post" action="traitement/chat_alliance.php"> 
				<input type="text" name="message" id="message" placeholder="Envoyer un message à votre alliance.." size="68" maxlength="500" />
				<input type="submit" name="subchatally" value="Envoyer le message"/>
					<?php 
	if(isset($_GET['length']) && $_GET['length']=='low'){ ?><p class="erreur_chat">Votre message est trop court ! Vous devez envoyer au moins 2 caractères.</p><?php } ?>
			</form>
		</td>
	</tr>


<?php
	$req1 = $bdd->prepare('SELECT id_alliance FROM membres WHERE id=?');
	$req1->execute(array($_SESSION['id']));
	$membre = $req1->fetch();
	
	$req2 = $bdd->prepare('SELECT nom FROM alliance WHERE id=?');
	$req2->execute(array($membre['id_alliance']));
	$info = $req2->fetch();
	
	$req3 = $bdd->prepare('UPDATE membres SET notif_chat_alliance=0 WHERE id=?');
	$req3->execute(array($_SESSION['id']));

	$reponse = $bdd->prepare('SELECT pseudo,message,date_alliance FROM chat_alliance WHERE nom_alliance=? ORDER BY ID DESC LIMIT 0, 25'); 
	$reponse->execute(array($info['nom']));
    while ($donnees = $reponse->fetch())
    {
		?>
		<tr>
			<td class="tdchat4">
				<strong><a href="profil.php?pseudo=<?php echo $donnees['pseudo'];?>"><?php if($donnees['pseudo'] == 'ZeroTernel'){ ?><span class="admin"><?php echo htmlspecialchars($donnees['pseudo']); ?>
				</span> <?php }else{echo htmlspecialchars($donnees['pseudo']);}?> </a></strong>
				<span class="heure"><?php echo date('H\hi', $donnees['date_alliance']); ?></span>: <?php if($donnees['pseudo']!='Gardien du coffre' &&  $donnees['pseudo']!='Registre de l\'alliance')
				{
					$msg = htmlspecialchars($donnees['message']);
					$msg = preg_replace('#https?://\S+#i', '<a href="$0" title="lien">$0</a>', $msg);
					echo '<span class="liens_a">'.$msg.'</span>';}else{echo $donnees['message']; 
				}?><br/>
			</td>
		</tr>
		<?php
	}
    $reponse->closeCursor(); ?>
</table>



	<?php $req_connecte = $bdd->query('SELECT COUNT(*) FROM connectes');
	$connectes = $req_connecte->fetchColumn(); ?>

	</div></div>
	<?php include_once'footer.php'; ?>
	</section>
	
	
</div>
    </body >
</html>












