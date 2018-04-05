<?php
session_start();
require_once '../cnx.php';
require_once '../global_function.php';

$chatPostInterval = 10;

$req1 = $bdd->prepare('SELECT poste FROM timer WHERE id=?');
$req1->execute(array($_SESSION['id']));
$timer = $req1->fetch();

$time=time();
if (($time - $timer['poste']) < $chatPostInterval) {
    redirectTo('chat', array('msg' => 'time'));
} else {
    if (strlen($_POST['message'])>500) {
        redirectTo('chat');
    } else {
		if (strlen($_POST['message']) < 2) {
            redirectTo('chat', array('length' => 'low'));
		} else {
            $dateTime = getDateTime();

			$chatInsert = $bdd->prepare('INSERT INTO chat(id_membre, message, date_chat) VALUES(?,?,?)');
			$chatInsert->execute(array($_SESSION['id'], $_POST['message'], $dateTime));

			$req2 = $bdd->prepare('UPDATE membres SET notif_chat = notif_chat + 1 WHERE id != ? && notif_chat < 15');
			$req2->execute(array($_SESSION['id']));

			$req3 = $bdd->prepare('UPDATE timer SET poste=? WHERE id=?');
			$req3->execute(array($time,$_SESSION['id']));

            redirectTo('chat');
		}
    }
}

?>
