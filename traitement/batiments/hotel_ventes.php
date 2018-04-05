<?php session_start();
if (!isset($_SESSION['id'])) header('Location:../../index.php');

if(isset($_POST['subhv']))
{
	include_once'../../actu.php';	
	
	$req1 = $bdd->prepare('SELECT fer,roche FROM ressources WHERE id = ?');						
	$req1->execute(array($_SESSION['id']));										
	$ressources = $req1->fetch();															

	$req21 = $bdd->prepare('SELECT hotel_ventes FROM production WHERE id = ?');			
	$req21->execute(array($_SESSION['id']));								
	$production = $req21->fetch();	

	$req22 = $bdd->prepare('SELECT hotel_ventes FROM niveau WHERE id = ?');			
	$req22->execute(array($_SESSION['id']));								
	$niveau = $req22->fetch();															

	$req3 = $bdd->prepare('SELECT hv_fer,hv_roche FROM cout WHERE id = ?');					
	$req3->execute(array($_SESSION['id']));								
	$cout = $req3->fetch();		
	
	$req01 = $bdd->prepare('SELECT coupe FROM membres WHERE id=?');
	$req01->execute(array($_SESSION['id']));
	$membre = $req01->fetch();
	
	$fer = $ressources['fer'];	
	$roche = $ressources['roche'];	
	if($membre['coupe']==1 || $membre['coupe']==4)
	{
		$hv_fer = floor($cout['hv_fer']*0.95);
		$hv_roche = floor($cout['hv_roche']*0.95);
	}
	else
	{
		$hv_fer = $cout['hv_fer'];
		$hv_roche = $cout['hv_roche'];
	}												
	$niv_hv = $niveau['hotel_ventes'];
	$prod_hv = $production['hotel_ventes'];

	if (($fer >= $hv_fer) && (($roche >= $hv_roche)))
	{
		$fer = $fer - $hv_fer;							
		$roche = $roche - $hv_roche;					
		$prod_hv_f = $prod_hv+30000+(5000*$niv_hv);						
		$hv_fer = $cout['hv_fer']*1.3+40000+(5000*$niv_hv);
		$hv_roche = $cout['hv_roche']*1.3+40000+(5000*$niv_hv);
	
		//-------------------Requêtes d'update-------------------//
		$req7 = $bdd->prepare('UPDATE membres SET points=points+2  WHERE id = ?');		
		$req7->execute(array($_SESSION['id']));	
	
		$req4 = $bdd->prepare('UPDATE ressources SET fer = ?, roche = ?  WHERE id = ?');		
		$req4->execute(array($fer,$roche,$_SESSION['id']));																							

		$req51 = $bdd->prepare('UPDATE production SET hotel_ventes=? WHERE id=?');
		$req51->execute(array($prod_hv_f,$_SESSION['id']));	

		$req52 = $bdd->prepare('UPDATE niveau SET hotel_ventes=hotel_ventes+1 WHERE id=?');
		$req52->execute(array($_SESSION['id']));	
		
		$req6 = $bdd->prepare('UPDATE cout SET hv_fer = ?,hv_roche =? WHERE id =?');		
		$req6->execute(array($hv_fer,$hv_roche,$_SESSION['id']));																												
		//---------------Fin des requetes d'update---------------//
			
		include_once'../level.php';
	
		$req1->closeCursor();
		$req2->closeCursor(); 
		$req3->closeCursor();
		header('Location:../../technologies.php');		//Quand tout est fini on redirige le membre sur la page des technologies
	}
	else
	{
		header('Location:../../technologies.php?error=b_bois');
	}
}
else
{
	header('Location:../../technologies.php');
}

?>