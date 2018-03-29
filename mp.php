<?php session_start();
if(!isset($_SESSION['id']))header('Location:index.php'); 
include_once'actu.php';
include_once'connectes.php';
include_once'fonction.php';

$id = NegativZero(intval($_GET['id']));
$req1 = $bdd->prepare('SELECT message,id_player FROM mess_priv WHERE id=? and recepteur=?');
$req1->execute(array($id,$_SESSION['pseudo']));
$test = $req1->fetch();
if(empty($test['message']))
{ 
	?>
	<!DOCTYPE html>
	<html>
    <head>
        <meta charset="utf-8" />
		<link rel="stylesheet" href="design.css" />
        <title>Message introuvable</title>
    </head>
	<body id="white">
		<h1 class="h1-w">Message introuvable</h1>	
		<div class="form">
		<p class="decal">Le message auquel vous tentez d'acceder n'existe pas.<br/><br/><a href="mess_priv.php">Cliquez ici</a> pour retourner à vos messages privés.</p>
		</div>
	</body>
	</html>
	<?php
	die();
}	
?>


<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
		<link rel="stylesheet" href="design.css" />
        <title>Message privé</title>
    </head>
        <body onload="augmentation_ressource()">
<?php include_once'header.php'; ?>
<div id="g_section">
	<div id="band_l"></div><div id="band_r"></div>
	<section>
	
	
	<?php include_once'menu.php'; ?>
	<div id="corps">
	<h1>Contenu du message</h1>
	<div class="corps2">


<table class="margin-a">
   <tr>
       <th class="th_mp">Expediteur</th>
       <th class="th_mp">Contenu du message</th>
       <th class="th_mp">Date d'envoi</th>
   </tr>
   
<?php 
require_once 'cnx.php';
$mess_priv = $bdd->prepare('SELECT recepteur,expediteur,message,date_mp,statut FROM mess_priv WHERE id=?');
$mess_priv->execute(array($_GET['id']));
    while ($donnees = $mess_priv->fetch())
    {
			if ($donnees['statut']==0) 
			{
				$req = $bdd->prepare('UPDATE mess_priv SET statut=TRUE WHERE recepteur=? AND id=?');
				$req->execute(array($_SESSION['pseudo'],$_GET['id']));
			}
			?>
				<tr class="lu">
					<td class="expe"><?php echo htmlspecialchars($donnees['expediteur']); ?></td>
					<td class="mess_mp"><?php if($donnees['expediteur']=='Chef de guerre' || $donnees['expediteur']=='Maître des informations' || $donnees['expediteur']=='Chef d\'alliance'|| $donnees['expediteur']=='Commissaire-priseur'){echo nl2br($donnees['message']);}else{		
					$msg = htmlspecialchars($donnees['message']);
					$msg = preg_replace('#https?://\S+#i', '<a href="$0" title="lien">$0</a>', $msg);
					echo '<span class="liens_a">'.nl2br($msg).'</span>';} ?></td>
					<td class="date-m"><?php echo date('d/m/Y', htmlspecialchars($donnees['date_mp'])); ?> <br/><br/> <?php echo date('H:i', htmlspecialchars($donnees['date_mp'])); ?></td>
				</tr>
				
				<?php if(htmlspecialchars($donnees['expediteur']!='Chef de guerre' && $donnees['expediteur']!='Maître des informations' && $donnees['expediteur']!='Commissaire-priseur'))
				{ 
					?>
					<tr class="lu">
						<td class="no_msg_l"></td>
						<td class="no_msg"><a href="reponse.php?id=<?php echo $id; ?>">Répondre au message</a></td>
						<td class="no_msg_r"></td>
					</tr>
					<?php 
				} 
				if($test['id_player']!=0)
				{
					?>
					<tr class="lu">
						<td class="no_msg_l"></td>
						<td class="no_msg"><a href="espionner.php?id=<?php echo $test['id_player']; ?>">  Espionner ce joueur</a> | <a href="gestion_combat.php?id=<?php echo $test['id_player']; ?>"> <img src="images/icon/sword.png" class="img_att" alt="Sword"/>  Attaquer ce joueur</a></td>
						<td class="no_msg_r"></td>
					</tr>
					<?php 
				}
				

    }
    $mess_priv->closeCursor(); ?>
</table>
	<?php $req_connecte = $bdd->query('SELECT COUNT(*) FROM connectes');
	$connectes = $req_connecte->fetchColumn(); ?>


	</div></div>
	<?php include_once'footer.php'; ?>
	</section>
	
</div>
    </body>
</html>


