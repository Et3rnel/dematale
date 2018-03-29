<?php
session_start();
require_once'cnx.php';

if (strlen($_POST['message']) > 10) {
    // Insertion du message
	$message = $_POST['message'];
	$temps = time();
    $req1 = $bdd->prepare('INSERT INTO forum_reponse (auteur,message,correspondance_sujet,date_reponse) VALUES(?,?,?,?)');
    $req1->execute(array($_SESSION['pseudo'],$message,$_GET['sujet_id'],$temps));
	//Mise a jour de l'heure du sujet 
	$req2 = $bdd->prepare('UPDATE forum_sujet SET date_derniere_reponse=:temps WHERE id=:id');
    $req2->execute(array('id'=>$_GET['sujet_id'],'temps'=>$temps));
    
    // Redirection du visiteur vers le sujet avec le bon id
    header('Location: forum_messages.php?id_sujet_a_lire='.$_GET['sujet_id'].'');
}else{
	header('Location: forum_messages.php?id_sujet_a_lire='.$_GET['sujet_id'].'&erreur=low');
}
?>