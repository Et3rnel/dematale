<?php session_start();	

if (!isset($_SESSION['id'])) header('Location:../../index.php');

if(isset($_POST['subo']))
{
	include_once'../../actu.php';		

	
	$req1 = $bdd->prepare('SELECT fer,roche,bois FROM ressources WHERE id = ?');						
	$req1->execute(array($_SESSION['id']));										
	$ressources = $req1->fetch();															

	$req21 = $bdd->prepare('SELECT gold FROM production WHERE id = ?');			
	$req21->execute(array($_SESSION['id']));								
	$production = $req21->fetch();	

	$req22 = $bdd->prepare('SELECT gold FROM niveau WHERE id = ?');			
	$req22->execute(array($_SESSION['id']));								
	$niveau = $req22->fetch();													

	$req3 = $bdd->prepare('SELECT gold_fer,gold_bois,gold_roche FROM cout WHERE id = ?');							
	$req3->execute(array($_SESSION['id']));										
	$cout = $req3->fetch();																

	$req01 = $bdd->prepare('SELECT coupe FROM membres WHERE id=?');
	$req01->execute(array($_SESSION['id']));
	$membre = $req01->fetch();
	
	$fer = $ressources['fer'];	
	$roche = $ressources['roche'];	
	$bois = $ressources['bois'];															
	if($membre['coupe']==3 || $membre['coupe']==4)
	{
		$gold_fer = floor($cout['gold_fer']*0.85);
		$gold_bois = floor($cout['gold_bois']*0.85);
		$gold_roche = floor($cout['gold_roche']*0.85);
	}
	else
	{
		$gold_fer = $cout['gold_fer'];
		$gold_bois = $cout['gold_bois'];
		$gold_roche = $cout['gold_roche'];
	}											
	$niv_gold = $niveau['gold'];
	$prod_gold = $production['gold'];

	if (($fer >= $gold_fer) && ($roche >= $gold_roche) && ($bois >= $gold_bois))
	{
		$fer = $fer - $gold_fer;							
		$roche = $roche - $gold_roche;	
		$bois = $bois - $gold_bois;				
		include_once'../../fonction.php';
		$prod_gold = DegTruncation($prod_gold*1.1+8*$niv_gold);						
		$gold_fer = DegTruncation($cout['gold_fer']*1.3+12*$niv_gold);
		$gold_roche = DegTruncation($cout['gold_roche']*1.3+12*$niv_gold);
		$gold_bois = DegTruncation($cout['gold_bois']*1.3+12*$niv_gold);

		//-------------------Requêtes d'update-------------------//
		$req7 = $bdd->prepare('UPDATE membres SET points=points+3 WHERE id=?');
		$req7->execute(array($_SESSION['id']));	
		
		$req4 = $bdd->prepare('UPDATE ressources SET fer=?,roche =?,bois=? WHERE id=?');	
		$req4->execute(array($fer,$roche,$bois,$_SESSION['id']));		
	
		$req51 = $bdd->prepare('UPDATE production SET gold=? WHERE id=?');
		$req51->execute(array($prod_gold,$_SESSION['id']));	

		$req52 = $bdd->prepare('UPDATE niveau SET gold=gold+1 WHERE id=?');
		$req52->execute(array($_SESSION['id']));																								

		$req6 = $bdd->prepare('UPDATE cout SET gold_fer = ?, gold_roche = ?, gold_bois = ? WHERE id = ?');		
		$req6->execute(array($gold_fer,$gold_roche,$gold_bois,$_SESSION['id']));			
		//---------------Fin des requetes d'update---------------//
	
		include_once'../level.php';

		$req1->closeCursor();
		$req2->closeCursor(); 
		$req3->closeCursor();
		header('Location:../../batiments.php');		//Quand tout est fini on redirige le membre sur la page des batiments
	}
	else
	{
		header('Location:../../batiments.php?error=b_gold');
	}
}
else
{
	header('Location:../../batiments.php');
}

?>