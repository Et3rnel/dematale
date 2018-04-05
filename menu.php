<div id="menu">

<?php
if (isset($_SESSION['pseudo']))
{
	$req1 = $bdd->prepare('SELECT mur,vitesse,fer,bois,roche,gold,titan FROM production WHERE id=?');
	$req1->execute(array($_SESSION['id']));
	$bat = $req1->fetch();

	$req2 = $bdd->prepare('SELECT fer,bois,roche,gold,titan,paysan,guerrisseur,temps_attaque FROM ressources WHERE id=?');
	$req2->execute(array($_SESSION['id']));
	$ressources = $req2->fetch();

	$reqUnits = $bdd->prepare('SELECT pa.id_unit, pa.unit_amount, ui.attack, ui.defense, ui.unit_name FROM player_army pa
        INNER JOIN units_informations ui ON pa.id_unit = ui.id_unit
        WHERE id_player = ?');
	$reqUnits->execute(array($_SESSION['id']));
	$units = $reqUnits->fetchAll();


	$req5=$bdd->prepare('SELECT forge FROM niveau WHERE id=?');
	$req5->execute(array($_SESSION['id']));
	$niveau = $req5->fetch();

	$req666 = $bdd->prepare('SELECT decret FROM membres WHERE id=?');
	$req666->execute(array($_SESSION['id']));
	$membre = $req666->fetch();

    if($niveau['forge']>0){$forge = ($niveau['forge']*3/100)+1;}else{$forge=1;}

    $attaque = $defense = 0;
    foreach ($units as $key => $unit) {
        $attaque += $unit['attack'] * $unit['unit_amount'];
        $defense += $unit['defense'] * $unit['unit_amount'];
    }

	$attaque *= $forge;
	$defense += $bat['mur'];

    ?>

	<div class="bandeau2">
		<a href="/private.php"><?php if($membres['coupe']!=0){echo '<img src="images/coupes/'.$membres['coupe'].'.png" class="img_pts" alt="Coupe" height="20" width="20"/> ';} echo $_SESSION['pseudo']; ?> - <?php echo $membres['points']; ?> <img src="/images/points.png" class="img_pts" align="bottom" alt="Points" title="Points" height="19"/></a>
	</div><br/>
		<img class="avat_menu" src="/avatar/<?php echo $membres['avatar'];?>" alt="Votre avatar" height="100" width="100"/><br/><br/><br/>



	<div class="bandeau1">Développement</div>
	<table>
		<tr class="tr_ress"><td class="nbr_ress2"><a href="/batiments.php">Bâtiments</a></td></tr>
		<tr class="tr_ress"><td class="nbr_ress2"><a href="/technologies.php">Technologies</a></td></tr>
		<tr class="tr_ress-b"><td class="nbr_ress2"><a href="/armee.php">Armée</a></td></tr>
	</table><br/>

	<div class="bandeau1">Ressources</div>

		<script>
				function number_format (number, decimals, dec_point, thousands_sep) {
					  number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
					  var n = !isFinite(+number) ? 0 : +number,
						prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
						sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
						dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
						s = '',
						toFixedFix = function (n, prec) {
						  var k = Math.pow(10, prec);
						  return '' + Math.round(n * k) / k;
						};
					  // Fix for IE parseFloat(0.55).toFixed(0) = 0;
					  s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
					  if (s[0].length > 3) {
						s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
					  }
					  if ((s[1] || '').length < prec) {
						s[1] = s[1] || '';
						s[1] += new Array(prec - s[1].length + 1).join('0');
					  }
					  return s.join(dec);
					}
			var iRessourcesDepartFer = <?php echo $ressources['fer']; ?>;
			var iProductionParSecondeFer = <?php echo $bat['fer']; ?>;
			var iRessourcesDepartBois = <?php echo $ressources['bois']; ?>;
			var iProductionParSecondeBois = <?php echo $bat['bois']; ?>;
			var iRessourcesDepartGold = <?php echo $ressources['gold']; ?>;
			var iProductionParSecondeGold = <?php echo $bat['gold']; ?>;
			var iRessourcesDepartRoche = <?php echo $ressources['roche']; ?>;
			var iProductionParSecondeRoche = <?php echo $bat['roche']; ?>;
			if (<?php echo $info['level']; ?> >= 5)
			{
				var iRessourcesDepartTitane = <?php echo $ressources['titan']; ?>;
				var iProductionParSecondeTitane = <?php echo $bat['titan']; ?>;
			}
			var iSecondes = 0;
			function augmentation_ressource()
			{
				var fer = (iRessourcesDepartFer+((iProductionParSecondeFer/3600)*iSecondes)/2.5);
				var bois = (iRessourcesDepartBois+((iProductionParSecondeBois/3600)*iSecondes)/2.5);
				var gold = (iRessourcesDepartGold+((iProductionParSecondeGold/3600)*iSecondes)/2.5);
				var roche = (iRessourcesDepartRoche+((iProductionParSecondeRoche/3600)*iSecondes)/2.5);
				if (<?php echo $membre['decret']?> == 2)
				{
					fer = fer*1.02;
					bois = bois*1.02;
					roche = roche*1.02;
				}
				if (<?php echo $membre['decret']?> == 1)
				{
					gold = gold*1.05;
				}

			   document.getElementById("fer").innerHTML = number_format(fer, 0, '.',' ');
			   document.getElementById("bois").innerHTML = number_format(bois, 0, '.',' ');
			   document.getElementById("gold").innerHTML = number_format(gold, 0, '.',' ');
			   document.getElementById("roche").innerHTML = number_format(roche, 0, '.',' ');

			   if (<?php echo $info['level']; ?> >= 5)
				{
					document.getElementById("titan").innerHTML = number_format((iRessourcesDepartTitane+((iProductionParSecondeTitane/3600)*iSecondes)/2.5), 0, '.',' ');
				}
			   iSecondes++;
			   setTimeout("augmentation_ressource()",400);
			}
		</script>

	<table>
		<tr class="tr_ress">
			<td class="icon_ress"><img src="/images/fer_icon.png" title="Fer" alt="Icon du fer"/></td>
			<td class="nbr_ress"><em id="fer"><?php echo number_format($ressources['fer'], 0, '.', ' '); ?></em></td>
		</tr>
		<tr class="tr_ress">
			<td class="icon_ress"><img src="/images/bois_icon.png" title="Bois" alt="Icon du bois"/></td>
			<td class="nbr_ress"><em id="bois"><?php echo number_format($ressources['bois'], 0, '.', ' '); ?></em></td>
		</tr>
		<tr class="tr_ress">
			<td class="icon_ress"><img src="/images/roche_icon.png" title="Roche" alt="Icon de la roche"/></td>
			<td class="nbr_ress"><em id="roche"><?php echo number_format($ressources['roche'], 0, '.', ' '); ?></em></td>
		</tr>
		<tr class="tr_ress-b">
			<td class="icon_ress"><img src="/images/gold_icon.png" title="Or" alt="Icon de l'or"/></td>
			<td class="nbr_ress"><em id="gold"><?php echo number_format($ressources['gold'], 0, '.', ' '); ?></em></td>
		</tr>
		<?php if($info['level'] >= 5)
		{
			?>
			<tr class="tr_ress-b">
				<td class="icon_ress"><img src="/images/titane_icon.png" title="Titan" alt="Icon du titan"/></td>
				<td class="nbr_ress"><em id="titan"><?php echo number_format($ressources['titan'], 0, '.', ' '); ?></em></td>
			</tr>
			<?php
		} ?>
		<tr class="tr_ress-b">
			<td class="icon_ress"><img src="/images/paysan.png" title="Paysan" alt="Paysan(s)" height="25"/></td>
			<td class="nbr_ress"><em><?php echo number_format($ressources['paysan'], 0, '.', ' '); ?> paysan(s) disponibles(s)</em></td>
		</tr>
	</table>


	<br/>

	<div class="bandeau1">Armée</div>

	<table>
        <?php
        foreach ($units as $key => $unit) {
            ?>
                <tr class="tr_ress">
                    <td class="icon_ress"><img src="/images/army_units/arm_<?= $unit['id_unit']; ?>.png" title="<?= $unit['unit_name']; ?>" alt="<?= $unit['unit_name']; ?>" height="25" width="25"/></td>
        			<td class="nbr_ress"><?php if($unit['unit_amount'] === 0){echo 'Aucun';}else{echo number_format($unit['unit_amount'], 0, '.', ' ');} ?></td>
                </tr>
            <?php
        }
        ?>

		<tr class="tr_ress">
			<td class="icon_ress">Attaque</td>
			<td class="nbr_ress"><em><?php echo number_format($attaque, 0, '.', ' '); ?></em></td>
		</tr>
		<tr class="tr_ress-b">
			<td class="icon_ress">Défense</td>
			<td class="nbr_ress"><em><?php echo number_format($defense, 0, '.', ' '); ?></em></td>
		</tr>
	</table>

	<br/>


	<?php
	$req666->closeCursor();
	$req1->closeCursor();
	$req2->closeCursor();
	$req5->closeCursor();


	$temps = time();
	$difference = $temps - $ressources['temps_attaque'];
	$attente = floor((($bat['vitesse']/60)+0.8)-($difference/60)); ?>


	<div class="bandeau1">Taches</div>
		<table>
		<tr class="tr_ress-b">
			<td class="icon_ress">Attaquer</td>
			<td class="nbr_ress"><?php if($attente >= 0){echo 'Dans '.$attente.' minutes';}else{echo 'Vous pouvez attaquer';} ?></td>
		</tr>
	</table><br/><br/>


<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- dematale1 -->
<ins class="adsbygoogle"
style="display:inline-block;width:120px;height:240px"
data-ad-client="ca-pub-7724414667437745"
data-ad-slot="6119400513"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>


	<?php


}
else
{
	?>
	<div class="bandeau">
		<p class="no_margin">Rejoignez-nous !</p>
	</div><br/>

	<p class="padd">Inscrivez-vous pour pouvoir jouer, ça ne vous prendra que quelques secondes.</p>
	<p class="padd">Partez à la découverte de la phase terrestre !<br/><img src="/images/terre.png" alt="Terre"/><br/>
	Puis de la phase spatiale !<br/><img src="/images/galaxy.png" alt="Galaxie"/></p>
	<p class="padd">Inscrivez-vous !</p>
	<a href="/register.php"><img src="/images/inscription.png" alt="Inscription"/></a><br/><br/>

	<?php
}
	?>

	<br/>


	</div>
