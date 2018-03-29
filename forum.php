<?php session_start();
if(!isset($_SESSION['id']))
{
	?>
	<!DOCTYPE html>
	<html>
    <head>
        <meta charset="utf-8" />
		<link rel="stylesheet" href="design.css" />
        <title>Accès au forum</title>
    </head>
		<body id="white">
			<h1 class="h1-w">Accéder au forum</h1>
			<div class="form">
			<p class="decal">Vous devez être connecté pour accéder au forum !<br/><br/><a href="connexion.php">Cliquez ici</a> pour vous connecter, ou inscrivez-vous <a href="register.php">ici</a>.</p>
			</div>
			<p class="retour_index"><a href="index.php">Retour à l'index</a></p>
		</body>
	</html>
	<?php
	die();
} 
include_once'actu.php';
include_once'connectes.php'; ?>

<!DOCTYPE html>
<html>
<head>
        <meta charset="utf-8" />
		<link rel="stylesheet" href="design.css" />
        <title>Forum</title>
</head>
        <body onload="augmentation_ressource()">
<?php include_once'header.php'; ?>
	
	
	
<div id="g_section">
	<div id="band_l"></div>	<div id="band_r"></div>
	<section>
	
	
	<?php include_once'menu.php'; ?>
	<div id="corps">
	<h1>Forum</h1>
	<div class="corps2">
	
	
<table id="forum">
   <tr>
		<th class="th_forum">Sujet</th>
		<th class="th_forum">Auteur</th>
		<th class="th_forum">Nb</th>
		<th class="th_forum">Date</th>   
   </tr>
<?php 
require_once'cnx.php';
$req = $bdd->query('SELECT id,auteur,titre,date_derniere_reponse,nbr_message FROM forum_sujet ORDER BY date_derniere_reponse DESC LIMIT 0, 18');

    while ($donnees = $req->fetch())
    {
		?>
			<tr>
				<td class="sujet"><a href="forum_messages.php?id_sujet_a_lire=<?php echo $donnees['id'];?>"><?php echo htmlspecialchars($donnees['titre']);?></a></td>
				<td class="auteur"><a href="profil.php?pseudo=<?php echo $donnees['auteur'];?>"><?php echo htmlspecialchars($donnees['auteur']);?></a></td>
				<td class="nb"><?php echo $donnees['nbr_message']; ?></td>
				<td class="date"><?php echo date('d/m/Y', htmlspecialchars($donnees['date_derniere_reponse']));?> à <?php echo date('H:i', htmlspecialchars($donnees['date_derniere_reponse']));?></td>
			</tr>
		<?php
	}
    $req->closeCursor(); ?>
</table><br/><br/>

		
<form method="post" action="traitement/sujet.php">
	<table>	
	<tr>
		<td class="tdprive"><label for="sujet">Sujet :</label> <input type="text" class="private_mess" name="sujet" maxlength="48" id="sujet" placeholder="Titre du sujet.."/></td>
	</tr>
	<tr>
		<td class="tdprive2">			
			<textarea class="reponse" name="message" id="message" placeholder="Ecrivez votre message ici.." maxlength="2000" rows="13" cols="79"/></textarea> <!--Cols a une valeur grande pour arriver au bout, la taille est limitée en CSS-->
		</td>
	</tr>
	<tr>
		<td class="tdprive">
		<input type="submit" name="prive" class="marg270" value="Envoyer le sujet"/>
		</td>
	</tr>
	</table>
</form>
	
	
	
	
	

	<?php $req_connecte = $bdd->query('SELECT COUNT(*) FROM connectes');
	$connectes = $req_connecte->fetchColumn(); ?>
	
	</div></div>
	<?php include_once'footer.php'; ?>
	</section>
	
	</div>

    </body>
</html>