<?php session_start();	
if (!isset($_SESSION['id'])) header('Location:../../index.php');

if(isset($_POST['subtemple']))
{
	include_once'../../actu.php';	
	
	$req1 = $bdd->prepare('SELECT temple FROM niveau WHERE id = ?');			
	$req1->execute(array($_SESSION['id']));								
	$niveau = $req1->fetch();	
	if($niveau['temple']>19)
	{ header('Location:../../batiments.php');
	die();}
		
	$req2 = $bdd->prepare('SELECT fer,bois FROM ressources WHERE id = ?');						
	$req2->execute(array($_SESSION['id']));										
	$ressources = $req2->fetch();																						

	$req3 = $bdd->prepare('SELECT fer_temple,bois_temple FROM cout WHERE id = ?');					
	$req3->execute(array($_SESSION['id']));								
	$cout = $req3->fetch();		
	
	$req4 = $bdd->prepare('SELECT coupe FROM membres WHERE id=?');
	$req4->execute(array($_SESSION['id']));
	$membre = $req4->fetch();
	
	$fer = $ressources['fer'];	
	$bois = $ressources['bois'];	
	if($membre['coupe']==3 || $membre['coupe']==4)
	{
		$fer_temple = floor($cout['fer_temple']*0.85);
		$bois_temple = floor($cout['bois_temple']*0.85);
	}
	else
	{
		$fer_temple = $cout['fer_temple'];
		$bois_temple = $cout['bois_temple'];
	}												

	if (($fer >= $fer_temple) && (($bois >= $bois_temple)))
	{
		include_once'../../fonction.php';			
		$fer_temple_f = DegTruncation($cout['fer_temple']*1.2);
		$bois_temple_f = DegTruncation($cout['bois_temple']*1.2);
	
		//-------------------Requêtes d'update-------------------//
		$req5 = $bdd->prepare('UPDATE membres SET points=points+2  WHERE id = ?');		
		$req5->execute(array($_SESSION['id']));	
	
		$req6 = $bdd->prepare('UPDATE ressources SET fer=fer-?, bois=bois-?  WHERE id = ?');		
		$req6->execute(array($fer_temple,$bois_temple,$_SESSION['id']));																							

		$req7 = $bdd->prepare('UPDATE niveau SET temple=temple+1 WHERE id=?');
		$req7->execute(array($_SESSION['id']));	
		
		$req8 = $bdd->prepare('UPDATE cout SET fer_temple=?, bois_temple=? WHERE id =?');		
		$req8->execute(array($fer_temple_f,$bois_temple_f,$_SESSION['id']));																												
		//---------------Fin des requetes d'update---------------//
	
		include_once'../level.php';
	
		$req1->closeCursor();
		$req2->closeCursor(); 
		$req3->closeCursor();
		$req4->closeCursor();
		header('Location:../../batiments.php');		//Quand tout est fini on redirige le membre sur la page des batiments
	}
	else
	{
		header('Location:../../batiments.php?msg=temple');
	}
}
else
{
	header('Location:../../batiments.php');
}

?>