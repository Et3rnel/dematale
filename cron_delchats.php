<?php require_once'cnx.php';
$temps = time();



$req1 = $bdd->query('SELECT id,date_chat FROM chat');
while($normal = $req1->fetch())
{
	if(($temps-$normal['date_chat'])>=1728000)
	{
		$req3 = $bdd->prepare('DELETE FROM chat WHERE id=?');
		$req3->execute(array($normal['id']));
	}
}

$req2 = $bdd->query('SELECT id,date_alliance FROM chat_alliance');
while($alliance = $req2->fetch())
{
	if(($temps-$normal['date_alliance'])>=1728000)
	{
		$req4 = $bdd->prepare('DELETE FROM chat_alliance WHERE id=?');
		$req4->execute(array($normal['id']));
	}
}



