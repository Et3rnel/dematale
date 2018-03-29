<?php include_once'cnx.php';
$temps = time();
$req1 = $bdd->query('SELECT id,last_connect,etat FROM membres');
while($membre = $req1->fetch())
{
	$dif_temps = $temps - $membre['last_connect'];
	if($membre['etat']!=0)
	{
		if($dif_temps > 1296000)
		{
			$req2 = $bdd->prepare('UPDATE membres SET etat=0 WHERE id=?');
			$req2->execute(array($membre['id']));
		} 
	}
	else
	{
		$req3 = $bdd->prepare('UPDATE membres SET etat=1 WHERE id=?');
		$req3->execute(array($membre['id']));
	}
} ?>