<?php session_start();

if (!isset($_SESSION['id'])) header('Location:../index.php');

if(isset($_POST['subra']))
{
	include_once'../actu.php';				
	
	$req22 = $bdd->prepare('SELECT vitesse FROM niveau WHERE id = ?');			
	$req22->execute(array($_SESSION['id']));								
	$niveau = $req22->fetch();										

	if ($niveau['vitesse'] == 5){header('Location:../technologies.php'); die();} 
	
	$req21 = $bdd->prepare('SELECT vitesse FROM production WHERE id = ?');			
	$req21->execute(array($_SESSION['id']));								
	$production = $req21->fetch();	

	$req1 = $bdd->prepare('SELECT gold FROM ressources WHERE id=?');						
	$req1->execute(array($_SESSION['id']));										
	$ressources = $req1->fetch();		

	$req3 = $bdd->prepare('SELECT vitesse FROM cout WHERE id=?');					
	$req3->execute(array($_SESSION['id']));								
	$cout = $req3->fetch();		
	
	$req01 = $bdd->prepare('SELECT coupe FROM membres WHERE id=?');
	$req01->execute(array($_SESSION['id']));
	$info = $req01->fetch();

	if($info['coupe']==1 || $info['coupe']==4)
	{
		$vitesse_gold = $cout['vitesse']*0.95;
	}
	else
	{
		$vitesse_gold = $cout['vitesse'];
	}
					
	if ($ressources['gold'] >= $vitesse_gold)
	{
		$or = $ressources['gold'] - $vitesse_gold;		
		$cout_vitesse_f = $cout['vitesse']*5;										
		$niv_vitesse=$niveau['vitesse']+1;
		$temps_vitesse = $production['vitesse']-600;

		$req1->closeCursor();
		$req21->closeCursor();
		$req22->closeCursor();
		$req3->closeCursor();

		//-------------------Requêtes d'update-------------------
		$req7 = $bdd->prepare('UPDATE membres SET points=points+3 WHERE id=?');						
		$req7->execute(array($_SESSION['id']));	
	
		$req4 = $bdd->prepare('UPDATE ressources SET gold=? WHERE id=?');						
		$req4->execute(array($or,$_SESSION['id']));																					

		$req51 = $bdd->prepare('UPDATE production SET vitesse=? WHERE id=?');
		$req51->execute(array($temps_vitesse,$_SESSION['id']));	

		$req52 = $bdd->prepare('UPDATE niveau SET vitesse=? WHERE id=?');
		$req52->execute(array($niv_vitesse,$_SESSION['id']));																								

		$req6 = $bdd->prepare('UPDATE cout SET vitesse=? WHERE id=?');				
		$req6->execute(array($cout_vitesse_f,$_SESSION['id']));																	
		//---------------Fin des requetes d'update---------------//
	
		include_once'../level.php';

		header('Location:../technologies.php');	
	}
	else
	{
		header('Location:../technologies.php?error2=notor');
	}
}
else
{
	header('Location:../technologies.php');
}


?>