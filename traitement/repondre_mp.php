<?php session_start();
if(!isset($_SESSION['id'])) header('Location:index.php');
include_once'../fonction.php';
include_once'../cnx.php';

if(isset($_POST['rep_mp']))
{
	if(isset($_POST['message']) && strlen($_POST['message'])<=2000 && strlen($_POST['message'])>0)
	{
		$id = NegativZero(intval($_GET['id']));
		$req1 = $bdd->prepare('SELECT expediteur,titre FROM mess_priv WHERE id=? and recepteur=?');
		$req1->execute(array($id,$_SESSION['pseudo']));
		$membre = $req1->fetch();
		if(!empty($membre['titre']))
		{
			$date=time();
			$titre=$membre['titre'];
			$recepteur=$membre['expediteur'];
			$message=$_POST['message'];
			
			$req_mp = $bdd->prepare('INSERT INTO mess_priv(recepteur,expediteur,titre,message,date_mp) VALUES (?,?,?,?,?)'); 
			$req_mp->execute(array($recepteur,$_SESSION['pseudo'],$titre,$message,$date));
			
			$message = '<span class="green">Votre message privé a bien été envoyé</span>';
			
			header('Location:../mess_priv.php?msg=val');
		}
		else
		{
			header('Location:../mess_priv.php?erreur=b_id');
		}
	}
	else
	{	
		header('Location:../mess_priv.php?erreur=length');
	}
}
else
{
	header('Location:../index.php');
}