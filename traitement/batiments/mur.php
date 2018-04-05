<?php session_start();

if (!isset($_SESSION['pseudo'])) header('Location:../../index.php');

if(isset($_POST['subm']))
{
	include_once'../../actu.php';			
	
	$req1 = $bdd->prepare('SELECT roche FROM ressources WHERE id=?');					
	$req1->execute(array($_SESSION['id']));										
	$ressources = $req1->fetch();														

	$req21 = $bdd->prepare('SELECT mur FROM production WHERE id = ?');			
	$req21->execute(array($_SESSION['id']));								
	$production = $req21->fetch();	

	$req22 = $bdd->prepare('SELECT mur FROM niveau WHERE id = ?');			
	$req22->execute(array($_SESSION['id']));								
	$niveau = $req22->fetch();							

	$req3 = $bdd->prepare('SELECT roche_mur FROM cout WHERE id=?');					
	$req3->execute(array($_SESSION['id']));								
	$cout = $req3->fetch();													

	$req01 = $bdd->prepare('SELECT coupe FROM membres WHERE id=?');
	$req01->execute(array($_SESSION['id']));
	$membre = $req01->fetch();
	
	if($membre['coupe']==3 || $membre['coupe']==4)
	{
		$roche_mur = floor($cout['roche_mur']*0.85);
	}
	else
	{
		$roche_mur = $cout['roche_mur'];
	}

	if($ressources['roche'] >= $roche_mur) 
	{
		$roche = $ressources['roche'] - $roche_mur;		
		include_once'../../fonction.php';		
		$roche_mur_f = DegTruncation($cout['roche_mur']*2);
		$prod_mur = DegTruncation($production['mur']*2);

		//-------------------Requêtes d'update-------------------//
		$req7 = $bdd->prepare('UPDATE membres SET points=points+2 WHERE id=?');			
		$req7->execute(array($_SESSION['id']));		
	
		$req4 = $bdd->prepare('UPDATE ressources SET roche=? WHERE id=?');			
		$req4->execute(array($roche,$_SESSION['id']));					
																	
		$req51 = $bdd->prepare('UPDATE production SET mur=? WHERE id=?');
		$req51->execute(array($prod_mur,$_SESSION['id']));	

		$req52 = $bdd->prepare('UPDATE niveau SET mur=mur+1 WHERE id=?');
		$req52->execute(array($_SESSION['id']));			
																				
		$req6 = $bdd->prepare('UPDATE cout SET roche_mur=? WHERE id=?');		
		$req6->execute(array($roche_mur_f,$_SESSION['id']));																												
		//---------------Fin des requetes d'update---------------//
	
		include_once'../level.php';

		$req1->closeCursor();
		$req2->closeCursor(); 
		$req3->closeCursor(); 
		header('Location:../../batiments.php');		//Quand tout est fini on redirige le membre sur la page des batiments
	}
	else
	{
		header('Location:../../batiments.php?error=r_mur');
	}
}
else
{
	header('Location:../../batiments.php');
}



?>