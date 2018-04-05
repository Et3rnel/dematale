<?php session_start();

if (!isset($_SESSION['id']))
{
	header('Location:../../index.php');
}	

if(isset($_POST['subf']))
{
	include_once'../../actu.php';	
	
	$req1 = $bdd->prepare('SELECT bois,roche FROM ressources WHERE id = ?');						
	$req1->execute(array($_SESSION['id']));										
	$ressources = $req1->fetch();															

	$req21 = $bdd->prepare('SELECT fer FROM production WHERE id = ?');			
	$req21->execute(array($_SESSION['id']));								
	$production = $req21->fetch();	

	$req22 = $bdd->prepare('SELECT fer FROM niveau WHERE id = ?');			
	$req22->execute(array($_SESSION['id']));								
	$niveau = $req22->fetch();		
	
	$req3 = $bdd->prepare('SELECT fer_bois,fer_roche FROM cout WHERE id = ?');					
	$req3->execute(array($_SESSION['id']));								
	$cout = $req3->fetch();		
	
	$req01 = $bdd->prepare('SELECT coupe FROM membres WHERE id=?');
	$req01->execute(array($_SESSION['id']));
	$membre = $req01->fetch();
	
	$bois = $ressources['bois'];	
	$roche = $ressources['roche'];	
	if($membre['coupe']==3 || $membre['coupe']==4)
	{
		$fer_bois = floor($cout['fer_bois']*0.85);
		$fer_roche = floor($cout['fer_roche']*0.85);
		
	}
	else
	{
		$fer_bois = $cout['fer_bois'];
		$fer_roche = $cout['fer_roche'];
	}
	$niv_fer = $niveau['fer'];
	$prod_fer = $production['fer'];

	if (($bois >= $fer_bois) && (($roche >= $fer_roche)))
	{	
		$bois = $bois - $fer_bois;							
		$roche = $roche - $fer_roche;	
		include_once'../../fonction.php';		
		$prod_fer = DegTruncation($prod_fer*1.1+8*$niv_fer);						
		$fer_bois = DegTruncation($cout['fer_bois']*1.3+12*$niv_fer);
		$fer_roche = DegTruncation($cout['fer_roche']*1.3+12*$niv_fer);
	
		//-------------------Requêtes d'update-------------------//
		$req7 = $bdd->prepare('UPDATE membres SET points=FLOOR(points+1+(?/10))  WHERE id = ?');		
		$req7->execute(array($niv_fer,$_SESSION['id']));	
	
		$req4 = $bdd->prepare('UPDATE ressources SET bois = ?, roche = ?  WHERE id = ?');		
		$req4->execute(array($bois,$roche,$_SESSION['id']));																							

		$req51 = $bdd->prepare('UPDATE production SET fer=? WHERE id=?');
		$req51->execute(array($prod_fer,$_SESSION['id']));	

		$req52 = $bdd->prepare('UPDATE niveau SET fer=fer+1 WHERE id=?');
		$req52->execute(array($_SESSION['id']));																								

		$req6 = $bdd->prepare('UPDATE cout SET fer_bois = ?,fer_roche =? WHERE id =?');		
		$req6->execute(array($fer_bois,$fer_roche,$_SESSION['id']));																											
		//---------------Fin des requetes d'update---------------//
	
		include_once'../level.php';

		$req1->closeCursor();
		$req2->closeCursor(); 
		$req3->closeCursor();
		header('Location:../../batiments.php');		//Quand tout est fini on redirige le membre sur la page des batiments
	}
	else
	{
		header('Location:../../batiments.php?error=b_fer');
	}
}
else
{
	header('Location:../../batiments.php');
}

?>