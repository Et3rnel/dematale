<div id="menu">
		
<?php
if (isset($_SESSION['pseudo'])) 
{	
	$req1 = $bdd->prepare('SELECT mur,fer,bois,roche,gold,titan,centre_travail FROM production WHERE id=?');
	$req1->execute(array($_SESSION['id']));
	$bat = $req1->fetch();
	
	$req2 = $bdd->prepare('SELECT fer,bois,roche,gold,titan,paysan FROM ressources WHERE id=?');		
	$req2->execute(array($_SESSION['id']));							
	$ressources = $req2->fetch();	
	
	$req3 = $bdd->prepare('SELECT avatar,level,points FROM membres WHERE id=?');		
	$req3->execute(array($_SESSION['id']));							
	$info = $req3->fetch();
	$req3->closeCursor(); //Eviter un bug ?
	
	$req41=$bdd->prepare('SELECT attaque,defense,nombre FROM armee WHERE type=? AND joueur=?');
	$req41->execute(array('recrue',$_SESSION['pseudo']));
	$recrue = $req41->fetch();
	
	$req42=$bdd->prepare('SELECT attaque,defense,nombre FROM armee WHERE type=? AND joueur=?');
	$req42->execute(array('capitaine',$_SESSION['pseudo']));
	$capitaine = $req42->fetch();
	
	$req43=$bdd->prepare('SELECT attaque,defense,nombre FROM armee WHERE type=? AND joueur=?');
	$req43->execute(array('lieutenant',$_SESSION['pseudo']));
	$lieutenant = $req43->fetch();
	
	$req44=$bdd->prepare('SELECT attaque,defense,nombre FROM armee WHERE type=? AND joueur=?');
	$req44->execute(array('commandant',$_SESSION['pseudo']));
	$commandant = $req44->fetch();
	
	$req5=$bdd->prepare('SELECT forge FROM niveau WHERE id=?');
	$req5->execute(array($_SESSION['id']));
	$niveau = $req5->fetch();

	if($niveau['forge']>0){$forge = ($niveau['forge']*3/100)+1;}else{$forge=1;}
	$attaque = ($recrue['nombre']*$recrue['attaque'] +  $capitaine['nombre']*$capitaine['attaque'] +  $lieutenant['nombre']*$lieutenant['attaque'] +  $commandant['nombre']*$commandant['attaque'])*$forge;	 
	$defense = $recrue['nombre']*$recrue['defense'] +  $capitaine['nombre']*$capitaine['defense'] +  $lieutenant['nombre']*$lieutenant['defense'] +  $commandant['nombre']*$commandant['defense'] + $bat['mur'];	
	?> 	

	<div class="bandeau2">
		<a href="/private.php"><?php echo $_SESSION['pseudo']; ?> - <?php echo $info['points']; ?> <img src="/images/points.png" class="img_pts" align="bottom" alt="Points" title="Points" height="19"/></a>
	</div><br/>
		<img class="avat_menu" src="/avatar/<?php echo $info['avatar'];?>" alt="Votre avatar" height="100" width="100"/><br/><br/><br/>

	
	
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
			var iProductionParSecondeFer = <?php echo ($bat['fer']+$bat['centre_travail']); ?>;
			var iRessourcesDepartBois = <?php echo $ressources['bois']; ?>; 
			var iProductionParSecondeBois = <?php echo ($bat['bois']+$bat['centre_travail']); ?>;
			var iRessourcesDepartGold = <?php echo $ressources['gold']; ?>; 
			var iProductionParSecondeGold = <?php echo $bat['gold']; ?>;
			var iRessourcesDepartRoche = <?php echo $ressources['roche']; ?>; 
			var iProductionParSecondeRoche = <?php echo ($bat['roche']+$bat['centre_travail']); ?>;
			if (<?php echo $info['level']; ?> >= 5)
			{
				var iRessourcesDepartTitane = <?php echo $ressources['titan']; ?>; 
				var iProductionParSecondeTitane = <?php echo $bat['titan']; ?>;
			}
			var iSecondes = 0;//c'est pas réellement des secondes, c'est des 0.25 de secondes à cause du timeout de 250ms 			
			function augmentation_ressource()
			{
			   document.getElementById("fer").innerHTML = number_format(iRessourcesDepartFer+((iProductionParSecondeFer/(3600*4))*iSecondes), 0, '.',' '); //avec le timeout on divise par 4 pour avoir la prod des 250 ms
			   document.getElementById("bois").innerHTML = number_format(iRessourcesDepartBois+((iProductionParSecondeBois/(3600*4))*iSecondes), 0, '.',' '); 
			   document.getElementById("gold").innerHTML = number_format(iRessourcesDepartGold+((iProductionParSecondeGold/(3600*4))*iSecondes), 0, '.',' '); 
			   document.getElementById("roche").innerHTML = number_format(iRessourcesDepartRoche+((iProductionParSecondeRoche/(3600*4))*iSecondes), 0, '.',' '); 
				if (<?php echo $info['level']; ?> >= 5)
				{
					document.getElementById("titan").innerHTML = number_format(iRessourcesDepartTitane+((iProductionParSecondeTitane/(3600*4))*iSecondes), 0, '.',' '); 
				}
			   iSecondes++;
			   setTimeout("augmentation_ressource()",250);//250=1000/4 comme ça on affiche un resultat juste
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
			<td class="icon_ress"><img src="/images/paysan.png" title="Paysan" alt="Paysan(s)" height="25" width="25"/></td> 
			<td class="nbr_ress"><em><?php echo number_format($ressources['paysan'], 0, '.', ' '); ?> paysan(s) disponibles(s)</em></td>
		</tr>
	</table>
	
	
	<br/>
	
	<div class="bandeau1">Armée</div>
	
	<table>
		<tr class="tr_ress">
			<td class="icon_ress"><img src="/images/arm_3.png" title="Recrue" alt="Recrue" height="25" width="25"/></td> 
			<td class="nbr_ress"><strong><?php echo number_format($recrue['nombre'], 0, '.', ' '); ?></strong></td>
		</tr>
		<tr class="tr_ress">		
			<td class="icon_ress"><img src="/images/arm_2.png" title="Lieutenant" alt="Lieutenant" height="25" width="25"/></td> 
			<td class="nbr_ress"><strong><?php echo number_format($lieutenant['nombre'], 0, '.', ' '); ?></strong></td>
		</tr>
		<tr class="tr_ress">		
			<td class="icon_ress"><img src="/images/arm_4.png" title="Capitaine" alt="Capitaine" height="25" width="25"/></td> 
			<td class="nbr_ress"><strong><?php echo number_format($capitaine['nombre'], 0, '.', ' '); ?></strong></td>
		</tr>
		<tr class="tr_ress-b">		
			<td class="icon_ress"><img src="/images/arm_1.png" title="Commandant" alt="Commandant" height="25" width="25"/></td> 
			<td class="nbr_ress"><strong><?php echo number_format($commandant['nombre'], 0, '.', ' '); ?></strong></td>
		</tr>
		<tr class="tr_ress-b">		
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
