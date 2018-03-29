<?php
session_start();
require_once'cnx.php';

if(strlen($_POST['sujet']) >= 6 && strlen($_POST['message']) >= 10){
	$req1 = $bdd->query('SELECT MAX(id) FROM forum_sujet');
	$id = $req1->fetch();
	$corres_sujet=$id[0]+1;
	$req1->closeCursor();
	$temps=time();
	
   // Insertion du message 
    $req2 = $bdd->prepare('INSERT INTO forum_sujet (auteur,titre,date_derniere_reponse) VALUES(?,?,?)');
    $req2->execute(array($_SESSION['pseudo'],$_POST['sujet'],$temps));
	
    $req3 = $bdd->prepare('INSERT INTO forum_reponse (auteur,message,correspondance_sujet,date_reponse) VALUES(?,?,?,?)');
    $req3->execute(array($_SESSION['pseudo'],$_POST['message'],$corres_sujet,$temps));


	 header('Location:forum_messages.php?id_sujet_a_lire='.$corres_sujet.'');

}else{
	header('Location:forum.php?erreur=low');
}

   
?>