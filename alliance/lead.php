<?php session_start();

if(isset($_POST['lead']))
{
	require_once'../cnx.php';
	$req1 = $bdd->prepare('SELECT id_alliance FROM membres WHERE id=?');
	$req1->execute(array($_SESSION['id']));
	$membre = $req1->fetch();

	$req2 = $bdd->prepare('SELECT chef FROM alliance WHERE id=?');
	$req2->execute(array($membre['id_alliance']));
	$alliance = $req2->fetch();
	if($alliance['chef'] == $_SESSION['pseudo'])
	{	
		$req3 = $bdd->prepare('SELECT id_alliance FROM membres WHERE pseudo=?');
		$req3->execute(array($_POST['leader']));
		$test = $req3->fetch();
		
		if($test['id_alliance'] == $membre['id_alliance'])
		{
			$req4 = $bdd->prepare('UPDATE alliance SET chef=? WHERE id=?');
			$req4->execute(array($_POST['leader'],$membre['id_alliance']));
			
			header('Location:../alliance.php?msg=val'); //FAIRE
		}
		else
		{
			header('Location:../alliance.php?erreur=unknow'); //FAIRE
		}
	}
	else
	{
		header('Location:../alliance.php?erreur=unknow'); 
	}
}
else
{
	header('Location:../alliance.php');
}
