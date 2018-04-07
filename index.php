<?php
session_start();
if (isset($_SESSION['id']))
{
	include_once'actu.php';
	include_once'connectes.php';
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
		<link rel="stylesheet" href="design.css" />
        <!-- <link rel="stylesheet" href="libs/tingle.css" />
        <script src="libs/tingle.js"></script> -->
        <title>Dematale</title>
		<meta name="description" content="Jeu de stratégie et de gestion gratuit en ligne par navigateur, avec une époque terrestre puis une époque spatiale">
		<meta name="keywords" content="gestion,stratégie,jeu en ligne,gratuit,navigateur,rapide,simple,guerre">
		<meta name="robots" content="index">
		<meta name="REVISIT-AFTER" content="7 days">
		<meta http-equiv="Content-Language" content="fr">
    </head>
    <body onload="augmentation_ressource()">
	<?php include_once'header.php'; ?>

    <!-- <script src="js/register_modal.js"></script> -->


<div id="g_section">
	<div id="band_l"></div>	<div id="band_r"></div>
	<section>


	<?php include_once'menu.php'; ?>
	<div id="corps">
	<h1>Dematale, le jeu de stratégie multi-époques</h1>
	<div class="corps2">


		<?php if(!isset($_SESSION['id']))
		{
			?>
			<p class="titre_jeu"><img class="valign" src="images/baniere.png" alt="Epee de guerre" />Rejoignez la bataille ! <img class="valign" src="images/baniere.png" alt="Epee de guerre" /></p><br/>

			<h2><img src="images/sword1.png" alt="Epee de guerre"/> But du jeu</h2>
			<p class="padd">Soyez le chef de <strong>Dematale</strong> ! Gagnez le plus de points <img src="images/points.png" alt="Points" align="top"/> possible pour être en haut du classement ! Pour gagner des points, plusieurs stratégies s'offrent à vous :
			<strong>Attaquez, développez vos batiments,  développez vos technologies ou créez une alliance digne de ce nom !</strong><br/>
			Attaquer, c'est très simple. Cliquez sur l'onglet classement puis selectionnez un joueur, vous aurez alors la possibilité de l'attaquer ou de l'espionner !</p>
			<h2><img src="images/sword1.png" alt="Epee de guerre"/> Le système</h2>
			<p class="padd">Au fil du jeu, vous aurez l'occasion de débloquer de nouvelles ressources en plus de celles que vous avez actuellement.<br/><br/>
			<img src="images/prez_menu.png" alt="Menu du jeu" /></p>
			<h2><img src="images/sword1.png" alt="Epee de guerre"/> Comment jouer</h2>
			<p class="padd">Si vous n'êtes pas encore inscrit, commencez par le faire, c'est <a href ="register.php">par ici.</a><br/><br/>
			Ensuite, commencez par augmenter le niveau de vos batiments pour avoir une production de ressources.<br/>
			Vous pouvez désormais développer votre armée ainsi que vos technologies pour pouvoir attaquer d'autres joueurs, et aussi pour pour vous defendre.<br/>
			Ce jeu sera axé sur le commerce et sur la gestion des ressources. Pensez à vous servir des technologies qui vous sont proposée !</p>
			<h2><img src="images/sword1.png" alt="Epee de guerre"/> Informations</h2>
			<p class="padd">Le jeu est en constante évolution, des mise à jour d'équilibrage sont faites régulièrement ainsi que du contenu supplémentaire ajouté.</p>
			<p class="padd">Pour jouer, il vous suffit d'avoir un navigateur Internet.</p>
			<?php
		}
		else
		{

//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
//--------------------------------------------------------------------------------------------------Le joueur est connecté---------------------------------------------------------------------------------------
//---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
$req_c1 = $bdd->query('SELECT pseudo,points FROM membres WHERE coupe=1');
$bronze = $req_c1->fetch();

$req_c2 = $bdd->query('SELECT pseudo,points FROM membres WHERE coupe=2');
$argent = $req_c2->fetch();

$req_c3 = $bdd->query('SELECT pseudo,points FROM membres WHERE coupe=3');
$or = $req_c3->fetch();

$req_c4 = $bdd->query('SELECT pseudo,points FROM membres WHERE coupe=4');
$diamant = $req_c4->fetch();

$req999 = $bdd->prepare('SELECT decret FROM membres WHERE id=?');
$req999->execute(array($_SESSION['id']));
$membre = $req999->fetch(); ?>

<h2>Répartition des coupes</h2>


<p class="center_align"><strong><a href="profil.php?pseudo=<?php echo $bronze['pseudo']; ?>"><?php echo $bronze['pseudo']; ?></a></strong> avec <strong><?php echo $bronze['points']; ?></strong> <img src="images/points.png" align="top" alt="Points" title="Points" height="15"/>  a la coupe  <strong>bronze</strong> <img src="images/coupes/1.png" alt="Coupe" height="15" width="15" align="top"/><br/>
 <strong><a href="profil.php?pseudo=<?php echo $argent['pseudo']; ?>"><?php echo $argent['pseudo']; ?></a></strong> avec <strong><?php echo $argent['points']; ?></strong> <img src="images/points.png" align="top" alt="Points" title="Points" height="15"/> a la coupe <strong>argent</strong> <img src="images/coupes/2.png" alt="Coupe" height="15" width="15" align="top"/><br/>
 <strong><a href="profil.php?pseudo=<?php echo $or['pseudo']; ?>"><?php echo $or['pseudo']; ?></a></strong> avec <strong><?php echo $or['points']; ?></strong> <img src="images/points.png" align="top" alt="Points" title="Points" height="15"/> a la coupe <strong>or</strong> <img src="images/coupes/3.png" alt="Coupe" height="15" width="15" align="top"/><br/><br/>
 <?php if(isset($diamant['pseudo'])){ ?> <strong><a href="profil.php?pseudo=<?php echo $diamant['pseudo']; ?>"><?php echo $diamant['pseudo']; ?></a></strong> avec <strong><?php echo $diamant['points']; ?></strong> <img src="images/points.png" align="top" alt="Points" title="Points" height="15"/> a la coupe <strong>diamant</strong> <img src="images/coupes/4.png" alt="Coupe" height="15" width="15" align="top"/> <?php }else{ ?> Personne n'a la coupe diamant. <?php } ?> </p>


<?php
$req2 = $bdd->prepare('SELECT decret FROM timer WHERE id=?');
$req2->execute(array($_SESSION['id']));
$timer = $req2->fetch(); ?>



<h2>Décret actuel décidé par le dirigeant : <?php switch($membre['decret']){case 0: echo 'Aucun';break; case 1: echo'Gagne-pain';break; case 2: echo'Travail intensif';break; case 3: echo'Maître des ombres';break;} ?></h2>

<p class="center_align">Le décret peut être changé tous les <strong>6 jours</strong>.</p>


<?php
$temps = time();
$dif_temps = $temps - $timer['decret'];

if($dif_temps >= 518400)
{   ?>
	<p class="center_align"><em>Vous pouvez actuellement changer le décret de votre village</em></p>
						<br/>
			<p class="simple_purple">Choisissez votre nouveau décret :</p>
				<form method="post" class="trait_formu" action="traitement/decret.php">
					<p class="marg70">
						<input type="radio" name="mode" value="gp" id="gp" <?php if($membre['decret'] == 1) echo 'checked="checked"';?> /> <label for="gp"><strong>Gagne-pain :</strong> Augmente la production d'or de <strong>5%</strong>.</label><br />
						<input type="radio" name="mode" value="ti" id="ti" <?php if($membre['decret'] == 2) echo 'checked="checked"';?> /> <label for="ti"><strong>Travail intensif :</strong> Augmente la production de toutes les autres ressources de <strong>2%</strong>.</label><br />
						<input type="radio" name="mode" value="mo" id="mo" <?php if($membre['decret'] == 3) echo 'checked="checked"';?> /> <label for="ti"><strong>Maître des ombres :</strong> Le coût en espionnage est divisé par <strong>15</strong>.</label><br/><br/>
						<input class="marg215" type="submit" name="decret" value="Envoyer" />
					</p>
				</form>
			<?php }
			else{
			$jours=floor($dif_temps/86400);
			$reste=$dif_temps%86400;
			$heures=floor($reste/3600);
			$reste=$reste%3600;
			$minutes=floor($reste/60);


			switch($membre['decret']){
			case 1: ?> <p class="center_align">Actuellement, votre production d'<img src="images/or_icon.png" alt="Or" align="top" title="Or" height="16"/> est augmenté de <strong>5%</strong></p> <?php break;
			case 2: ?> <p class="center_align">Actuellement, la production de <img src="images/bois_icon.png" alt="Bois" align="top" title="Bois" height="17"/>, <img src="images/fer_icon.png" alt="Fer" align="top" title="Fer" height="16"/> , <img src="images/roche_icon.png" alt="Roche" align="top" title="Roche" height="17"/> est augmenté de <strong>2%</strong></p> <?php break;
			case 3: ?> <p class="center_align">Actuellement, le coût en espionnage est divisé par <strong>15</strong></p> <?php break;}


			$true_day = 5-$jours;
			$true_houre = 23-$heures;
			$true_minutes = 59-$minutes;
			?>
			<p class="simple_purple">Il vous reste <strong><?php if($true_day>=1)echo $true_day.' jour(s),';?></strong> <strong><?php if($true_houre>=1)echo $true_houre.' heure(s)</strong> et';?> <strong><?php if($true_houre>=1)echo $true_minutes.' minute(s)';?> </strong> avant de pouvoir changer de decret</p>





			<?php }
		}
			include_once'cnx.php';
			$req_connecte = $bdd->query('SELECT COUNT(*) FROM connectes');
			$connectes = $req_connecte->fetchColumn(); ?>

	</div></div>
	<?php include_once'footer.php'; ?>
	</section>

</div>

    </body >
</html>
