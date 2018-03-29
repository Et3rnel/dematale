<?php session_start();
require_once'cnx.php';

if(strlen($_POST['message']) < 5)
{
	header('Location:chat.php?length=low');
}
else
{	
    $time=time();
    $re1 = $bdd->prepare('INSERT INTO chat (pseudo, message, date_chat) VALUES(?, ?, ?)');
    $re1->execute(array($_SESSION['pseudo'], $_POST['message'], $time));

    $req2 = $bdd->prepare('UPDATE membres SET notification=notification+1 WHERE id != ?');
    $req2->execute(array($_SESSION['id']));	
    
    // Redirection du visiteur vers la page du minichat
    header('Location:chat.php');
}
?>