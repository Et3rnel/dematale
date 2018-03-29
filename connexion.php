<?php session_start(); 
if(isset($_SESSION['pseudo'])) header('Location:index.php'); //si l'utilisateur est connecté il ne peut pas acceder au formulaire de connexion
require_once'cnx.php';

if(isset($_POST['pseudo']) && isset($_POST['pass'])) 
{
$pseudo = htmlentities($_POST['pseudo']);  
$pass = md5(htmlentities($_POST['pass'])); 

$req = $bdd->prepare('SELECT id,pseudo,password,auth FROM membres WHERE pseudo = :pseudo AND password = :pass'); //on cherche dans la bdd le pseudo et le mdp qui correspondent
$req->execute(array('pseudo' => $pseudo, 'pass' => $pass));
$data = $req->fetch();  //data vas devenir un array avec les données de pseudo, id et pass

	if(!empty($data['pseudo']))
	{
	
		$_SESSION['pseudo'] = $data['pseudo'];
		$_SESSION['password'] = $data['password'];
		$_SESSION['id'] = $data['id'];
		$_SESSION['auth'] = $data['auth'];
		header('Location:index.php'); //quand l'utilisateur est connecté, on le redirige vers le menu
	}
	else
	{
		$message='<span class="red">Mauvais pseudo ou mauvais mot de passe</span>';
	}
}	
?>



<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
		<link rel="stylesheet" href="design.css" />
        <title>Se connecter</title>
    </head>
	<body  id="white">
	<h1 class="h1-w">Se connecter</h1>
		
	
		<form class="form" method="post" action="connexion.php">
		
			<br/>
				<label class="decal" for="pseudo">Pseudo</label>
			<br/>
				<input  class="decal" type="text" name="pseudo" id="pseudo" size="30" maxlength="11"/>
			<br/>
				<label  class="decal" for="pass">Mot de passe</label>
			<br/>
				<input  class="decal" type="password" name="pass" id="pass" size="30" maxlength="20" />
			<br/>
		
			<p class="error"><?php if (isset($message)) echo $message.'<br/>'; ?></p>
			<p class="succes"><?php if (isset($_GET['succes'])) echo 'Compte crée avec succès, vous pouvez à présent vous connecter !';?></p>
	
				<input class="decal" type="submit" value="Envoyer" /><p></p> 
	
		</form>

<p class="retour_index"><a href="index.php">Retour à l'index</a></p>
    </body>
</html>






