<?php session_start(); 
if(!isset($_SESSION['pseudo'])) header('Location:index.php');
include_once'actu.php';
include_once'connectes.php'; ?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
		<link rel="stylesheet" href="design.css" />
        <title>Classement des alliances</title>
    </head>
        <body onload="augmentation_ressource()">
<?php include_once'header.php'; ?>
	
	
	
<div id="g_section">
	<div id="band_l"></div>	<div id="band_r"></div>
	<section>
	
	
	<?php include_once'menu.php'; ?>
	<div id="corps">
	<h1>Classement des alliances</h1>

	
<div class="rankmenu">
	<div class="switch"><div class="bandeau3">Autres</div><p class="padd"><a href="classement.php?page=<?php echo $page; ?>">Joueurs</a></p></div>

</div>


<table class="ranktab">
	<tr> 
        <th class="th_rank"><img src="images/test.png" alt="Classement" title="Classement" align="top"/> Rank</th>
		<th class="th_rank">Embl.</th>
		<th class="th_rank">Alliance</th>
		<th class="th_rank">Membres</th>
		<th class="th_rank">Chef</th>
		<th class="th_rank"><img src="images/points.png"  align="top" alt="Points" title="Points" /></th>  <!--On choisir align="top" pour centrer les images au mileu du th. Les autres valeurs ne font pas le bon alignement-->
   </tr>
   
	
	<?php 
	
		$messagesParPage = 20;
		$req3 = $bdd->query('SELECT COUNT(*) AS nbr_alliances FROM alliance');
		$donnees = $req3->fetch();
		$nbrPages = ceil($donnees['nbr_alliances']/$messagesParPage);
		

		if(isset($_GET['page'])) 
		{
			 $pageActuelle=intval($_GET['page']);
			 if($pageActuelle>$nbrPages){$pageActuelle=$nbrPages;}
		}
		else{$pageActuelle=1;}

		$premiereEntree=($pageActuelle-1)*$messagesParPage;


	
		$req1 = $bdd->query('SELECT id,nom,chef,nbr_membre,points FROM alliance ORDER BY points DESC LIMIT '.$messagesParPage.' OFFSET '.$premiereEntree.'');

		$rank = $premiereEntree+1;
		while($classement = $req1->fetch())
		{
			?>
	<tr>
		<td class="ranktd-1"><?php echo $rank; ?></td>
		<td class="ranktd-4"></td>
		<td class="ranktd-2"><?php echo $classement['nom']; ?></td>
		<td class="ranktd-1"><?php echo $classement['nbr_membre'];; ?><a/></td>
		<td class="ranktd-2"><?php echo $classement['chef']; ?></td>
		<td class="ranktd-2"><?php echo $classement['points']; ?></td>		
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
          echo ' <a href="classement_alliance.php?page='.$i.'">' .$i. '</a> ';
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