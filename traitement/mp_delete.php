<?php session_start();

if (!isset($_SESSION['id']))
{
	header('Location:../index.php');
}
else
{ 
	if(isset($_POST['mp_delete']))
	{
		include'../cnx.php';
		$req1 = $bdd->prepare('DELETE FROM mess_priv WHERE recepteur=?');
		$req1->execute(array($_SESSION['pseudo']));
		header('Location:../mess_priv.php?message=val2');
	}
	else
	{
		header('Location:../mess_priv.php');
	}
} ?>
