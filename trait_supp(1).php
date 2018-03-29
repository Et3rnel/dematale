<?php session_start();

if (!isset($_GET['id'])) header('Location:index.php');
include_once'cnx.php';

$check = $bdd->prepare('SELECT expediteur FROM mess_priv WHERE recepteur=? AND id=?');
$check->execute(array($_SESSION['pseudo'],$_GET['id']));
$donnees = $check->fetch();
if (empty($donnees['expediteur']))
	{
		header('Location:mess_priv.php?message=b_id');
	}
else
	{
		$supprimer = $bdd->prepare('DELETE FROM mess_priv WHERE id=?');
		$supprimer->execute(array($_GET['id']));
		header('Location:mess_priv.php');
	}

?>
