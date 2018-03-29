<?php
if(isset($_SESSION['id'])) 
{  
	require_once'cnx.php';
	
	$req1 = $bdd->prepare('SELECT fer,roche,bois,gold,titan,temps_ressources FROM ressources WHERE id = ?');
	$req1->execute(array($_SESSION['id']));
	$ressources = $req1->fetch();				
	
	$temps_actuel=time();
	$temps_ancien=$ressources['temps_ressources'];
	$temps_ecoule=$temps_actuel - $temps_ancien;  
	
	$req2 = $bdd->prepare('SELECT fer,bois,roche,gold,titan,centre_travail FROM production WHERE id = ?');
	$req2->execute(array($_SESSION['id']));
	$production = $req2->fetch();
	
	$check = $bdd->prepare('SELECT level,decret FROM membres WHERE id = ?');
	$check->execute(array($_SESSION['id']));
	$info = $check->fetch();  
	
	//On stock les production dans une variable
	$prod_titan = $production['titan'];
	if($info['decret'] == 1){$prod_gold = floor($production['gold'] + $production['gold']*0.05);}else{$prod_gold = $production['gold'];}
	if($info['decret'] == 2)
	{
		$prod_fer = floor($production['fer']*1.02);
		$prod_bois = floor($production['bois']*1.02);
		$prod_roche = floor($production['roche']*1.02);
	}
	else
	{
		$prod_fer = $production['fer'];
		$prod_bois = $production['bois'];
		$prod_roche = $production['roche'];
	}
	
	if($info['level'] >= 5)
	{
		$titan = ($prod_titan*($temps_ecoule/3600)); 
		$upd1 = $bdd->prepare('UPDATE ressources SET titan=titan+? WHERE id=?');
		$upd1->execute(array($titan,$_SESSION['id']));
	}
	//---------Fin du stockage-----------------

	
	$ct = ($production['centre_travail']*($temps_ecoule/3600)); 
	$fer = ($prod_fer*($temps_ecoule/3600)) + $ct; 
	$bois = ($prod_bois*($temps_ecoule/3600)) + $ct; 
	$roche = ($prod_roche*($temps_ecoule/3600)) + $ct; 
	$gold = ($prod_gold*($temps_ecoule/3600));


	$req3 = $bdd->prepare('UPDATE ressources SET fer=fer+:nbrfer, bois=bois+:nbrbois, roche=roche+:nbrroche, gold=gold+:nbrgold, temps_ressources=:temps WHERE id=:id'); 
	$req3->execute(array(
	'nbrfer' => $fer,
	'nbrbois' => $bois,
	'nbrroche' => $roche,
	'nbrgold' => $gold,
	'temps' => $temps_actuel,
	'id' => $_SESSION['id']));
	
	$temps = time(); 
	$req4 = $bdd->prepare('UPDATE membres SET last_connect=? WHERE id=?');
	$req4->execute(array($temps,$_SESSION['id']));

	$req1->closeCursor();
	$req2->closeCursor();
} 
?>












