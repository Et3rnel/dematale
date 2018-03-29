<?php session_start(); 
if (!isset($_SESSION['pseudo']) || !isset($_GET['id'])) {header('Location:alliance.php');}
require_once'cnx.php'; 
//On ne peut pas rejoindre d'alliance si on est deja dans une alliance
$ally = $bdd->prepare('SELECT id_alliance,level FROM membres WHERE id=?');
$ally->execute(array($_SESSION['id']));
$donnees = $ally->fetch();
if ($donnees['id_alliance']!=0){header('Location:alliance.php');}
//On regarde si l'ally existe
$req1 = $bdd->prepare('SELECT nom,nbr_membre FROM alliance WHERE id=?');
$req1->execute(array($_GET['id']));
$alliance = $req1->fetch();
//On regarde si le mec est level 1 ou plus

if(empty($alliance['nom']) || $alliance['nbr_membre']==15)
{
	?>
	<!DOCTYPE html>
	<html>
		<head>
			<meta charset="utf-8" />
			<link rel="stylesheet" href="design.css" />
			<title>Alliance inconnu</title>
		</head>
		<body id="white">
		<h1 class="h1-w">Alliance inconnu</h1>	
	<div class="form">
		<p class="decal">L'alliance que vous essayez de rejoindre est déjà complète ou n'existe pas.<br/><br/><a href="alliance.php">Retourner sur la page alliance</a></p>
	</div>
		</body>
	</html>
	<?php 
	die();
} 
if($donnees['level']<1)
{
	header('Location:alliance/join.php');
	die();
} 


$req2=$bdd->prepare('UPDATE membres SET id_alliance=? WHERE id=?');
$req2->execute(array($_GET['id'],$_SESSION['id']));

header('Location:alliance.php');






