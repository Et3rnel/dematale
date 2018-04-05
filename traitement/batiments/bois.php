<?php session_start();	
if (!isset($_SESSION['id'])) header('Location:../../index.php');

if(isset($_POST['subb']))
{
	include_once'../../actu.php';	
	
	$req1 = $bdd->prepare('SELECT fer,roche FROM ressources WHERE id = ?');						
	$req1->execute(array($_SESSION['id']));										
	$ressources = $req1->fetch();															

	$req21 = $bdd->prepare('SELECT bois FROM production WHERE id = ?');			
	$req21->execute(array($_SESSION['id']));								
	$production = $req21->fetch();	

	$req22 = $bdd->prepare('SELECT bois FROM niveau WHERE id = ?');			
	$req22->execute(array($_SESSION['id']));								
	$niveau = $req22->fetch();															

	$req3 = $bdd->prepare('SELECT bois_fer,bois_roche FROM cout WHERE id = ?');					
	$req3->execute(array($_SESSION['id']));								
	$cout = $req3->fetch();		
	
	$req01 = $bdd->prepare('SELECT coupe FROM membres WHERE id=?');
	$req01->execute(array($_SESSION['id']));
	$membre = $req01->fetch();
	
	$fer = $ressources['fer'];	
	$roche = $ressources['roche'];	
	if($membre['coupe']==3 || $membre['coupe']==4)
	{
		$bois_fer = floor($cout['bois_fer']*0.85);
		$bois_roche = floor($cout['bois_roche']*0.85);
	}
	else
	{
		$bois_fer = $cout['bois_fer'];
		$bois_roche = $cout['bois_roche'];
	}												
	$niv_bois = $niveau['bois'];
	$prod_bois = $production['bois'];

	if (($fer >= $bois_fer) && (($roche >= $bois_roche)))
	{
		$fer = $fer - $bois_fer;							
		$roche = $roche - $bois_roche;	
		include_once'../../fonction.php';			
		$prod_bois = DegTruncation($prod_bois*1.1+8*$niv_bois);	
		$bois_fer = DegTruncation($cout['bois_fer']*1.3+12*$niv_bois);
		$bois_roche= DegTruncation($cout['bois_roche']*1.3+12*$niv_bois);
	
		//-------------------Requêtes d'update-------------------//
		$req7 = $bdd->prepare('UPDATE membres SET points=FLOOR(points+1+(?/10))  WHERE id = ?');		
		$req7->execute(array($niv_bois,$_SESSION['id']));	
	
		$req4 = $bdd->prepare('UPDATE ressources SET fer = ?, roche = ?  WHERE id = ?');		
		$req4->execute(array($fer,$roche,$_SESSION['id']));																							

		$req51 = $bdd->prepare('UPDATE production SET bois=? WHERE id=?');
		$req51->execute(array($prod_bois,$_SESSION['id']));	

		$req52 = $bdd->prepare('UPDATE niveau SET bois=bois+1 WHERE id=?');
		$req52->execute(array($_SESSION['id']));	
		
		$req6 = $bdd->prepare('UPDATE cout SET bois_fer = ?,bois_roche =? WHERE id =?');		
		$req6->execute(array($bois_fer,$bois_roche,$_SESSION['id']));																												
		//---------------Fin des requetes d'update---------------//
	
		include_once'../level.php';
	
		$req1->closeCursor();
		$req2->closeCursor(); 
		$req3->closeCursor();
		header('Location:../../batiments.php');		//Quand tout est fini on redirige le membre sur la page des batiments
	}
	else
	{
		header('Location:../../batiments.php?error=b_bois');
	}
}
else
{
	header('Location:../../batiments.php');
}

?>