<?php session_start();
require_once'../cnx.php';
require_once'../fonction.php';
require_once '../global_function.php';

$id = negativeZero(intval($_GET['sujet_id']));
$page = negativeZero(intval($_GET['page']));

$req1 = $bdd->prepare('SELECT poste FROM timer WHERE id=?');
$req1->execute(array($_SESSION['id']));
$timer = $req1->fetch();

$time=time();
if(($time - $timer['poste'])<15)
{
	header('Location:../forum_messages.php?id_sujet_a_lire='.$id.'&page='.$page.'&msg=time');
}
else
{
	if (strlen($_POST['message'])>=10 && strlen($_POST['message'])<=3000)
	{
		$message = $_POST['message'];
		$temps = time();
	    $req1 = $bdd->prepare('INSERT INTO forum_reponse (auteur,message,correspondance_sujet,date_reponse) VALUES(?,?,?,?)');
	    $req1->execute(array($_SESSION['pseudo'],$message,$id,$temps));

		$req2 = $bdd->prepare('UPDATE forum_sujet SET date_derniere_reponse=?,nbr_message=nbr_message+1 WHERE id=?');
	    $req2->execute(array($temps,$id));

		$req3 = $bdd->prepare('UPDATE timer SET poste=? WHERE id=?');
		$req3->execute(array($time,$_SESSION['id']));

	    header('Location:../forum_messages.php?id_sujet_a_lire='.$id.'&page='.$page.'');
	}
	else
	{
		header('Location:../forum_messages.php?id_sujet_a_lire='.$id.'&page='.$page.'&erreur=low');
	}
}
?>
