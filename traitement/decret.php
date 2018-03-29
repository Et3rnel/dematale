<?php session_start();
if(!isset($_SESSION['pseudo'])) header('Location:../index.php');


if(isset($_POST['decret']))
{
	include_once'../cnx.php';
	
	$req1 = $bdd->prepare('SELECT decret,temps_decret FROM membres WHERE id=?');
	$req1->execute($_SESSION['id']);
	$membre = $req1->fetch();
	
	$req2 = $bdd->prepare('SELECT decret FROM timer WHERE id=?');
	$req2->execute($_SESSION['id']);
	$membre = $req2->fetch();
		
	if($_POST['mode']=='gp' || $_POST['mode']=='ti' || $_POST['mode']=='mo')
	{
		$temps = time();
		$dif_temps = $temps - $timer['decret'];
		if($dif_temps > 518400)
		{
			if($_POST['mode']=='gp')
			{
				$mode=1;
			}
			elseif($_POST['mode']=='ti')
			{
				$mode=2;
			}
			elseif($_POST['mode']=='mo')
			{
				$mode=3;
			}			
		}
		else
		{
			header('Location:../index.php?erreur=delai'); //Vous pouvez seulement changer votre décret tout les 6 jours
		}
		$req3 = $bdd->prepare('UPDATE membres SET decret=? WHERE id=?');
		$req3->execute(array($mode,$_SESSION['id']));

		$req4 = $bdd->prepare('UPDATE timer SET decret=? WHERE id=?');
		$req4->execute(array($temps,$_SESSION['id']));		
		header('Location:../index.php?msg=succes');
	}
	else
	{
		header('Location:../index.php');
	}
}
else
{	
	header('Location:../index.php');
}