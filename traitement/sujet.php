<?php
session_start();
require_once'../cnx.php';
require_once '../global_function.php';

if(strlen($_POST['sujet'])>=6 && strlen($_POST['message'])>=10 && strlen($_POST['message'])<=3000) {
	$req1 = $bdd->query('SELECT MAX(id) FROM forum_sujet');
	$id = $req1->fetch();
	$corres_sujet=$id[0]+1;

	$temps=time();

    $req2 = $bdd->prepare('INSERT INTO forum_sujet (auteur,titre,date_derniere_reponse, type, locked) VALUES(?, ?, ?, ?, ?)');
    $req2->execute(array($_SESSION['pseudo'], $_POST['sujet'], $temps, 1, 0));

    $req3 = $bdd->prepare('INSERT INTO forum_reponse (auteur,message,correspondance_sujet,date_reponse, deleted) VALUES(?, ?, ?, ?, ?)');
    $req3->execute(array($_SESSION['pseudo'], $_POST['message'], $corres_sujet, $temps, 0));

    redirectTo('forum_messages', array('id_sujet_a_lire' => $corres_sujet));
} else {
    redirectTo('forum', array('erreur' => 'low'));
}
