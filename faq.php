<?php session_start();
include_once'actu.php'; 
include_once'connectes.php'; ?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
		<link rel="stylesheet" href="design.css" />
        <title>Foire aux questions</title>
    </head>
        <body onload="augmentation_ressource()">
<?php include_once'header.php'; ?>
	
	
	
<div id="g_section">
	<div id="band_l"></div>	<div id="band_r"></div>
	<section>
	
	
	<?php include_once'menu.php'; ?>
	<div id="corps">
	<h1>Foire aux questions</h1>

	<h3>1. Les ressources</h3>
			<p class="padd">Les ressources sont la partie la plus importante sur Dematale. Elles permettent d'avancer dans le jeu.
		<h2>Les matériaux</h2>
			<p class="padd">Les principaux matériaux sont le <strong>fer</strong> <img src="images/fer_icon.png" alt="Icon du fer" align="top"/>, le <strong>bois</strong> <img src="images/bois_icon.png" alt="Icon du bois" align="top"/> et la 
			<strong>roche</strong> <img src="images/roche_icon.png" alt="Icon roche" align="top"/>. Ils vous servent à augmenter le niveau de vos batiments. 
			Le quatrième matériau principal du jeu est l'<strong>or</strong> <img src="images/gold_icon.png" alt="Icon de l'or" align="top"/>. 
			Il est surtout utilisé pour acheter des <a href="index.html#les unites">troupes</a> ou monter le niveau de ses technologies.
			Au fil du jeu, vous pourrez débloquer de nouveaux matériaux. A vous de les découvrir !</p>
		<h2>Les paysans</h2>
			<p class="padd">	Les <strong>paysans</strong> <img src="images/paysan.png" alt="Paysan" align="top" height="20" width="20"/> permettent par exemple d'augmenter 
			la technologie Centre du travail ou de participer au bon développement de l'alliance.
			Pour obtenir des paysans, il vous faut attaquer un joueur et lui infliger des pertes. En voyant ça, des paysans viendront se 
			joindre à vous. Vous gagnez aussi des paysans tous les jours si vous appartenez à une <strong>Alliance</strong>.</p>

	<h3>2. L'art du combat</h3>
			<p class="padd">Se préparer pour le combat est une chose qu'il ne faut pas prendre à la légère. Les combats permettent 
			d'accroître sa puissance plus rapidement ou simplement de se défendre contre les assayants.</p>
		<h2 id="les unites">Les unités</h2>
			<p class="padd">Chaque unité possède 3 caractéristiques :</p>
   <ul>
   <li> <strong>L'attaque :</strong> lorsque vous attaquerez un autre joueur, <strong>l'ensemble des points d'attaque</strong> de vos unités sera comparé à <strong>l'ensemble des points de défense des unités de votre adversaire, plus celle de son mur.</strong></li><br/>
    <li> <strong>La défense :</strong> lorsqu'un autre joueur vous attaquera, l'ensemble des défenses de vos unités (plus celle de votre mur) sera opposé à l'ensemble des attaques des unités de votre adversaire. </li><br/>
    <li> <strong>Les ressources :</strong> si vous remportez un combat, vous pourrez alors piller les ressources de votre adversaire. Une unité ayant comme caractéristique 5 ressources pourra piller 5 roche, 5 bois, 5 fer et 5 or. </li><br/>
 </ul>
<p class="padd">Il existe 4 types d'unités :</p>
<ul> 
	<li> <strong>Les recrues :</strong> avantageux pour le pillage de ressources, mais mauvais pour la défense et l'attaque. </li> 
	<li> <strong>Les lieutenants :</strong> avantageux pour la défense, moyens pour le pillage et mauvais pour l'attaque. </li> 
	<li> <strong>Les capitaines :</strong> avantageux pour l'attaque, moyens pour le pillage et mauvais pour la défense. </li> 
	<li> <strong>Les commandants :</strong> allient une bonne défense et une bonne attaque, mais très mauvais pour le pillage. </li> 
</ul>
   
    
  

 
<p class="padd">Vous pourrez en débloquer en progressant dans le jeu.
Vous pouvez accéder à votre armée en cliquant sur "Armée" dans le menu de gauche.</p>
		<h2>Choisir sa cible</h2>
			<p class="padd">En vous rendant sur le <strong>classement</strong> (dans la barre de menu du haut), vous pourrez cliquer sur le pseudo d'un joueur ce qui aura pour effet d'ouvrir son profil.
Sur le profil de chaque joueur se trouve trois boutons : <em>envoyer un message, attaquer et espionner.</em><br/>
Il est préférable d'espionner sa cible avant de l'attaquer, mais rien ne vous y oblige.<br/>
Une fois que vous êtes sûr de vous, il ne vous reste plus qu'à appuyer sur le bouton attaquer.<br/>
 
<strong>Attention</strong> : vous ne pouvez pas attaquer une cible qui a plus de 100 points d'écart (en plus ou en moins) avec vous.</p>

		<h2>Déroulement des combats</h2>
			<p class="padd">Pour l'attaquant, l'ensemble des attaques de ses unités sont additionnées.
Pour le défenseur, l'ensemble des défenses de ses unités sont additionnées avec la défense apportée par son mur.
On compare ensuite le total de chacun et celui qui a le plus fort l'emporte.</p>
 
<p class="padd">Quelque soit le résultat du combat, il y aura dans la majorité des cas des pertes des deux côtés.
Le calcul des pertes se fait suivant un algorithme et une touche d'aléatoire.</p>
 
<p class="padd">Si l'attaquant l'emporte, il peut piller le royaume ennemi (en fonction de la caractéristique ressources de l'ensemble de ses unités) et il gagne 3 points. Le défenseur quant à lui perd <strong>2 points</strong>.
Si c'est le défenseur qui l'emporte, l'attaquant repart chez lui bredouille et humilié et le défenseur gagne ? point.</p>
		<h2>Les coupes</h2>
			<p class="padd">On peut distinguer quatre coupes différentes : la <strong>bronze</strong>, l'<strong>argent</strong>, l'<strong>or</strong> et celle en <strong>diamant</strong>. Elles sont redistribuées 
			chaque jours aléatoirement à certain joueurs à l'exeption de celle en diamant qui a une chance sur 3 d'apparaître à chaque <strong>Récapitulatif du jour</strong>.<br/></p>
			<p class="padd">
			Tout au long de la journée, les joueurs peuvent si la cible est accessible l'attaquer pour en plus espérer lui voler sa coupe s'il sort victorieux du combat.</p>
			<p class="padd"><u>Voici les bonus qu'elles apportent :</u></p>
			<p class="padd">
			- Bronze <img src="images/coupes/1.png" alt="Bronze" align="top" height="15" width="15"/> : Réduit le coût des technologies de <strong>5%</strong>. Le prix du centre du travail est réduit <strong>d'un</strong> paysan.<br/>
			- Argent <img src="images/coupes/2.png" alt="Argent" align="top" height="15" width="15"/> : Réduit le coût des unités de <strong>10%</strong>, sauf les guerrisseurs qui voient leur prix baisser de 5 paysans.<br/>
			- Or <img src="images/coupes/3.png" alt="Or" align="top" height="15" width="15"/> : Réduit le coût des bâtiments de <strong>15%</strong>.<br/>
			- Diamant <img src="images/coupes/4.png" alt="Diamant" align="top" height="15" width="15"/> : Regroupe les 3 bonus précédents !</p>
	<h3>3. Système de points</h3>
			<p class="padd">Les points ou <img class="img_pts" src="images/points.png" alt="Points"/> donnent une idée sur la puissance d'un joueur.</p>
		<h2>Gagner des points</h2>
			<p class="padd">On gagne <strong>3</strong> points lorsque l'on remporte un combat en tant qu'attaquant.
			On gagne <strong>1</strong> point lorsque l'on remporte un combat en tant que défenseur.
			On gagne aussi des point par bâtiment ou technologie amélioré, en fonction du niveau du bâtiment.</p>
		<h2>Perte de points</h2>
			<p class="padd">On perd des points lorsque l'on perd un combat en tant que défenseur.</p>
							
						<h3>4. Niveau du joueur</h3>
						<p class="padd">
							Chaque nouvel arrivant dans Dematale commence au niveau zéro. C'est en gagnant des points que le joueur gagne 
							des niveaux. À chaque niveau, le joueur débloquera de nouvelles fonctionnalités dans le jeux.
						</p>
						
		<h3>5. Système d'alliance</h3>
			
			<h2>Généralité</h2>
<p class="padd">Pour rejoindre une alliance, il faut être <strong>niveau 1.</strong><br/>
Pour créer une <strong><em>alliance</em></strong>, il faut être <strong>niveau 2</strong> et il faut également payer  <strong>10 000</strong> de fer, <strong>10.000</strong> bois, <strong>10.000</strong> roche et <strong>20.000</strong> or.<br/>
Les alliances sont classées en fonction du nombre de points (le classement alliance est accessible en vous rendant sur la page classement joueur et en cliquant sur alliance dans le cadre à gauche du classement joueur).</p>
			<h2>Avantages d'une alliance</h2>
<p class="padd">Lorsque l'on appartient à une alliance, on gagne un certain nombre de paysans chaque jour (le nombre de membres dans votre alliance + d'éventuels bonus).<br/>
Les joueurs les plus forts peuvent donner de l'or aux joueurs les plus faibles par l'intermédiaire du <strong>coffre-fort d'alliance</strong>.</p>
			<h2>Le fonctionnement des alliances</h2>

			<p class="padd">Il existe deux types d'alliance :</p>
				<ol>
					<li> <strong>Économique</strong> : Production de paysans doublée.</li><br/>
					<li> <strong>Militaire</strong> : Coûts des unités de l'alliance réduit.</li> 
				</ol> 
    
 
<p class="padd">Chaque alliance a un <em>coffre-fort</em> dans lequel tout membre de l'alliance peut déposer de l'or 
(cet or pourra ensuite être redistribué aux différents membres de l'alliance et/ou utilisé pour augmenter les bâtiments d'alliance).</p>
 

						
		<h3>6. Le commerce</h3>
			<p class="padd">Le commerce ne peut se débloquer qu'à partir du <strong>niveau 2</strong>, il vous faudra alors augmenter la technologie <em><strong>commerce</strong></em>.
Une fois cela fait, vous pourrez échanger un certain type de ressource contre d'autres types de ressource avec les autres joueurs.<br/>
C'est le niveau de votre technologie <em>commerce</em> qui détermine combien vous pouvez proposer d'échange à la fois (au niveau 1 vous ne pourrez donc proposer qu'un seul échange à la fois).</p><br/>
						
				
	
	<?php $req_connecte = $bdd->query('SELECT COUNT(*) FROM connectes');
	$connectes = $req_connecte->fetchColumn(); ?>
	
	</div>
	<?php include_once'footer.php'; ?>
	</section>
	
	</div>
    </body>
</html>