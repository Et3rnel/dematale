<?php session_start();

if(!isset($_SESSION['id']))
{
	header('Location:../../index.php');
}	

if(isset($_POST['subcom']))
{
	include_once'../../actu.php';	
	
	$req1 = $bdd->prepare('SELECT fer,bois,roche,gold FROM ressources WHERE id = ?');						
	$req1->execute(array($_SESSION['id']));										
	$ressources = $req1->fetch();																

	$req22 = $bdd->prepare('SELECT commerce FROM niveau WHERE id = ?');			
	$req22->execute(array($_SESSION['id']));								
	$niveau = $req22->fetch();		
	
	if($niveau['commerce']<2)
	{

		if($ressources['fer']>=30000 && $ressources['bois']>=30000 && $ressources['roche']>=30000 && $ressources['gold']>=50000)
		{
			$req001 = $bdd->prepare('UPDATE ressources SET fer=fer-30000,roche=roche-30000,bois=bois-30000,gold=gold-50000 WHERE id=?');
			$req001->execute(array($_SESSION['id']));
			
			$req002 = $bdd->prepare('UPDATE niveau SET commerce=commerce+1 WHERE id=?');
			$req002->execute(array($_SESSION['id']));
			
			$req003 = $bdd->prepare('UPDATE membres SET points=points+5 WHERE id=?');		
			$req003->execute(array($_SESSION['id']));	
						
			include_once'../level.php';
			
			header('Location:../../technologies.php');
		}
		else
		{
			header('Location:../../technologies.php?error=com');
		}
	}
	else
	{
		$req3 = $bdd->prepare('SELECT co_fer,co_gold FROM cout WHERE id=?');					
		$req3->execute(array($_SESSION['id']));								
		$cout = $req3->fetch();	
		
		$req01 = $bdd->prepare('SELECT coupe FROM membres WHERE id=?');
		$req01->execute(array($_SESSION['id']));
		$membre = $req01->fetch();
		
		if($membre['coupe']==1 || $membre['coupe']==4)
		{
			$coutfer = floor($cout['co_fer']*0.95);
			$coutgold = floor($cout['co_gold']*0.95);	
		}
		else
		{
			$coutfer = $cout['co_fer'];
			$coutgold = $cout['co_gold'];	
		}
		

		if(($ressources['fer'] >= $coutfer) && (($ressources['gold'] >= $coutgold)))
		{	
		
			$req011 = $bdd->prepare('UPDATE ressources SET fer=fer-?,gold=gold-? WHERE id=?');
			$req011->execute(array($coutfer,$coutgold,$_SESSION['id']));
			
			$req022 = $bdd->prepare('UPDATE niveau SET commerce=commerce+1 WHERE id=?');
			$req022->execute(array($_SESSION['id']));
			
			include_once'../../fonction.php';	
			$coutfer_f = DegTruncation(floor($cout['co_fer']*1.5));
			$coutgold_f = DegTruncation(floor($cout['co_gold']*1.5));
			
			$req033 = $bdd->prepare('UPDATE cout SET co_fer=?,co_gold=? WHERE id=?');		
			$req033->execute(array($coutfer_f,$coutgold_f,$_SESSION['id']));	
			
			$req044 = $bdd->prepare('UPDATE membres SET points=points+1 WHERE id=?');		
			$req044->execute(array($_SESSION['id']));	
			
			include_once'../level.php';
		
			header('Location:../../technologies.php');
		}
		else
		{
			header('Location:../../technologies.php?erreur=com');
		}
	}
	
}
else
{
	header('Location:../../technologies.php');
}
	
	
	
