<?php session_start();

if(!isset($_SESSION['id'])) header('Location:../../index.php');

if(isset($_POST['subfp']))
{	
	
	include_once'../../cnx.php';
	$req2 = $bdd->prepare('SELECT paysan FROM ressources WHERE id=?');						
	$req2->execute(array($_SESSION['id']));										
	$nbr = $req2->fetch();		
	
	if($nbr['paysan']<20) header('Location:../../technologies.php');

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
		
	$nombre_de_stack = floor($nbr['paysan']/$centre_paysan);
	$pertes_paysan = $nombre_de_stack*$centre_paysan;
	$up_du_centre = $nombre_de_stack*5;

	$req2->closeCursor();
	$req3->closeCursor();

	//-------------------Requêtes d'update-------------------
	$req4 = $bdd->prepare('UPDATE ressources SET paysan=paysan-? WHERE id=?');						
	$req4->execute(array($pertes_paysan,$_SESSION['id']));			
		
	$req6 = $bdd->prepare('UPDATE production SET centre_travail=centre_travail+? WHERE id=?');
	$req6->execute(array($up_du_centre,$_SESSION['id']));																	
	//---------------Fin des requetes d'update---------------//
		
	header('Location:../../technologies.php');	
}


else
{
	header('Location:../../technologies.php');
}


?>