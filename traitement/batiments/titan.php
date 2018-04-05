<?php session_start();

if (!isset($_SESSION['pseudo'])) header('Location:../../index.php');

if(isset($_POST['subt']))
{
	require_once'../../cnx.php';	

	$req15 = $bdd->prepare('SELECT level,coupe FROM membres WHERE id = ?');						
	$req15->execute(array($_SESSION['id']));										
	$info = $req15->fetch();
	if($info['level']<5)
	{
		header('Location:../../batiments.php?error=level');
	}
	include_once'../../actu.php';				

	$req1 = $bdd->prepare('SELECT bois,fer FROM ressources WHERE id = ?');						
	$req1->execute(array($_SESSION['id']));										
	$ressources = $req1->fetch();															

	$req21 = $bdd->prepare('SELECT titan FROM production WHERE id = ?');			
	$req21->execute(array($_SESSION['id']));								
	$production = $req21->fetch();	

	$req22 = $bdd->prepare('SELECT titan FROM niveau WHERE id = ?');			
	$req22->execute(array($_SESSION['id']));								
	$niveau = $req22->fetch();															

	$req3 = $bdd->prepare('SELECT titan_bois,titan_fer FROM cout WHERE id = ?');					
	$req3->execute(array($_SESSION['id']));								
	$cout = $req3->fetch();														

	$bois = $ressources['bois'];	
	$fer = $ressources['fer'];																
	if($info['coupe']==3 || $info['coupe']==4)
	{
		$titan_fer = floor($cout['titan_fer']*0.85);
		$titan_bois = floor($cout['titan_bois']*0.85);
	}
	else
	{
		$titan_fer = $cout['titan_fer'];
		$titan_bois = $cout['titan_bois'];
	}													
	$niv_titan = $niveau['titan'];
	$prod_titan = $production['titan'];

	if (($bois >= $titan_bois) && (($fer >= $titan_fer)))
	{
		$bois = $bois - $titan_bois;							
		$fer = $fer- $titan_fer;		
		include_once'../../fonction.php';			
		$prod_titan = DegTruncation($prod_titan*1.1+4*$niv_titan);			
		$titan_bois = DegTruncation($cout['titan_bois']*1.2+8*$niv_titan);
		$titan_fer = DegTruncation($cout['titan_fer']*1.2+8*$niv_titan);
	
		//-------------------Requêtes d'update-------------------//
		$req7 = $bdd->prepare('UPDATE membres SET points=points+5 WHERE id = ?');		
		$req7->execute(array($_SESSION['id']));	
	
		$req4 = $bdd->prepare('UPDATE ressources SET bois = ?, fer = ?  WHERE id = ?');		
		$req4->execute(array($bois,$fer,$_SESSION['id']));																							

		$req51 = $bdd->prepare('UPDATE production SET titan=? WHERE id=?');
		$req51->execute(array($prod_titan,$_SESSION['id']));	

		$req52 = $bdd->prepare('UPDATE niveau SET titan=titan+1 WHERE id=?');
		$req52->execute(array($_SESSION['id']));																									

		$req6 = $bdd->prepare('UPDATE cout SET titan_bois=?,titan_fer=? WHERE id=?');		
		$req6->execute(array($titan_bois,$titan_fer,$_SESSION['id']));																												
		//---------------Fin des requetes d'update---------------//
	
		include_once'../level.php';

		$req1->closeCursor();
		$req2->closeCursor(); 
		$req3->closeCursor();
		header('Location:../../batiments.php');		//Quand tout est fini on redirige le membre sur la page des batiments
	}
		else
	{
		header('Location:../../batiments.php?error=b_titan');
	}
}
else
{
	header('Location:../../batiments.php');
}

?>