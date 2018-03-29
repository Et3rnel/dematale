<?php include_once'cnx.php';
	
	
if((!empty($_SESSION['id']))) //Si le mec est connecté 
{ 
	$req1 = $bdd->prepare('SELECT COUNT(*) ip FROM connectes WHERE ip=? ');
	$req1->execute(array($_SERVER['REMOTE_ADDR']));
		
	if($req1->fetchColumn() == 0) //on regarde si il est déjà inscrit sous le statut "connecté"
	{
		$req2= $bdd->prepare('INSERT INTO connectes VALUES(?,?,?)');
		$req2->execute(array($_SERVER['REMOTE_ADDR'],time(),$_SESSION['id']));	
	}
	else //si c'est pas le cas on ajoute l'heure de connexion, son ip et son id 
	{
		$req3 = $bdd->prepare('UPDATE connectes SET last_connexion=?,id_membre=? WHERE ip=?'); 
		$req3->execute(array(time(),$_SESSION['id'],$_SERVER['REMOTE_ADDR']));	
	}
		 
	$timestamp_5min = time() - (60 * 5); 

	$req4 = $bdd->prepare('DELETE FROM connectes WHERE last_connexion<?');
	$req4->execute(array($timestamp_5min));
}
else
{
	header('Location:index.php');
} 

$req1->closeCursor(); ?>