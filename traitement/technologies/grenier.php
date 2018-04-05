<?php session_start();
if (!isset($_SESSION['id'])) header('Location:../../index.php');

if(isset($_POST['subgr']))
{
	include_once'../../actu.php';			
	
	$req1 = $bdd->prepare('SELECT bois FROM ressources WHERE id=?');						
	$req1->execute(array($_SESSION['id']));										
	$ressources = $req1->fetch();

	$req2 = $bdd->prepare('SELECT grenier FROM niveau WHERE id=?');					
	$req2->execute(array($_SESSION['id']));										
	$tech = $req2->fetch();
		
	$req2 = $bdd->prepare('SELECT grenier FROM production WHERE id=?');					
	$req2->execute(array($_SESSION['id']));										
	$capacite= $req2->fetch();
	
	$req3 = $bdd->prepare('SELECT grenier FROM cout WHERE id=?');					
	$req3->execute(array($_SESSION['id']));								
	$cout = $req3->fetch();														
		
	$req01 = $bdd->prepare('SELECT coupe FROM membres WHERE id=?');
	$req01->execute(array($_SESSION['id']));
	$info = $req01->fetch();
	
	if($info['coupe']==1 || $info['coupe']==4)
	{
		$grenier_gold = $cout['grenier']*0.95;
	}
	else
	{
		$grenier_gold = $cout['grenier'];
	}
	
	
	if($ressources['bois'] >= $grenier_gold)
	{
		$bois = $ressources['bois'] - $grenier_gold;
		include_once'../../fonction.php';			
		$cout_grenier_f = DegTruncation($cout['grenier'] + $tech['grenier']*75);									
		$prod_grenier_f = DegTruncation($capacite['grenier'] + $tech['grenier']*75);
		
		//-------------------Requêtes d'update----------------------
		$req7 = $bdd->prepare('UPDATE membres SET points=points+1 WHERE id=?');						
		$req7->execute(array($_SESSION['id']));			
	
		$req4 = $bdd->prepare('UPDATE ressources SET bois=? WHERE id=?');						
		$req4->execute(array($bois,$_SESSION['id']));																											
	
		$req5 = $bdd->prepare('UPDATE niveau SET grenier=grenier+1 WHERE id=?');			
		$req5->execute(array($_SESSION['id']));	

		$req55 = $bdd->prepare('UPDATE production SET grenier=? WHERE id=?');			
		$req55->execute(array($prod_grenier_f,$_SESSION['id']));			

		$req6 = $bdd->prepare('UPDATE cout SET grenier=? WHERE id=?');					
		$req6->execute(array($cout_grenier_f,$_SESSION['id']));																					
		//---------------Fin des requetes d'update------------------
	
		include_once'../level.php';

		$req1->closeCursor();
		$req2->closeCursor(); 
		$req3->closeCursor();
		header('Location:../../technologies.php');		//Quand tout est fini on redirige le ressources sur la page des technologies
	}
	else
	{
		header('Location:../../technologies.php?erreur=gr');
	}
}
else
{
	header('Location:../../technologies.php');
}

?>