<?php session_start();
if(!isset($_SESSION['id'])) header('Location:index.php'); 
include_once'actu.php';
include_once'connectes.php'; ?>


<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
		<link rel="stylesheet" href="design.css" />
        <title>Messages privés</title>
    </head>
        <body onload="augmentation_ressource()">
		<?php include_once'header.php'; ?>
	
	
	
<div id="g_section">
	<div id="band_l"></div>	<div id="band_r"></div>
	<section>
	
	
	<?php include_once'menu.php'; ?>
	<div id="corps">
	<h1>Messages privés</h1>
	<div class="corps2">
			
			
			
	<?php 
	if(isset($_GET['message']) && $_GET['message'] == 'val') echo '<p class="mp_vert">Vos messages on été marqué comme lu !</p><br/>';	
	if(isset($_GET['message']) && $_GET['message'] == 'val2') echo '<p class="mp_vert">Vos messages ont tous été supprimés !</p><br/>';		
	if(isset($_GET['msg']) && $_GET['msg']=='succes') echo '<p class="mp_vert">Votre message privé a bien été envoyé</p><br/>';
	if(isset($_GET['erreur']) && $_GET['erreur']=='length') echo '<p class="mp_rouge">Vous ne pouvez pas répondre par un message vide.</p><br/>';
	if(isset($_GET['msg']) && $_GET['msg']=='notfound') echo '<p class="mp_rouge">Ce joueur n\'existe pas, verifiez que vous ayez écris correctement son pseudo.</p><br/>';
	if(isset($_GET['msg']) && $_GET['msg']=='empty') echo '<p class="mp_rouge">Verifiez que vous avez correctement remplis tous les champs</p><br/>';
			if(isset($_GET['message']) && $_GET['message']=='b_id') 
				{ 
					?>
						<p class="b_id">Ce message n'existe pas !</p>
					<?php
				}
					?>
				
				
			<table class="margin-a">
				<tr>
					<th class="th_mp1">Expediteur</th>
					<th class="th_mp2">Sujet du message privé</th>
					<th class="th_mp3">Date de reception</th>      
					<th class="th_mp4">Supp.</th>
				</tr>
				
				
<?php require_once'cnx.php';



$mess_priv = $bdd->prepare('SELECT id,expediteur,titre,message,date_mp,statut FROM mess_priv WHERE recepteur = ? ORDER BY date_mp DESC LIMIT 0, 15');
$mess_priv->execute(array($_SESSION['pseudo']));

$check_zero = $bdd->prepare('SELECT COUNT(*) id,expediteur,titre,message,date_mp,statut FROM mess_priv WHERE recepteur=?');
$check_zero->execute(array($_SESSION['pseudo']));
if($check_zero->fetchColumn() == 0)
{
	?>
	<td class="no_msg_l"></td>
	<td class="no_msg">Vous n'avez aucun message.</td>
	<td class="no_msg"></td>
	<td class="no_msg_r"></td>
	<?php
}
else
{

		while ($donnees = $mess_priv->fetch())
		{
			?>
			<tr class="<?php	
			
							if($donnees['expediteur']!='Maître des informations')
							{	
								if($donnees['statut']==1)
								{
									echo'lu';
								}
								else
								{
									echo'nlu';
								} 
							}
							else
							{	
								if($donnees['statut']==1)
								{
									echo'msg_cron1';
								}
								else
								{
									echo'msg_cron2';
								} 
							}
								
						?>">
					<td class="expe"><?php if(($donnees['expediteur']=='Maître des informations') || ($donnees['expediteur']=='Chef de guerre')){ echo $donnees['expediteur']; }else{echo '<a href="profil.php?pseudo='.$donnees['expediteur'].'">'.$donnees['expediteur'].'</a>'; }                           ?></td>
					<td class="fofo_mp"><a href="mp.php?id=<?php echo $donnees['id']; ?>"><?php echo htmlspecialchars($donnees['titre']); ?></a></td>
					<td class="date-m"><?php echo date('d/m/Y', htmlspecialchars($donnees['date_mp'])); ?> à <?php echo date('H:i', htmlspecialchars($donnees['date_mp'])); ?></td>
					<td class="sup"><a href="trait_supp.php?id=<?php echo $donnees['id'];?>"><img src="images/sup_ms.png" alt="Supprimer le message"/></a></td>
			</tr>
			<?php
		
		}
		$mess_priv->closeCursor();
}
    ?>
</table><br/>


	
<form class="marquage1" method="post" action="traitement/mp_read.php">
	<input type="submit" value="Marquer les messages privés comme lu">
</form>

<form class="marquage2" method="post" action="traitement/mp_delete.php">
	<input type="submit" name="mp_delete" value="Supprimer tous les messages privés">
</form>		
	
<br/><br/><br/>
	
	
<form method="post" action="traitement/mess_priv.php">
	<table>	
	<tr>
		<td class="tdprive"><label for="recepteur">Envoyer un message privé à :</label> <input class="private_mess" type="text" name="recepteur" id="recepteur" <?php if(isset($_GET['nick']))echo 'value="'.$_GET['nick'].'"';?> placeholder="Pour quel joueur.."/></td>
	</tr>
	<tr>
		<td class="tdprive"><label for="sujet_mp">Sujet :</label> <input type="text" class="private_mess" name="sujet_mp" maxlength="48" id="sujet_mp" placeholder="Titre du message.."/></td>
	</tr>
	<tr>
		<td class="tdprive2">			
			<textarea class="reponse" name="message_mp" id="message_mp" placeholder="Ecrivez votre message ici.." maxlength="2000" rows="13" cols="79"/></textarea> <!--Cols a une valeur grande pour arriver au bout, la taille est limitée en CSS-->
		</td>
	</tr>
	<tr>
		<td class="tdprive">
		<input type="submit" name="prive" class="marg270" value="Envoyer le message"/>
		</td>
	</tr>
	</table>
</form><br/>  

	
	
	
	
	
	
	
	
	
	<?php $req_connecte = $bdd->query('SELECT COUNT(*) FROM connectes');
	$connectes = $req_connecte->fetchColumn(); ?>
	
	</div></div>
	<?php include_once'footer.php'; ?>
	</section>
	
	
	</div>

    </body>
</html>