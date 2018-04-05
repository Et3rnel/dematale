<?php
session_start();
require_once 'global_function.php';

if(!isset($_SESSION['id']))header('Location:index.php');
if(!isset($_GET['id']))header('Location:index.php');
include_once'actu.php';
include_once'connectes.php';
include_once'fonction.php';

$id = negativeZero(intval($_GET['id']));
$req0 = $bdd->prepare('SELECT message FROM mess_priv WHERE id=? and recepteur=?');
$req0->execute(array($id,$_SESSION['pseudo']));
$test = $req0->fetch();
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
		<p class="decal">Le message auquel <strong>vous tentez de répondre</strong> n'existe pas.<br/><br/><a href="mess_priv.php">Cliquez ici</a> pour retourner à vos messages privés.</p>
		</div>
	</body>
	</html>
	<?php
	die();
}
$req1 = $bdd->prepare('SELECT expediteur,message,titre FROM mess_priv WHERE id=?');
$req1->execute(array($id));
$cable = $req1->fetch();
?>

<!DOCTYPE html>
<html>
	<link rel="icon" type="image/ico" href="favicon.ico" />
    <head>
        <meta charset="utf-8"/>
		<link rel="stylesheet" href="design.css" />
        <title>Répondre au message</title>
    </head>
        <body onload="augmentation_ressource()">


<?php include_once'header.php'; ?>



<div id="g_section">
	<div id="band_l"></div>	<div id="band_r"></div>
	<section>


		<?php include_once'menu.php'; ?>

		<div id="corps">
		<h1>Répondre au joueur</h1>
		<div class="corps2">


		<?php if(isset($_GET['msg']) && $_GET['msg']=='succes') echo '<p class="mp_vert">Votre message privé a bien été envoyé</p><br/>'; ?>

		<h2>Répondre au message </h2>

		<table>
			<tr>
				<td class="tdprive">Réponse au message privé envoyé par <strong><?php echo $cable['expediteur']; ?></strong></td>
			</tr>
			<tr>
				<td class="tdprive">Sujet : <strong><?php echo $cable['titre']; ?></strong></td>
			</tr>
			<tr>
				<td class="tdprive2">
						<form method="post" action="traitement/repondre_mp.php?id=<?php echo $id; ?>">
							<textarea class="reponse" name="message" id="message" placeholder="Ecrivez votre réponse ici.." maxlength="2000" rows="13" cols="79"/></textarea> <!--Cols a une valeur grande pour arriver au bout, la taille est limitée en CSS-->
							<input type="submit" name="rep_mp" class="marg270" value="Envoyer la réponse"/>
						</form>
				</td>
			</tr>
		</table>




<?php
	$req_connecte = $bdd->query('SELECT COUNT(*) FROM connectes');
	$connectes = $req_connecte->fetchColumn(); ?>
	</div></div>
	<?php include_once'footer.php'; ?>
	</section>

	</div>
    </body >
</html>
