<?php session_start();
if(!isset($_SESSION['id'])) header('Location:../../index.php');

if(isset($_POST['sube']))
{
	include_once'../../actu.php';																

	$req1 = $bdd->prepare('SELECT gold FROM ressources WHERE id=?');						
	$req1->execute(array($_SESSION['id']));										
	$ressources = $req1->fetch();

	$req2 = $bdd->prepare('SELECT espio FROM cout WHERE id=?');					
	$req2->execute(array($_SESSION['id']));								
	$cout = $req2->fetch();														
	
	$req01 = $bdd->prepare('SELECT coupe FROM membres WHERE id=?');
	$req01->execute(array($_SESSION['id']));
	$info = $req01->fetch();

	if($info['coupe']==1 || $info['coupe']==4)
	{
		$espio_gold = $cout['espio']*0.95;
	}
	else
	{
		$espio_gold = $cout['espio'];
	}

	include_once'../../fonction.php';
	$cout_espio = DegTruncation($cout['espio']*1.5);
	
	if($ressources['gold'] >= $espio_gold)
	{	
		
		//-------------------Requêtes d'update----------------------
		$req7 = $bdd->prepare('UPDATE membres SET points=points+1 WHERE id=?');						
		$req7->execute(array($_SESSION['id']));			
	
		$req4 = $bdd->prepare('UPDATE ressources SET gold=gold-? WHERE id=?');						
		$req4->execute(array($espio_gold,$_SESSION['id']));																											
	
		$req5 = $bdd->prepare('UPDATE niveau SET espionnage=espionnage+1 WHERE id=?');			
		$req5->execute(array($_SESSION['id']));																									

		$req6 = $bdd->prepare('UPDATE cout SET espio=? WHERE id=?');					
		$req6->execute(array($cout_espio,$_SESSION['id']));																					
		//---------------Fin des requetes d'update------------------
	
		include_once'../level.php';

		header('Location:../../technologies.php');
	}
	else
	{
		header('Location:../../technologies.php?msg=orspy');
	}
}
else
{
	header('Location:../../technologies.php');
}

?>