<?php session_start(); 
if(!isset($_SESSION['pseudo'])) header('Location:index.php');
include_once'actu.php';
include_once'connectes.php'; ?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
		<link rel="stylesheet" href="design.css" />
        <title>Classement</title>
    </head>
        <body onload="augmentation_ressource()">
<?php include_once'header.php'; ?>
	
	
	
<div id="g_section">
	<div id="band_l"></div>	<div id="band_r"></div>
	<section>
	
	
	<?php include_once'menu.php'; ?>
	<div id="corps">
	<h1>Classement des joueurs</h1>

	
<div class="rankmenu">
	<div class="switch"><div class="bandeau3">Autres</div><p class="padd"><a href="classement_alliance.php">Alliances</a></p></div>

</div>


<table class="ranktab">
	<tr> 
        <th class="th_rank"><img src="images/test.png" alt="Classement" title="Classement" align="top"/> Rank</th>
		<th class="th_rank">Avat.</th>
		<th class="th_rank">Joueur</th>
		<th class="th_rank">Alliance</th>
		<th class="th_rank"><img src="images/points.png"  align="top" alt="Points" title="Points" /></th>  <!--On choisir align="top" pour centrer les images au mileu du th. Les autres valeurs ne font pas le bon alignement-->
		<th class="th_rank">LvL</th>
   </tr>
   
	
	<?php 
	
		$messagesParPage = 20;
		$req3 = $bdd->query('SELECT COUNT(*) AS nbr_membres FROM membres');
		$donnees = $req3->fetch();
		$nbrPages = ceil($donnees['nbr_membres']/$messagesParPage);
		

		if(isset($_GET['page'])) 
		{
			 $pageActuelle=intval($_GET['page']);
			 if($pageActuelle>$nbrPages){$pageActuelle=$nbrPages;}
			 if($pageActuelle<=0){$pageActuelle=1;}
		}
		else{$pageActuelle=1;}

		$premiereEntree=($pageActuelle-1)*$messagesParPage;


	
		$req1 = $bdd->query('SELECT id,pseudo,points,level,avatar,id_alliance,apply,coupe FROM membres ORDER BY points DESC LIMIT '.$messagesParPage.' OFFSET '.$premiereEntree.'');

		$rank = $premiereEntree+1;
		while($classement = $req1->fetch())
		{
			if($classement['apply']==1)
			{
				$req2=$bdd->prepare('SELECT nom FROM alliance WHERE id=?');
				$req2->execute(array($classement['id_alliance']));
				$ally=$req2->fetch();
			}
			else
			{
				$ally['nom'] = '';
			}
			?>
	<tr>
		<td class="<?php if($_SESSION['pseudo']==$classement['pseudo']){echo 'ranktd-1-bis';}else{echo 'ranktd-1';} ?>"><?php echo $rank; ?></td>
		<td class="ranktd-4"><img src="avatar/<?php echo $classement['avatar'];?>" alt="Votre avatar" height="35" width="35"/></td>
		<td class="<?php if($_SESSION['pseudo']==$classement['pseudo']){echo 'ranktd-2-bis';}else{echo 'ranktd-2';} ?>"><a href="profil.php?pseudo=<?php echo $classement['pseudo']; ?>"><?php echo htmlspecialchars($classement['pseudo']); if($classement['coupe']!=0){echo ' <img src="images/coupes/'.$classement['coupe'].'.png" alt="Coupe" height="15" width="15" align="top"/>';} ?></a></td>
		<td class="<?php if($_SESSION['pseudo']==$classement['pseudo']){echo 'ranktd-5-bis';}else{echo 'ranktd-5';} ?>"><a href="profil_alliance.php?nom=<?php echo $ally['nom']; ?>"><?php echo $ally['nom']; ?><a/></td>
		<td class="<?php if($_SESSION['pseudo']==$classement['pseudo']){echo 'ranktd-3-bis';}else{echo 'ranktd-3';} ?>"><?php echo htmlspecialchars($classement['points']); ?></td>
		<td class="<?php if($_SESSION['pseudo']==$classement['pseudo']){echo 'ranktd-6-bis';}else{echo 'ranktd-6';} ?>"><?php echo htmlspecialchars($classement['level']); ?></td>
	</tr>

		<?php $rank++;}
		$req1->closeCursor(); ?>
</table><br/>


<div class="pagination1">
<?php
for($i=1; $i<=$nbrPages; $i++) 
{
 
     if($i==$pageActuelle) 
     {
         echo '<strong class="color_blue">[-'.$i.'-]</strong>'; 
     }	
     else 
     {
          echo ' <a href="classement.php?page='.$i.'">' .$i. '</a> ';
     }
}
?>
</div>


<br/><br/>
	<?php $req_connecte = $bdd->query('SELECT COUNT(*) FROM connectes');
	$connectes = $req_connecte->fetchColumn(); ?>
	
	</div>
	<?php include_once'footer.php'; ?>
	</section>
	
	</div>
    </body>
</html>