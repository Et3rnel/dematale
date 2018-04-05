<?php
session_start();
require_once '../global_function.php';
require_once '../actu.php';
require_once '../fonction.php';

if(isset($_POST['updateofdeathlol']))
{
	if (empty($_POST['recrue']) && empty($_POST['capitaine']) && empty($_POST['lieutenant']) && empty($_POST['commandant'])) {
        redirectTo('armee', array('error' => 'empty'));
	} else {
		$req001 = $bdd->prepare('SELECT temple FROM niveau WHERE id=?');
		$req001->execute(array($_SESSION['id']));
		$level = $req001->fetch();

		$req01 = $bdd->prepare('SELECT coupe FROM membres WHERE id=?');
		$req01->execute(array($_SESSION['id']));
		$info = $req01->fetch();

		$req1 = $bdd->prepare('SELECT gold FROM ressources WHERE id = ?');
		$req1->execute(array($_SESSION['id']));
		$ressources = $req1->fetch();

        $reqUnits = $bdd->prepare('SELECT pa.id_unit, pa.unit_amount, ui.attack, ui.defense, ui.unit_name, ui.price, ui.looting_capacity, ui.unit_label FROM player_army pa
            INNER JOIN units_informations ui ON pa.id_unit = ui.id_unit
            WHERE id_player = ?');
        $reqUnits->execute(array($_SESSION['id']));
        $units = $reqUnits->fetchAll();

        $trophyDiscountValue = 0.1;
        $totalCost = 0;

        if ($info['coupe'] === 2 || $info['coupe'] === 4) {
            foreach ($units as $key => $unit) {
                $units[$key]['price'] = floor($unit['price'] - $unit['price'] * $trophyDiscountValue);
            }
        }

        // Retrieve how much units the player wants to buy
        foreach ($units as $key => $unit) {
            $units[$key]['wanted_number'] = negativeZero($_POST[$unit['unit_label']]);
            $totalCost += $units[$key]['price'] * $units[$key]['wanted_number'];
        }

		if ($totalCost <= $ressources['gold']) {
			$req3 = $bdd->prepare('UPDATE ressources SET gold = gold - ? WHERE id = ?');
			$req3->execute(array($totalCost, $_SESSION['id']));

            // Inserting the new units
            foreach ($units as $key => $unit) {
                $updateUnitsQuery = $bdd->prepare('UPDATE player_army SET unit_amount = unit_amount + ? WHERE id_player = ? AND id_unit = ?');
                $updateUnitsQuery->execute(array($unit['wanted_number'], $_SESSION['id'], $unit['id_unit']));
            }

            redirectTo('armee');
		} else {
            redirectTo('armee', array('error' => 'money'));
		}
	}

	$req01->closeCursor();
	$req1->closeCursor();
	$req21->closeCursor();
	$req22->closeCursor();
	$req23->closeCursor();
	$req24->closeCursor();
} else {
    redirectTo('armee');
}
