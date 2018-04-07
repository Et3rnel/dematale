<?php session_start();
if(!isset($_SESSION['pseudo'])) header('Location:index.php');
include_once'actu.php';
include_once'connectes.php'; ?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
		<link rel="stylesheet" href="design.css" />
        <title>Gestion du compte</title>
    </head>
    <body onload="augmentation_ressource()">
	<?php include_once'header.php'; ?>
<div id="g_section">
	<div id="band_l"></div><div id="band_r"></div>
	<section>


	<?php include_once'menu.php'; ?>
	<div id="corps">
	<h1>Espace membre</h1>
	<div class="corps2">


		<h2>Modifier ses informations</h2>
		<p class="center_align">Bienvenue <?php echo $_SESSION['pseudo']; ?>. <br/>Ici tu peux changer les informations relatives à ton compte.</p>

<?php





if(isset($_POST['submit1']))
{
	if($_POST['mdp']==$_POST['mdp_r'] && strlen($_POST['mdp'])>=5 && strlen($_POST['mdp'])<=20 && filter_var($_POST['mail'],FILTER_VALIDATE_EMAIL))
	{
		$mdp=md5($_POST['mdp']);
		$mail=addslashes($_POST['mail']);

		$update_compte = $bdd->prepare('UPDATE membres SET password=?,mail=? WHERE pseudo=?');
		$update_compte->execute(array($mdp,$mail,$_SESSION['pseudo']));
	}
}







if(isset($_POST['submit3']))
{
	echo 'Informations bien enregistrées';
}

$infos = $bdd->prepare('SELECT mail,pseudo FROM membres WHERE id=?');
$infos->execute(array($_SESSION['id']));
$info = $infos->fetch();
?>



		<h4>Informations du compte</h4>
			<form class="marg180" method="post" action="private.php">
				<table>
					<tr><td class="ta_r">Pseudo : </td> <td class="padd-2"> <?php echo $info['pseudo']; ?></td></tr>
					<tr><td class="ta_r"><label for="mdp">Mot de passe :</td> <td class="padd-2"></label><input type="password" name="mdp" maxlength="48" id="mdp"/></td></tr>
					<tr><td class="ta_r"><label for="mdp_r">Répéter mot de passe :</td><td class="padd-2"></label><input type="password" name="mdp_r" maxlength="48" id="mdp_r"/></td></tr>
					<tr><td class="ta_r"><label for="mail">E-mail :</td><td class="padd-2"></label><input type="text" value="<?php echo $info['mail']; ?>" name="mail" maxlength="48" id="mail"/></td></tr>
				</table>
					<br/><input class="marg60" type="submit" value="Changer les informations du compte" name="submit1"/>
			</form>


			<h4>Avatar</h4>
			<p class="padd">Pour modifier votre image de profil, veuillez choisir une image qui ne dépassé pas 0,5 Mo.</p>





			<?php
			if(isset($_GET['msg']) && $_GET['msg']=='val')
			{ ?>
			<p class="padd-g">Votre avatar a bien été modifié !</p>
			<?php }
			if(isset($_GET['erreur']) && $_GET['erreur']=='size')
			{ ?>
			<p class="padd-r">Votre avatar doit faire moins de 500 Ko (0,5 Mo).</p>
			<?php }
			if(isset($_GET['erreur']) && $_GET['erreur']=='ext')
			{ ?>
			<p class="padd-r">Votre avatar peut seulement avoir l'extension .jpg .jpeg ou .png.</p>
			<?php }
			if(isset($_GET['erreur']) && $_GET['erreur']=='used')
			{ ?>
			<p class="padd-r">Un joueur à déjà choisir un avatar qui possède le même nom de fichier. Renomez votre image pour pouvoir l'utiliser comme avatar.</p>
			<?php }

			?>




			</br>
				<form class="marg215" method="post" action="modif_avatar.php" enctype="multipart/form-data">
					<input type="file" name="avatar"/><br/><br/>
					<input class="marg70" type="submit"  value="Modifier votre avatar" />
				</form>


			<h4>Informations personelles</h4>
				<form class="marg235" method="post" action="private.php">
					Sexe : <input type="radio" name="sexe" value="m" id="m"/> <label for="m">Homme</label>
					<input type="radio" name="sexe" value="f" id="f"/> <label for="m">Femme</label><br />

					<br/><input  type="submit" value="Changer les informations personelles" name="submit3"/>
				</form>

	<br/>





			<!-- <?php if((isset($_GET['msg'])) && ($_GET['msg']=='chef')) { ?> <p class="red">Vous devez dissoudre votre alliance ou nommer un nouveau chef avant de reinitialiser votre compte.</p> <?php } ?>
		<?php if((isset($_GET['msg'])) && ($_GET['msg']=='succes_raz')) { ?> <p class="green">Vous avez réinitialisé votre compte avec succès !</p> <?php } ?>
<table class="margin-a">
	<tr>
		<td class="pritd"><form class="center_align" method="post" action="compte_option.php">
			<input class="subprivate" type="submit" name="raz_compte" onclick="return(confirm('Etes-vous sûr de vouloir remettre à zero votre compte ?'));" value="Remettre son compte à zéro" name="submit_raz"/>
		</form></td>
		<td class="pritd"><form class="center_align" method="post" action="compte_option.php">
			<input class="subprivate" type="submit" name="del_compte" onclick="return(confirm('Etes-vous sûr de vouloir remettre à supprimer votre compte ? ATTENTION, cette action est irréversible !'));" value="Supprimer le compte" name="submit_del"/>
		</form></td>
	</tr>
	<tr>
		<td class="textprivate"><strong>Vous réinitialisez votre compte.</strong> Vous retournez à 0 points, vos batiments et technologies sont réinitialisés mais vous gardez vos succès débloqués. Vous quittez aussi votre alliance.</td>
		<td class="textprivate"><strong>Vous supprimez votre compte.</strong> Vous n'existerez plus dans le jeu et votre pseudo sera de nouveau disponible. <span class="red">Cette action est <strong>IRREVERSIBLE !</strong></span></td>
	</tr>
</table><br/><br/> -->


	<?php $req_connecte = $bdd->query('SELECT COUNT(*) FROM connectes');
	$connectes = $req_connecte->fetchColumn(); ?>

	</div></div>
	<?php include_once'footer.php'; ?>
	</section>


</div>
    </body>
</html>
