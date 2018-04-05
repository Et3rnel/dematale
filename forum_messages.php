<?php
session_start();
require_once 'global_function.php';
if(!isset($_SESSION['pseudo'])) header('Location:index.php');
if(!isset($_GET['id_sujet_a_lire'])) header('Location:forum.php');
include_once'fonction.php';
$sujet_id = negativeZero(intval($_GET['id_sujet_a_lire']));
include_once'actu.php';
include_once'connectes.php'; ?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
		<link rel="stylesheet" href="design.css" />
        <title>Messages</title>
    </head>
        <body onload="augmentation_ressource()">


<?php include_once'header.php'; ?>

	

<div id="g_section">
	<div id="band_l"></div>	<div id="band_r"></div>
	<section>


	<?php include_once'menu.php';
	$req01 = $bdd->prepare('SELECT titre FROM forum_sujet WHERE id=?');
	$req01->execute(array($sujet_id));
	$forum = $req01->fetch(); ?>

	<div id="corps">
	<h1><?php echo $forum['titre']; ?></h1>
	<div class="corps2">

	<?php if((isset($_GET['msg'])) && ($_GET['msg']=='time')) { ?> <p class="mp_rouge">Vous avez déjà posté un message sur le chat ou sur le forum il y a moins de 15 secondes.<br/>Veuillez réessayer après ce délais</p> <?php } ?>

<table id="forum">
   <tr>
       <th class="th_forum">Auteur</th>
       <th class="th_forum">Message</th>
       <th class="th_forum">Posté le :</th>
   </tr>

<?php

$req2 = $bdd->prepare('SELECT auteur FROM forum_reponse WHERE correspondance_sujet=?');
$req2->execute(array($sujet_id));
$test = $req2->fetch();

if(empty($test['auteur'])) header('Location:forum.php');


$messagesParPage = 10;
$req1 = $bdd->query('SELECT COUNT(*) AS nbr_messages FROM forum_reponse WHERE correspondance_sujet='.$sujet_id.'');
$donnees = $req1->fetch();
$nbrPages = ceil($donnees['nbr_messages']/$messagesParPage);

if(isset($_GET['page']))
{
	$pageActuelle=intval($_GET['page']);
	if($pageActuelle>$nbrPages){$pageActuelle=$nbrPages;}
}
else{$pageActuelle=1;}
$premiereEntree=($pageActuelle-1)*$messagesParPage;

$req = $bdd->query('SELECT auteur,message,date_reponse FROM forum_reponse WHERE correspondance_sujet="'.$sujet_id.'" ORDER BY date_reponse ASC LIMIT '.$messagesParPage.' OFFSET '.$premiereEntree.'');

while ($donnees = $req->fetch())
{
	$avatar = $bdd->prepare('SELECT avatar FROM membres WHERE pseudo=?');
	$avatar->execute(array($donnees['auteur']));
	$info = $avatar->fetch();
		?>
			<tr>
				<td class="auteur_reponse"> <br/><img src="avatar/<?php echo $info['avatar'];?>" alt="Votre avatar" height="100" width="100"/> <br/> <a href="profil.php?pseudo=<?php echo $donnees['auteur'];?>"><?php echo htmlspecialchars($donnees['auteur']);?></a>   </td>
				<td class="message"><?php
				$msg = htmlspecialchars($donnees['message']);
				$msg = preg_replace('#https?://\S+#i', '<a href="$0" title="lien">$0</a>', $msg);
				echo '<span class="liens_a">'.nl2br($msg).'</span>';?></td>
				<td class="date_reponse"><?php echo date('d/m/Y', htmlspecialchars($donnees['date_reponse']));?> <br/><br/> <?php echo date('H:i', htmlspecialchars($donnees['date_reponse']));?></td>
			</tr>
		<?php
	$avatar->closeCursor();
} ?>
</table>



<div class="pagination2">
<?php
for($i=1; $i<=$nbrPages; $i++)
{

     if($i==$pageActuelle)
     {
         echo '<strong class="color_blue">[-'.$i.'-]</strong>';
     }
     else
     {
          echo ' <a href="forum_messages.php?id_sujet_a_lire='.$sujet_id.'&page='.$i.'">' .$i. '</a> ';
     }
}
?>
</div><br/>


	<form method="post" action="traitement/message.php?sujet_id=<?php echo $sujet_id;?>&page=<?php echo $pageActuelle;?>">
	<table>
	<tr>
		<td class="tdprive"><p>Votre pseudo : <strong><?php echo''.$_SESSION['pseudo'].''?></strong></p></td>
	</tr>
	<tr>
		<td class="tdprive2">
			<textarea class="reponse" name="message" id="message" placeholder="Ecrivez votre message ici.." maxlength="2000" rows="13" cols="79"/></textarea> <!--Cols a une valeur grande pour arriver au bout, la taille est limitée en CSS-->
		</td>
	</tr>
	<tr>
		<td class="tdprive">
		<input type="submit" class="marg270" value="Envoyer le message"/>
		</td>
	</tr>
	</table>
</form>


<?php if (isset($_GET['erreur'])) { ?> <p class="red">Votre message doit faire entre 10 et 3000 caractères !</p> <?php } ?>







		<?php $req_connecte = $bdd->query('SELECT COUNT(*) FROM connectes');
	$connectes = $req_connecte->fetchColumn(); ?>

	</div></div>
	<?php include_once'footer.php'; ?>
	</section>


	</div>

    </body>
</html>
