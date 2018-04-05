<?php
session_start();
require_once 'global_function.php';
if (isset($_SESSION['pseudo'])) {
    redirectTo('index');
}
require_once 'cnx.php';

//si le formulaire est rempli et que tout est ok
if (isset($_POST['pseudo']) && isset($_POST['password']) && strlen($_POST['pseudo']) <= 11 && strlen($_POST['pseudo']) >= 5 && $_POST['password'] == $_POST['conf_pass'] && strlen($_POST['password']) >= 5 && filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL)) {
    $pseudo = addslashes($_POST['pseudo']);

    if (preg_match("#^[a-zA-Z0-9]{2,}$#", $_POST['pseudo'])) {
        $mail             = addslashes($_POST['mail']);
        $password         = md5($_POST['password']);
        $temps_ressources = time();
        $notificationText          = 'Pour bien débuter dans le jeu, commencez par augmenter le niveau de vos bâtiments de fer de roche ainsi que de bois.
		Augmentez ensuite votre générateur d\'or pour pouvoir acheter des unités et commencer a attaquer ou vous défendre.
		N\'oubliez pas d\'aller faire un tour du côté des technologies !';
        $notificationTitle            = 'Bienvenue sur Dematale.com !';

        //on regarde si le pseudo existe déjà dans la bdd, si il existe on affiche une erreur si non on creer le compte
        $check = $bdd->prepare('SELECT pseudo FROM membres WHERE pseudo=?');
        $check->execute(array(
            $pseudo
        ));
        $donnees = $check->fetch();
        if (!empty($donnees['pseudo'])) {
            $errors   = array();
            $errors[] = '<span class="red">Le pseudo existe déjà, veuillez en choisir un autre.</span>';
        } else {
            try {
                $bdd->beginTransaction();

                $membres = $bdd->prepare('INSERT INTO membres(pseudo,password,mail) VALUES(?,?,?)');
                $membres->execute(array(
                    $pseudo,
                    $password,
                    $mail
                ));

                $playerId = $bdd->lastInsertId();

                // TODO : faire un rollback si qq SQL se passe mal
                $ressources = $bdd->prepare('INSERT INTO ressources(id, temps_ressources) VALUES(?, ?)');
                $ressources->execute(array(
                    $playerId,
                    $temps_ressources
                ));

                //-------------------Essayer de changer ces lignes qui doivent surement être inutiles----------------
                // TODO : mettre les valeurs dans le code au lieu de la bdd
                $cout  = $bdd->query('INSERT INTO cout(id, fer_bois) VALUES(' . $playerId . ', 180)');
                $timer = $bdd->query('INSERT INTO timer(id, decret) VALUES(' . $playerId . ', 0)');
                $req2  = $bdd->query('INSERT INTO production(id, fer) VALUES(' . $playerId . ', 380)');
                $req3  = $bdd->query('INSERT INTO niveau(id, fer) VALUES(' . $playerId . ', 1)');
                //---------------------------------------------------------------------------------------------------


                // TODO : fetch les types d'unit pour les init à zéro

                $reqUnitTypes = $bdd->query('SELECT id_unit FROM units_informations');
                $unitTypes = $reqUnitTypes->fetchAll();

                foreach ($unitTypes as $key => $unit) {
                    $playerArmy = $bdd->prepare('INSERT INTO player_army(id_unit, id_player, unit_amount) VALUES(?, ?, 0)');
                    $playerArmy->execute(array($unit['id_unit'], $playerId));
                }

                $dateTime = getDateTime();

                $req1 = $bdd->prepare('INSERT INTO notification(membre_id, notification_title, notification_text, notification_date) VALUES (?, ?, ?, ?)');
                $req1->execute(array($playerId, $notificationTitle, $notificationText, $dateTime));

                $bdd->commit();
                redirectTo('connexion', array('success' => 1));
            } catch (PDOException $e) {
                $bdd->rollback();
                die($e);
                // TODO : virer ça pour la prod
                redirectTo('register', array('techerror' => 1));
            }

        }
        $check->closeCursor();
    } else {
        $errors[] = 'Votre pseudo doit être seulement composé de chiffres et de lettres.';
    }
} elseif (!isset($_POST['pseudo']) && !isset($_POST['password'])) {
    //on ne fait rien, le formulaire n'est pas rempli
} else {
    $errors = array();

    if (!filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Votre e-mail n\'est pas valide.';
    }
    if (strlen($_POST['pseudo']) < 5) {
        $errors[] = 'Votre pseudo doit faire plus de 4 caractères.';
    }
    if (strlen($_POST['pseudo']) > 11) {
        $errors[] = 'Votre pseudo doit faire moins de 12 caractères.';
    }
    if ($_POST['password'] != $_POST['conf_pass']) {
        $errors[] = 'Vos deux mots de passe doivent être identiques.';
    }
    if (strlen($_POST['password']) < 5) {
        $errors[] = 'Votre mot de passe doit faire plus de 5 caractères.';
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
		<link rel="stylesheet" href="design.css" />
        <title>S'inscrire</title>
    </head>
    <body id="white">



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
if (isset($errors)) {
    foreach ($errors as $error) {
        echo $error . '<br/><br/>';
    }
}
?>
		</p>
		<input  class="decal" type="submit" value="Envoyer" /><p></p>
	</form>

<p class="retour_index"><a href="index.php">Retour à l'index</a></p>
    </body>
</html>
