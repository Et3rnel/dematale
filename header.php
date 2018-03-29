<header>
	<div>
		<a href="http://www.facebook.com/Geasscraft"><img src="/Dematale/images/fb_icon.png" alt="Like us !"/></a><br/>
	</div>

	<div class="google"><div class="g-plusone" data-annotation="none"></div></div>
	<script type="text/javascript">
	window.___gcfg = {lang: 'fr'};

	(function() {
		var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
		po.src = 'https://apis.google.com/js/plusone.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
	})();
	</script>

	<div id="head_menu">



<table class="margin-a">
<tr>
	<td class="tdhead"><a href="/Dematale/index.php"> <img class="img_faq" src="/Dematale/images/home.png" alt="Bouton home"/> Home</a></td>
	<?php if(!isset($_SESSION['id']))
			{ ?>
	<td class="tdhead"><a href="/Dematale/connexion.php">Se connecter</a></td>
			<?php }
	if(isset($_SESSION['id']))
	{
		require_once'cnx.php';
		$req1 = $bdd->prepare('SELECT level,avatar,points,coupe,notif_chat,notif_chat_alliance FROM membres WHERE id=?');
		$req1->execute(array($_SESSION['id']));
		$membres = $req1->fetch();
		$niveau_du_membre = $membres['level'];

		$nbr_mp = $bdd->prepare('SELECT COUNT(*) FROM mess_priv WHERE recepteur=? AND statut=0');
		$nbr_mp->execute(array($_SESSION['pseudo']));
		$nbr_lignes = $nbr_mp->fetchColumn(); ?>


	<td class="tdhead"><a href="/Dematale/mess_priv.php">Messages</a> <span class="nbr_msg"> <?php if($nbr_lignes>0) echo $nbr_lignes; ?></span></td>

	<?php }

	if(isset($_SESSION['pseudo']))
	{
		$req_clas = $bdd->prepare('SELECT COUNT(*) AS classement FROM membres WHERE points>?');
		$req_clas->execute(array($membres['points']));
		$define = $req_clas->fetch();

		$page = ceil(($define['classement']+1)/20);

		echo '<td class="tdhead"><a href="/Dematale/classement.php?page='.$page.'">Classement</a></td>';
	}	?>

	<?php if(isset($_SESSION['id']))
	{ ?>

	<td class="tdhead"><img src="/Dematale/images/ally.png" alt="Alliance" class="img_coffre"/><a href="/Dematale/alliance.php"> <?php if($membres['notif_chat_alliance']!=0){echo' Alliance <span class="nbr_msg">'.$membres['notif_chat_alliance'].'</span>';}else{echo' Alliance';}?></a></td>
			<?php }; ?>
			<?php if(isset($_SESSION['id']) && $niveau_du_membre>1) { ?> <td class="tdhead"><img src="/Dematale/images/icon_commerce.png" class="img_pts" alt="Icon du commerce"/><a href="/Dematale/commerce.php"> Commerce</a></td> <?php } ?>
	<td class="tdhead"><a href="/Dematale/private.php">Profil</a></td>
	<td class="tdhead"><a href="/Dematale/forum.php">Forum</a></td>
	<td class="tdhead"><a href="/Dematale/chat.php">Chat <?php if(isset($_SESSION['id']) && $membres['notif_chat']!=0) echo '<span class="nbr_msg">'.$membres['notif_chat'].'</span>'; ?></a> </td>


			<?php if(isset($_SESSION['id']))
			{ ?>
	<td class="tdhead"><a href="/Dematale/deconnexion.php">Se déconnecter</a></td>
			<?php }; ?>
	<td class="tdhead"><a href="/Dematale/faq.php">F.A.Q <img class="img_faq" src="/Dematale/images/p_intero.png" alt="Icon F.A.Q"/></a></td>
</tr>
</table>



	</div>
</header>
