<?php
session_start();
require_once 'global_function.php';

if (!isset($_SESSION['id'])) {
    redirectTo('index');
}

require_once 'actu.php';
require_once 'connectes.php';

function fillAmountByParam($unitGetParam)
{
    if (isset($_GET[$unitGetParam])) {
        return 'value="' . (int) $_GET[$unitGetParam] . '"';
    }
}

?>


<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
		<link rel="stylesheet" href="design.css" />
        <title>Armée</title>
    </head>
        <body onload="augmentation_ressource()">
<?php include_once'header.php'; ?>



<div id="g_section">
	<div id="band_l"></div>	<div id="band_r"></div>
	<section>

		<?php include_once'menu.php'; ?>
		<div id="corps">
		<h1>Armée</h1>


    <p class="padd">Ici, vous pouvez entrainer des unités afin de combattre vos ennemis. Vous pouvez entrainer différents types d'unités avec des caractéristiques différentes. Plus vous aurez d'unités, plus vous pourrez
    transporter de ressources lors de vos pillages ! Mais attention, les troupes les plus chères ne sont pas forcément celles qui peuvent porter le plus de ressources.</p><br/>

    <form method="post" action="traitement/creer_armee.php">
        <br/>
        <?php

        $req001 = $bdd->prepare('SELECT temple FROM niveau WHERE id=?');
        $req001->execute(array($_SESSION['id']));
        $level = $req001->fetch();

        $req01 = $bdd->prepare('SELECT coupe FROM membres WHERE id=?');
        $req01->execute(array($_SESSION['id']));
        $info = $req01->fetch();

        $ressourcesReq = $bdd->prepare('SELECT gold FROM ressources WHERE id = ?');
        $ressourcesReq->execute(array($_SESSION['id']));
        $ressources = $ressourcesReq->fetch();

        $reqUnits = $bdd->prepare('SELECT pa.id_unit, pa.unit_amount, ui.attack, ui.defense, ui.unit_name, ui.price, ui.looting_capacity FROM player_army pa
            INNER JOIN units_informations ui ON pa.id_unit = ui.id_unit
            WHERE id_player = ?');
        $reqUnits->execute(array($_SESSION['id']));
        $units = $reqUnits->fetchAll();

        $totalLootingCapacity = 0;
        foreach ($units as $key => $unit) {
            $totalLootingCapacity += $unit['looting_capacity'] * $unit['unit_amount'];

            // Also calculating max amount of unit we can buy with current money
            $units[$key]['nb_buyable'] = floor($ressources['gold'] / $unit['price']);
        }

        $trophyDiscountValue = 0.1;
        if ($info['coupe'] === 2 || $info['coupe'] === 4) {
            foreach ($units as $key => $unit) {
                $units[$key]['price'] = floor($unit['price'] - $unit['price'] * $trophyDiscountValue);
            }
        }

         foreach ($units as $key => $unit) {
             $unitId = strtolower($unit['unit_name']);

             echo '<div class="armee1"> <img src="images/army_units/arm_' . $unit['id_unit'] . '.png" alt="Icon d\'armée" class="img_arm" height="25" width="25"/>';
             echo '<label for="' . $unitId . '"><em>Nombre de ' . $unitId . '(s) :</em> </label>';
             echo '<input type="text" name="' . $unitId . '" id="' . $unitId . '" placeholder="Prix :' . $unit['price'] . ' or" ' . fillAmountByParam($unitId) . ' />';
             echo '<a href="armee.php?' . $unitId . '=' . $unit['nb_buyable'] . '" class="green">' . number_format($unit['nb_buyable'], 0, '.', ' ') . ' Max.</a></div>';
             echo '<div class="armee2">
             		<p class="armee3"><em>' . $unit['attack'] . ' att | ' . $unit['defense'] . ' def | ' . $unit['looting_capacity'] . ' ressources</em></p>
             	</div><br/>';
         }

    $req2 = $bdd->prepare('SELECT level FROM membres WHERE id=?');
    $req2->execute(array($_SESSION['id']));
    $info = $req2->fetch();


    if (isset($_GET['error'])) {
        $errorMessage = 'Undefined message';
        if ($_GET['error'] === 'empty') {
            $errorMessage = 'Vous n\'avez rempli aucun des champs.';
        } elseif ($_GET['error'] === 'money') {
            $errorMessage = 'Vous n\'avez pas assez de ressources.';
        }

        echo '<p class="mp_rouge">' . $errorMessage . '</p>';
    }
    ?>

	<p class="center_align">Vous pouvez piller <span class="capa_pillage"><?= number_format($totalLootingCapacity, 0, '.', ' '); ?></span> unités de chaque ressource.</p>

	<br/><input type="submit" class="marg270" name="updateofdeathlol" value="Entrainer les troupes !"/>

    </form>
	<?php $req_connecte = $bdd->query('SELECT COUNT(*) FROM connectes');
	$connectes = $req_connecte->fetchColumn(); ?>

	</div>
	<?php include_once'footer.php'; ?>
	</section>

	</div>
    </body >
</html>
