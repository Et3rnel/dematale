<?php session_start(); 
if(!isset($_SESSION['pseudo'])) {header('Location:../index.php');}
include_once'../actu.php';
include_once'../connectes.php';
//On ne peut pas rejoindre d'alliance si on est deja dans une alliance
$ally = $bdd->prepare('SELECT id_alliance,level FROM membres WHERE id=?');
$ally->execute(array($_SESSION['id']));
$donnees = $ally->fetch();
if ($donnees['id_alliance']!=0){header('Location:../index.php');}
include_once'../actu.php'; ?>


<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
		<link rel="stylesheet" href="../design.css" />
        <title>Dematale, le jeu de strategie en ligne</title>
    </head>
    <body>

<?php include_once'../header.php'; ?>
	
	
	
<div id="g_section">
	<div id="band_l"></div>	<div id="band_r"></div>
	<section>
	
	
	<?php include_once'../menu.php'; ?>
	<div id="corps">					
	<h1>Rejoindre une alliance</h1>
	<div class="corps2">
	
<p class="padd">Pour postuler dans une alliance, cliquez sur son nom et selectionnez "Postuler"</p><br/>
<table class="margin-a">
	<tr>
		<th class="th_rank">Alliance</th>
		<th class="th_rank">Chef</th>
		<th class="th_rank">Membres</th>
	</tr>
<?php

$check_zero = $bdd->query('SELECT COUNT(*) id FROM alliance ORDER BY id DESC');
if($check_zero->fetchColumn() == 0)
{
	?>
		<td class="alliance_td-l"></td> 
		<td class="alliance_td-n">Aucune alliance</td>
		<td class="alliance_td-r"></td>
	<?php
}
else
{
$req = $bdd->query('SELECT id,nom,chef,nbr_membre FROM alliance WHERE nbr_membre<15 ORDER BY nbr_membre DESC');
while($alliance = $req->fetch())
{?>
	<tr>
		<td class="alliance_td"><a href="../profil_alliance.php?nom=<?php echo $alliance['nom']; ?>"><?php echo $alliance['nom'];?></a></td> 
		<td class="alliance_td"><?php echo $alliance['chef'];?></td>
		<td class="alliance_td"><?php echo $alliance['nbr_membre']; ?></td>
	</tr>
<?php } }?>
</table>


<?php if($donnees['level']>=1){echo '<p class="mp_vert">Vous pouvez rejoindre une alliance</p>';}else{echo '<p class="mp_rouge">Vous devez être level 1 ou plus pour rejoindre un alliance</p>';} ?>


	<?php $req_connecte = $bdd->query('SELECT COUNT(*) FROM connectes');
	$connectes = $req_connecte->fetchColumn(); ?>
	
	</div></div>
	<?php include_once'../footer.php'; ?>
	</section>
	
</div>
	
    </body >
</html>