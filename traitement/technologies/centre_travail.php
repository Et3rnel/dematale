<?php session_start();

if (!isset($_SESSION['id'])) header('Location:../../index.php');

if(isset($_POST['subct']))
{
	include_once'../../actu.php';				
											
	$req1 = $bdd->prepare('SELECT centre_travail FROM production WHERE id = ?');			
	$req1->execute(array($_SESSION['id']));								
	$production = $req1->fetch();	

	$req2 = $bdd->prepare('SELECT paysan FROM ressources WHERE id=?');						
	$req2->execute(array($_SESSION['id']));										
	$nbr = $req2->fetch();		

	$req3 = $bdd->prepare('SELECT centre_travail FROM cout WHERE id=?');					
	$req3->execute(array($_SESSION['id']));								
	$cout = $req3->fetch();														
	
	$req01 = $bdd->prepare('SELECT coupe FROM membres WHERE id=?');
	$req01->execute(array($_SESSION['id']));
	$info = $req01->fetch();
	
	if($info['coupe']==1 || $info['coupe']==4)
	{
		$centre_paysan = $cout['centre_travail']-1;
	}
	else
	{
		$centre_paysan = $cout['centre_travail'];
	}
			
	if ($nbr['paysan'] >= $centre_paysan)
	{

		$req1->closeCursor();
		$req2->closeCursor();
		$req3->closeCursor();

		//-------------------Requêtes d'update-------------------
		$req4 = $bdd->prepare('UPDATE ressources SET paysan=paysan-? WHERE id=?');						
		$req4->execute(array($centre_paysan,$_SESSION['id']));			
		
		$req6 = $bdd->prepare('UPDATE production SET centre_travail=centre_travail+5 WHERE id=?');
		$req6->execute(array($_SESSION['id']));																	
		//---------------Fin des requetes d'update---------------//


		header('Location:../../technologies.php');	
	}
	else
	{
		header('Location:../../technologies.php?erreur=ct');
	}
}
else
{
	header('Location:../../technologies.php');
}


?>