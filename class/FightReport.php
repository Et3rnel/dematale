$defenderReport<?php

/**
 * Class used to generate the fight report text after a fight between two players
 */
class FightReport
{
    private $attacker;
    private $defender;

    private $isFightWon;

    private $opponentPseudo;
    private $stolenRessources;
    private $nbPeasants;

    function __construct(array $attacker, array $defender, bool $isFightWon, string $opponentPseudo, array $stolenRessources, int $nbPeasants) {
        $this->setAttacker($attacker);
        $this->setDefender($defender);
        $this->setIsFightWon($isFightWon);
        $this->setOpponentPseudo($opponentPseudo);
        $this->setStolenRessources($stolenRessources);
        $this->setNbPeasants($nbPeasants);
    }

    /**
     * Display the list of units for the given type
     * @param  string $whoUnits       The player units to choose
     * @param  string $unitsType      The type of units, lost units or units before fight
     * @return string                 The formatted units text
     */
    private function displayUnits(string $whoUnits, string $unitsType)
    {
        $unitsText = '';
        if ($whoUnits === 'attacker') {
            $units = $this->attacker;
        } else {
            $units = $this->defender;
        }

        foreach ($units as $unit) {
            $unitsText .= number_format($unit[$unitsType], 0, '.', ' ') . '<img src="images/army_units/arm_' . $unit['id_unit'] . '.png" alt="' . $unit['unit_name'] . '" align="top" height="15" width="15"/> ' . strtolower($unit['unit_name']) . '(s)<br/>';
        }

        return $unitsText;
    }

    /**
     * Generate the fight text report for the attacker
     */
    public function generateAttackerReport()
    {
        $attackerReport = '';

        // Settings messages according to fight outcome
        if ($this->isFightWon) {
            $className = 'fight-report-won';
            $reportMsg = 'Vous remportez le combat !';

            if ($this->nbPeasants > 0) {
                $peasantMsg = '<p class="paysan">En vous regardant combattre <strong>' . number_format($paysan, 0, '.', ' ') . '</strong>
                    <img src="/images/paysan.png" title="Paysan(s)" alt="Paysan(s)" align="top" height="16"/> décident de rejoindre vos rangs.</p>';
            } else {
                $peasantMsg = '<p class="paysan">En regardant le combat, aucun paysan ne semble décidé à vous rejoindre.</p>';
            }
        } else {
            $className = 'fight-report-lost';
            $reportMsg = 'Votre attaque sur <a class="red" href="profil.php?pseudo=' . $this->opponentPseudo . '">' . $this->opponentPseudo . '</a> a échoué !';
            $peasantMsg = '<p class="paysan">Vous ne volez aucune ressources ! En partant, vous apercevez quelques paysans vous regarder tout en rigolant.</p>';
        }

        $reportTitle = '<p class="' . $className . '">' . $reportMsg . '</p>';

        // Generation of the report
        $attackerReport .= $reportTitle;
        $attackerReport .= '<p class="ses_pertes">Votre armée au moment du combat :<br/>';
        $attackerReport .= $this->displayUnits('attacker', 'unit_amount') . '</p>';

        $attackerReport .= '<p class="ses_pertes">Votre adversaire a perdu :<br/>';
        $attackerReport .= $this->displayUnits('defender', 'lost_units') . '</p>';

        $attackerReport .= '<p class="mes_pertes">Vous perdez :<br/>';
        $attackerReport .= $this->displayUnits('attacker', 'lost_units') . '</p>';

        if ($this->isFightWon) {
            $attackerReport .= '<p class="ses_pertes">Vous pillez :';

            foreach ($this->stolenRessources as $ressource) {
                $attackerReport .= number_format($ressource['quantity'], 0, '.', ' ').' <img src="images/' . $ressource['name'] . '_icon.png" alt="Icon du ' . $ressource['name'] . '" align="top" height="15" width="15"/> ' . $ressource['name'];
            }

            $attackerReport .= '.</p>';
        }

        $attackerReport .= $peasantMsg;
        return $attackerReport;
    }


    public function generateDefenderReport()
    {
        $defenderReport = '';

        // Settings messages according to fight outcome
        if ($this->isFightWon) {
            $className = 'fight-report-lost';
            $reportMsg = 'Vous vous êtes fait attaqué par ' . $_SESSION['pseudo'] . ' et vous perdez le combat !';
        } else {
            $className = 'fight-report-won';
            $reportMsg = 'Vous vous êtes fait attaqué par <a class="link_rc_1" href="profil.php?pseudo=' .$_SESSION['pseudo'] . '">' . $_SESSION['pseudo'] . '</a> !';
            $peasantMsg = '<p class="paysan">Votre adversaire n\'a pas réussi à vous voler des ressources ! Vous le regardez partir tout en rigolant.</p>';

            if ($this->nbPeasants > 0) {
                $peasantMsg .= '<p class="paysan">En voyant le désastre chez votre adversaire, ' . $this->nbPeasants . ' paysans sont venu se joindre à vous !</p>';
            } else {
                $peasantMsg .= '<p class="paysan">En regardant le combat, aucun paysan ne semble décidé à vous rejoindre.</p>';
            }
        }

        $reportTitle = '<p class="' . $className . '">' . $reportMsg . '</p>';

        // Generation of the report
        $defenderReport .= $reportTitle;
        $defenderReport .= '<p class="ses_pertes">Votre armée au moment du combat :<br/>';
        $defenderReport .= $this->displayUnits('defender', 'unit_amount') . '</p>';

        $defenderReport .= '<p class="ses_pertes">Votre adversaire a perdu :<br/>';
        $defenderReport .= $this->displayUnits('attacker', 'lost_units') . '</p>';

        $defenderReport .= '<p class="mes_pertes">Vous perdez :<br/>';
        $defenderReport .= $this->displayUnits('defender', 'lost_units') . '</p>';

        if ($this->isFightWon) {
            $defenderReport .= '<p class="ses_pertes">Il vous à volé :';

            foreach ($this->stolenRessources as $ressource) {
                $defenderReport .= number_format($ressource['quantity'], 0, '.', ' ').' <img src="images/' . $ressource['name'] . '_icon.png" alt="Icon du ' . $ressource['name'] . '" align="top" height="15" width="15"/> ' . $ressource['name'];
            }

            $defenderReport .= '.</p>';
        }

        $defenderReport .= $peasantMsg;
        return $defenderReport;
    }


    // Getters and setters
	public function getAttacker() : array
    {
		return $this->attacker;
	}

	public function setAttacker(array $attacker)
    {
		$this->attacker = $attacker;
	}

	public function getDefender() : array
    {
		return $this->defender;
	}

	public function setDefender(array $defender)
    {
		$this->defender = $defender;
	}

	public function getIsFightWon() : bool
    {
		return $this->isFightWon;
	}

	public function setIsFightWon(bool $isFightWon)
    {
		$this->isFightWon = $isFightWon;
	}

	public function getOpponentPseudo() : string
    {
		return $this->opponentPseudo;
	}

	public function setOpponentPseudo(string $opponentPseudo)
    {
		$this->opponentPseudo = $opponentPseudo;
	}

    public function getStolenRessources() : array
    {
        return $this->stolenRessources;
    }

    public function setStolenRessources(array $stolenRessources)
    {
        $this->stolenRessources = $stolenRessources;
    }

    public function setNbPeasants(int $nbPeasants)
    {
        $this->nbPeasants = $nbPeasants;
    }

    public function getNbPeasants() : int
    {
        return $this->nbPeasants;
    }


}

// $message1 = '<p class="rapport_combat_2">Vous vous êtes fait attaqué par '.$_SESSION['pseudo'].' et vous perdez le combat !</p>
//
// $message1 = '<p class="rapport_combat_1">Vous vous êtes fait attaqué par <a class="link_rc_1" href="profil.php?pseudo='.$_SESSION['pseudo'].'">'.$_SESSION['pseudo'].'</a> !</p>
