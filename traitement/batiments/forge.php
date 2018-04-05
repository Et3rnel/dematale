<?php session_start();
if (!isset($_SESSION['id'])) header('Location:../../index.php');

if(isset($_POST['subfo']))
{
	include_once'../../actu.php';				
	
	$req22 = $bdd->prepare('SELECT forge FROM niveau WHERE id = ?');			
	$req22->execute(array($_SESSION['id']));								
	$niveau = $req22->fetch();										

	if ($niveau['forge'] == 5){header('Location:../../batiments.php'); die();} 
	

	$req1 = $bdd->prepare('SELECT titan FROM ressources WHERE id=?');						
	$req1->execute(array($_SESSION['id']));										
	$ressources = $req1->fetch();		

	$req3 = $bdd->prepare('SELECT forge FROM cout WHERE id=?');					
	$req3->execute(array($_SESSION['id']));								
	$cout = $req3->fetch();		
	
	$req01 = $bdd->prepare('SELECT coupe FROM membres WHERE id=?');
	$req01->execute(array($_SESSION['id']));
	$info = $req01->fetch();

	if($info['coupe']==3 || $info['coupe']==4)
	{
		$forge_titane = $cout['forge']*0.85;
	}
	else
	{
		$forge_titane = $cout['forge'];
	}
					
	if ($ressources['titan'] >= $forge_titane)
	{	
		if($niveau['forge']==0)
		{
			$cout_forge = 35000;
		}
		else
		{
			$cout_forge = $cout['forge']+(20000*$niveau['forge']);
		}		

		$req1->closeCursor();
		$req22->closeCursor();
		$req3->closeCursor();

		//-------------------Requêtes d'update-------------------
		$req7 = $bdd->prepare('UPDATE membres SET points=points+2 WHERE id=?');						
		$req7->execute(array($_SESSION['id']));	
	
		$req4 = $bdd->prepare('UPDATE ressources SET titan=titan-? WHERE id=?');						
		$req4->execute(array($forge_titane,$_SESSION['id']));																					

		$req52 = $bdd->prepare('UPDATE niveau SET forge=forge+1 WHERE id=?');
		$req52->execute(array($_SESSION['id']));																								

		$req6 = $bdd->prepare('UPDATE cout SET forge=? WHERE id=?');				
		$req6->execute(array($cout_forge,$_SESSION['id']));																	
		//---------------Fin des requetes d'update---------------//
	
		include_once'../level.php';

		header('Location:../../batiments.php');	
	}
	else
	{
		header('Location:../../batiments.php?erreur=forge');
	}
}
else
{
	header('Location:../../batiments.php');
}


?>