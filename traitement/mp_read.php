<?php session_start();

if (!isset($_SESSION['id']))
{
	header('Location:../index.php');
}
else
{ 
	include'../cnx.php';
	$req_statut = $bdd->prepare('UPDATE mess_priv SET statut=1 WHERE recepteur=?');
	$req_statut->execute(array($_SESSION['pseudo']));
	header('Location:../mess_priv.php?message=val');
} ?>
