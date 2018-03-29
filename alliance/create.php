<?php session_start(); 
if (!isset($_SESSION['pseudo'])) {header('Location:../index.php');}
require_once'../cnx.php'; 
//On ne peut pas creer d'alliance si on est deja dans une alliance
$ally = $bdd->prepare('SELECT id_alliance FROM membres WHERE pseudo=?');
$ally->execute(array($_SESSION['pseudo']));
$donnees = $ally->fetch();
if ($donnees['id_alliance']!=0){header('Location:../index.php');}
include_once'../actu.php';


if(isset($_POST['nom'])) 
{
	$name = addslashes($_POST['nom']);

	$check = $bdd->prepare('SELECT nom FROM alliance WHERE nom=?');
	$check->execute(array($name));
	$try = $check->fetch();

	if(empty($try['nom']) && strlen($_POST['nom'])<=15 && strlen($_POST['nom'])>3)//Si nom pas trop long et n'existe pas deja //FAIRE:SI LE NOM EXISTE PAS DEJA
	{
		$req1 = $bdd->prepare('SELECT fer,roche,bois,gold FROM ressources WHERE id = ?');		
		$req1->execute(array($_SESSION['id']));																			
		$ressources = $req1->fetch(); 
	
		$req2 = $bdd->prepare('SELECT level FROM membres WHERE id = ?');		
		$req2->execute(array($_SESSION['id']));																			
		$membre = $req2->fetch(); 
		
		if(preg_match("#^[a-zA-Z]{2,}$#",$_POST['nom']))
		{
			if($ressources['fer']<10000 || $ressources['roche']<10000 || $ressources['bois']<10000 || $ressources['gold']<20000 || $membre['level']<2)
			{
				$erreur = 'Vous ne remplissez pas les conditions requises !';
			}
			else
			{
				//Recup max id d'alliance
				$req10 = $bdd->query('SELECT MAX(id) FROM alliance');
				$id = $req10->fetch();
				$last_id=$id[0]+1;
			
				$req3=$bdd->prepare('UPDATE membres SET id_alliance=?,apply=1 WHERE id=?');	
				$req3->execute(array($last_id,$_SESSION['id']));
			
				$req4=$bdd->prepare('UPDATE ressources SET bois=bois-10000,fer=fer-10000,roche=roche-10000,gold=gold-20000 WHERE id=?');
				$req4->execute(array($_SESSION['id']));
			
				$req5 = $bdd->prepare('INSERT INTO alliance(nom,chef,nbr_membre) values(?,?,?)');
				$req5->execute(array($name,$_SESSION['pseudo'],1));
				
				$req6 = $bdd->prepare('INSERT INTO timer_alliance(donner_or_membre) values(?)');
				$req6->execute(array(0));
				
				$req7 = $bdd->prepare('INSERT INTO developpement(corruption) values(?)');
				$req7->execute(array(0));
			
				$req8 = $bdd->prepare('INSERT INTO niveau_alliance(corruption) values(?)');
				$req8->execute(array(1));
				
				$req9 = $bdd->prepare('INSERT INTO cout_alliance(corruption) values(?)');
				$req9->execute(array(4000));
			
				$req10->closeCursor(); 
				header('Location:../alliance.php');
			}
		}
		else
		{
			$erreur = 'Le nom de votre alliance peut seulement être composé de lettres.';
		}
	}
	else
	{
		$erreur = 'Le nom de votre alliance est trop long ou trop court (11 caracteres max. et 4 caracteres min.) ou ce nom est déjà utilisé par une autre alliance';
	}

}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
		<link rel="stylesheet" href="../design.css" />
        <title>Dematale, le jeu de strategie en ligne</title>
    </head>
    <body>

<?php include_once'../header.php'; ?>
	
	
	
<div id="g_section">
	<div id="band_l"></div>	<div id="band_r"></div>
	<section>
	
	
	<?php include_once'../menu.php'; ?>
	<div id="corps">
	<h1>Creer son alliance</h1>
	<div class="corps2">



<?php if(isset($erreur)) echo '<p class="padd-r">'.$erreur.'</p>';?>
<p class="padd">Pensez à verifier si vous avez bien les prè-requis pour creer une alliance ! (les ressources seront immediatement consommées apres la création)</p><br/>

<form class="center_align" method="post" action="create.php"> 
	<label for="nom">Nom de l'alliance : </label><input type="text" name="nom" id="nom" placeholder="Ne doit pas contenir de caractéres speciaux.." size="40" maxlength="20" />
	<br/><br/><input type="submit"  value="Creer l'alliance"/>
</form>
	<?php $req_connecte = $bdd->query('SELECT COUNT(*) FROM connectes');
	$connectes = $req_connecte->fetchColumn(); ?>

	</div></div>
	<?php include_once'../footer.php'; ?>
	</section>
	
	
</div>
	
    </body >
</html>