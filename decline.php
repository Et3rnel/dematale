<?php session_start(); 
if (!isset($_SESSION['pseudo']) || !isset($_GET['pseudo'])) {header('Location:index.php');}
require_once'cnx.php'; 

$pseudo=addslashes($_GET['pseudo']);

$ally = $bdd->prepare('SELECT id_alliance,apply FROM membres WHERE pseudo=?');
$ally->execute(array($pseudo));
$donnees = $ally->fetch();

$req=$bdd->prepare('SELECT id_alliance FROM membres WHERE id=?');
$req->execute(array($_SESSION['id']));
$check = $req->fetch();

$req2=$bdd->prepare('SELECT chef FROM alliance WHERE id=?');
$req2->execute(array($check['id_alliance']));
$test = $req2->fetch();

if ($donnees['id_alliance']==$check['id_alliance'] && $donnees['apply']==0 && $test['chef']==$_SESSION['pseudo'])
{
	$req3=$bdd->prepare('UPDATE membres SET id_alliance=0 WHERE pseudo=?');
	$req3->execute(array($pseudo));
	header('Location:alliance.php'); 
}
else
{
	 header('Location:alliance.php'); 
}