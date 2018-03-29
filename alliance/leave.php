<?php session_start();

if(isset($_POST['leave']))
{
	require_once'../cnx.php';
	$req1 = $bdd->prepare('SELECT id_alliance FROM membres WHERE id=?');
	$req1->execute(array($_SESSION['id']));
	$membre = $req1->fetch();

	$req2 = $bdd->prepare('SELECT chef FROM alliance WHERE id=?');
	$req2->execute(array($membre['id_alliance']));
	$alliance = $req2->fetch();
	if($alliance['chef'] != $_SESSION['pseudo'])
	{	
	
		$req3 = $bdd->prepare('UPDATE alliance SET nbr_membre=nbr_membre-1 WHERE id=?');
		$req3->execute(array($membre['id_alliance']));
		
		$req4 = $bdd->prepare('UPDATE membres SET id_alliance=?,apply=? WHERE id=?');
		$req4->execute(array(0,0,$_SESSION['id']));
		
		header('Location:../alliance.php?msg=succes'); //FAIRE LE MESSAGE 
	}
	else
	{
		header('Location:../alliance.php?msg=lead'); //FAIRE LE MESSAGE
	}
}
else
{
	header('Location:../alliance.php');
}
