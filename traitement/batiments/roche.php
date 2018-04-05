<?php session_start();
			
if (!isset($_SESSION['id'])) header('Location:../../index.php');


if(isset($_POST['subr']))
{
	include_once'../../actu.php';	

	$req1 = $bdd->prepare('SELECT bois,fer FROM ressources WHERE id = ?');						
	$req1->execute(array($_SESSION['id']));										
	$ressources = $req1->fetch();															

	$req21 = $bdd->prepare('SELECT roche FROM production WHERE id = ?');			
	$req21->execute(array($_SESSION['id']));								
	$production = $req21->fetch();	

	$req22 = $bdd->prepare('SELECT roche FROM niveau WHERE id = ?');			
	$req22->execute(array($_SESSION['id']));								
	$niveau = $req22->fetch();		

	$req3 = $bdd->prepare('SELECT roche_fer,roche_bois FROM cout WHERE id = ?');					
	$req3->execute(array($_SESSION['id']));								
	$cout = $req3->fetch();		

	$req01 = $bdd->prepare('SELECT coupe FROM membres WHERE id=?');
	$req01->execute(array($_SESSION['id']));
	$membre = $req01->fetch();	

	$fer = $ressources['fer'];	
	$bois = $ressources['bois'];																
	if($membre['coupe']==3 || $membre['coupe']==4)
	{
		$roche_fer = floor($cout['roche_fer']*0.85);
		$roche_bois = floor($cout['roche_bois']*0.85);
	}
	else
	{
		$roche_fer = $cout['roche_fer'];
		$roche_bois = $cout['roche_bois'];
	}													
	$niv_roche = $niveau['roche'];
	$prod_roche = $production['roche'];

	if (($fer >= $roche_fer) && (($bois >= $roche_bois)))
	{
		$fer = $fer - $roche_fer;							
		$bois = $bois - $roche_bois;	
		include_once'../../fonction.php';		
		$prod_roche = DegTruncation($prod_roche*1.1+8*$niv_roche);		
		$roche_fer = DegTruncation($cout['roche_fer']*1.3+12*$niv_roche);
		$roche_bois = DegTruncation($cout['roche_bois']*1.3+12*$niv_roche);
	
		//-------------------Requêtes d'update-------------------//
		$req7 = $bdd->prepare('UPDATE membres SET points=FLOOR(points+1+(?/10)) WHERE id=?');		
		$req7->execute(array($niv_roche,$_SESSION['id']));	
	
		$req4 = $bdd->prepare('UPDATE ressources SET fer=?, bois=? WHERE id=?');		
		$req4->execute(array($fer,$bois,$_SESSION['id']));																							

		$req51 = $bdd->prepare('UPDATE production SET roche=? WHERE id=?');
		$req51->execute(array($prod_roche,$_SESSION['id']));	

		$req52 = $bdd->prepare('UPDATE niveau SET roche=roche+1 WHERE id=?');
		$req52->execute(array($_SESSION['id']));		

		$req6 = $bdd->prepare('UPDATE cout SET roche_fer=?, roche_bois=? WHERE id=?');		
		$req6->execute(array($roche_fer,$roche_bois,$_SESSION['id']));																												
		//---------------Fin des requetes d'update---------------//
	
		include_once'../level.php';
		
		$req1->closeCursor();
		$req2->closeCursor(); 
		$req3->closeCursor();
		header('Location:../../batiments.php');		//Quand tout est fini on redirige le membre sur la page des batiments
	}
	else
	{
		header('Location:../../batiments.php?error=b_roche');
	}
}
else
{
	header('Location:../../batiments.php');
}

?>