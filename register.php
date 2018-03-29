<?php session_start();
if (isset($_SESSION['pseudo'])) {header('Location:index.php');}		
require_once'cnx.php'; 

//si le formulaire est rempli et que tout est ok
if (isset($_POST['pseudo']) && isset($_POST['password']) && strlen($_POST['pseudo'])<=11 && strlen($_POST['pseudo'])>=5 && $_POST['password']==$_POST['conf_pass'] && strlen($_POST['password'])>=5 && filter_var($_POST['mail'],FILTER_VALIDATE_EMAIL))
{
	
	$pseudo=addslashes($_POST['pseudo']);
	
	if(preg_match("#^[a-zA-Z0-9]{2,}$#",$_POST['pseudo']))
	{
		$mail=addslashes($_POST['mail']);	
		$password=md5($_POST['password']);   
		$temps_ressources=time();				
		$message = 'Pour bien débuter dans le jeu, commencez par augmenter le niveau de vos bâtiments de fer de roche ainsi que de bois.
		Augmentez ensuite votre générateur d\'or pour pouvoir acheter des unités et commencer a attaquer ou vous défendre. 
		N\'oubliez pas d\'aller faire un tour du côté des technologies !';
		$titre = 'Bienvenue sur Dematale.com !';
	
		//on regarde si le pseudo existe déjà dans la bdd, si il existe on affiche une erreur si non on creer le compte
		$check = $bdd->prepare('SELECT pseudo FROM membres WHERE pseudo=?');
		$check->execute(array($pseudo));
		$donnees = $check->fetch();
		if (!empty($donnees['pseudo']))
		{
			$errors = array();
			$errors[] = '<span class="red">Le pseudo existe déjà, veuillez en choisir un autre.</span>';
		}
		else
		{
	
			$req1 = $bdd->prepare('INSERT INTO mess_priv(expediteur,recepteur,titre,message,date_mp) VALUES (:admin,:rec,:titre,:mess,:date)'); 
			$req1->execute(array('admin'=>'Admin', 'rec'=>$pseudo,'titre'=>$titre,'mess'=>$message,'date'=>$temps_ressources)); 
		
			$membres = $bdd->prepare('INSERT INTO membres(pseudo,password,mail) VALUES(?,?,?)'); 
			$membres->execute(array($pseudo,$password,$mail)); 
		
			$ressources = $bdd->prepare('INSERT INTO ressources(temps_ressources) VALUES(?)'); 
			$ressources->execute(array($temps_ressources)); 
			
			//-------------------Essayer de changer ces lignes qui doivent surement être inutiles----------------
			$cout = $bdd->query('INSERT INTO cout(fer_bois) VALUES(180)');
			$timer = $bdd->query('INSERT INTO timer(decret) VALUES(0)'); 			
			$req2 = $bdd->query('INSERT INTO production(fer) VALUES(380)'); 
			$req3 = $bdd->query('INSERT INTO niveau(fer) VALUES(1)'); 
			//---------------------------------------------------------------------------------------------------

	
			$req41 = $bdd->prepare('INSERT INTO armee(joueur,type,attaque,defense,prix,pillage) VALUES(?,?,?,?,?,?)'); 
			$req41->execute(array($pseudo,'recrue',6,4,30,5)); 
			$req42 = $bdd->prepare('INSERT INTO armee(joueur,type,attaque,defense,prix,pillage) VALUES(?,?,?,?,?,?)'); 
			$req42->execute(array($pseudo,'lieutenant',15,20,80,7)); 
			$req43 = $bdd->prepare('INSERT INTO armee(joueur,type,attaque,defense,prix,pillage) VALUES(?,?,?,?,?,?)'); 
			$req43->execute(array($pseudo,'capitaine',25,15,90,7)); 
			$req44 = $bdd->prepare('INSERT INTO armee(joueur,type,attaque,defense,prix,pillage) VALUES(?,?,?,?,?,?)'); 
			$req44->execute(array($pseudo,'commandant',50,50,205,12)); 
		
			header('Location:connexion.php?succes=1');
		}
		$check->closeCursor();
	}
	else
	{
		$errors[] = 'Votre pseudo doit être seulement composé de chiffres et de lettres.'; 
	}
}
elseif (!isset($_POST['pseudo']) && !isset($_POST['password']))
{
	//on ne fait rien, le formulaire n'est pas rempli
}
else
{	
	$errors = array();
	
	if (!filter_var($_POST['mail'],FILTER_VALIDATE_EMAIL))
	{ $errors[] = 'Votre e-mail n\'est pas valide.'; }
	if (strlen($_POST['pseudo'])<5)
	{ $errors[] = 'Votre pseudo doit faire plus de 4 caractères.'; }
	if (strlen($_POST['pseudo'])>11)
	{ $errors[] = 'Votre pseudo doit faire moins de 12 caractères.'; }
	if ($_POST['password']!=$_POST['conf_pass'])
	{ $errors[]= 'Vos deux mots de passe doivent être identiques.'; }
	if (strlen($_POST['password'])<5)
	{ $errors[] = 'Votre mot de passe doit faire plus de 5 caractères.'; }
} ?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
		<link rel="stylesheet" href="design.css" />
        <title>S'inscrire</title>
    </head>
    <body  id="white">

	

<h1 class="h1-w">S'inscrire</h1>
	<form class="form" method="post" action="register.php">
		<br/>
		<label class="decal" for="pseudo">Pseudo</label><br/>
		<input  class="decal" type="text" name="pseudo" id="pseudo" size="30" maxlength="11"/><br/>
		
		<label  class="decal" for="mail">E-mail</label><br/>
		<input  class="decal" type="text" name="mail" id="mail" size="30" maxlength="40" /><br/>
		
		<label  class="decal" for="password">Mot de passe</label><br/>
		<input  class="decal" type="password" name="password" id="password" size="30" maxlength="20" /><br/>
		
		<label  class="decal" for="conf_pass">Confirmation du mot de passe</label><br/>
		<input  class="decal" type="password" name="conf_pass" id="conf_pass" size="30" maxlength="20" /><br/>

		<p class="error">	
		<?php 
		if (isset($errors)) 
		{
			foreach ($errors as $error)
			{
				echo $error.'<br/><br/>';
			} 
		} 
		?>	
		</p>
		<input  class="decal" type="submit" value="Envoyer" /><p></p>
	</form>

<p class="retour_index"><a href="index.php">Retour à l'index</a></p>
    </body>
</html>