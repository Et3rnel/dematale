<?php session_start();
include_once'../cnx.php';

if(isset($_POST['prive']))
{
	if(isset($_POST['recepteur']) && strlen($_POST['sujet_mp']) >=1 && strlen($_POST['sujet_mp']) <= 48 && strlen($_POST['message_mp'])>1  && strlen($_POST['message_mp']) <= 2000)
	{
		//on regarde si le mec a qui on envoie existe sur le jeu
		$recepteur=addslashes($_POST['recepteur']);
		$exist = $bdd->prepare('SELECT pseudo FROM membres WHERE pseudo=?');
		$exist->execute(array($recepteur));
		$donnees = $exist->fetch();
		if (empty($donnees['pseudo']))
		{
			 header('Location:../mess_priv.php?msg=notfound'); 
		}
		else
		{
			$date=time();
			$titre=$_POST['sujet_mp'];
			$message=$_POST['message_mp'];
		
			$req_mp = $bdd->prepare('INSERT INTO mess_priv (recepteur,expediteur,titre,message,date_mp) VALUES (?,?,?,?,?)'); 
			$req_mp->execute(array($recepteur,$_SESSION['pseudo'],$titre,$message,$date));
		
			 header('Location:../mess_priv.php?msg=succes');
		}
	}
	else
	{
		header('Location:../mess_priv.php?msg=empty');
	}
}