<?php session_start();
include_once'cnx.php';

$req1 = $bdd->prepare('SELECT id_alliance FROM membres WHERE id=?');
$req1->execute(array($_SESSION['id']));
$info = $req1->fetch();
	
$req2 = $bdd->prepare('SELECT chef FROM alliance WHERE id=?');
$req2->execute(array($info['id_alliance']));
$alliance = $req2->fetch();
	
if(isset($_POST['raz_compte']))
{
	if($info['id_alliance']!=0)
	{
		if($alliance['chef'] == $_SESSION['pseudo'])
		{
			header('Location:private.php?msg=chef');
		}
		$req968 = $bdd->prepare('UPDATE alliance SET nbr_membre=nbr_membre-1 WHERE id=?');
		$req968->execute(array($info['id_alliance']));
	}
	

	$req3 = $bdd->prepare('UPDATE armee SET nombre=0 WHERE joueur=? AND type=?');
	$req3->execute(array($_SESSION['pseudo'],'recrue'));
			
	$req4 = $bdd->prepare('UPDATE armee SET nombre=0 WHERE joueur=? AND type=?');
	$req4->execute(array($_SESSION['pseudo'],'lieutenant'));
			
	$req5 = $bdd->prepare('UPDATE armee SET nombre=0 WHERE joueur=? AND type=?');
	$req5->execute(array($_SESSION['pseudo'],'capitaine'));
			
	$req6 = $bdd->prepare('UPDATE armee SET nombre=0 WHERE joueur=? AND type=?');
	$req6->execute(array($_SESSION['pseudo'],'commandant'));
			
	$req7 = $bdd->prepare('UPDATE cout SET fer_bois=DEFAULT,fer_roche=DEFAULT,bois_fer=DEFAULT,bois_roche=DEFAULT,roche_fer=DEFAULT,roche_bois=DEFAULT,gold_fer=DEFAULT,gold_bois=DEFAULT,gold_roche=DEFAULT,titan_bois=DEFAULT,titan_fer=DEFAULT,espio=DEFAULT,vitesse=DEFAULT,roche_mur=DEFAULT,centre_travail=DEFAULT,grenier=DEFAULT,hv_fer=DEFAULT,hv_roche=DEFAULT,co_fer=DEFAULT,co_gold=DEFAULT,forge=DEFAULT WHERE id=?');
	$req7->execute(array($_SESSION['id']));
			
	$req8 = $bdd->prepare('DELETE FROM commerce WHERE vendeur=?');
	$req8->execute(array($_SESSION['id']));
		
	$req9 = $bdd->prepare('UPDATE membres SET points=0,level=0,coupe=0,decret=0,id_alliance=0,apply=0 WHERE id=?');
	$req9->execute(array($_SESSION['id']));
			
	$req91 = $bdd->prepare('UPDATE timer SET decret=0 WHERE id=?');
	$req91->execute(array($_SESSION['id']));
			
	$req10 = $bdd->prepare('DELETE FROM mess_priv WHERE recepteur=?');
	$req10->execute(array($_SESSION['pseudo']));
		
	$req11 = $bdd->prepare('UPDATE niveau SET fer=DEFAULT,bois=DEFAULT,roche=DEFAULT,gold=DEFAULT,titan=DEFAULT,mur=DEFAULT,espionnage=DEFAULT,vitesse=DEFAULT,grenier=DEFAULT,commerce=DEFAULT,hotel_ventes=DEFAULT,forge=DEFAULT WHERE id=?');
	$req11->execute(array($_SESSION['id']));
		
	$req12 = $bdd->prepare('UPDATE production SET fer=DEFAULT,bois=DEFAULT,roche=DEFAULT,gold=DEFAULT,titan=DEFAULT,mur=DEFAULT,vitesse=DEFAULT,grenier=DEFAULT,centre_travail=DEFAULT,hotel_ventes=DEFAULT WHERE id=?');
	$req12->execute(array($_SESSION['id']));
		
	$temps = time();
	$req13 = $bdd->prepare('UPDATE ressources SET fer=DEFAULT,bois=DEFAULT,roche=DEFAULT,gold=DEFAULT,titan=DEFAULT,paysan=0,temps_ressources=?,temps_attaque=0 WHERE id=?');
	$req13->execute(array($temps,$_SESSION['id']));
			
	header('Location:private.php?msg=succes_raz');
			
}
elseif(isset($_POST['del_compte']))
{
	if($info['id_alliance']!=0)
	{
		if($alliance['chef']==$_SESSION['pseudo'])
		{
			$req123 = $bdd->prepare('SELECT MAX(points) FROM membres WHERE id_alliance=? AND apply=1');
			$req123->execute(array($info['id_alliance']));
			$id = $req123->fetch();		
			
			$req122 = $bdd->prepare('SELECT pseudo FROM membres WHERE id_alliance=? AND points=? AND apply=1');
			$req122->execute(array($info['id_alliance'],$id[0]));
			$newchef = $req122->fetch();
			
			$req122 = $bdd->prepare('UPDATE alliance SET chef=?,nbr_membre=nbr_membre-1 WHERE id=? ');
			$req122->execute(array($newchef['pseudo'],$info['id_alliance']));
			
		}
	}
	$req3 = $bdd->prepare('DELETE FROM armee WHERE joueur=?');
	$req3->execute(array($_SESSION['pseudo']));
	
	$req7 = $bdd->prepare('DELETE FROM cout WHERE id=?');
	$req7->execute(array($_SESSION['id']));
			
	$req8 = $bdd->prepare('DELETE FROM commerce WHERE vendeur=?');
	$req8->execute(array($_SESSION['id']));
			
	$req9 = $bdd->prepare('DELETE FROM membres WHERE id=?');
	$req9->execute(array($_SESSION['id']));
					
	$req10 = $bdd->prepare('DELETE FROM mess_priv WHERE recepteur=?');
	$req10->execute(array($_SESSION['pseudo']));			
			
	$req91 = $bdd->prepare('DELETE FROM timer WHERE id=?');
	$req91->execute(array($_SESSION['id']));
			
	$req11 = $bdd->prepare('DELETE FROM niveau WHERE id=?');
	$req11->execute(array($_SESSION['id']));
		
	$req12 = $bdd->prepare('DELETE FROM production WHERE id=?');
	$req12->execute(array($_SESSION['id']));
		
	$req13 = $bdd->prepare('DELETE FROM ressources WHERE id=?');
	$req13->execute(array($_SESSION['id']));
	
	session_destroy();	
	
	header('Location:index.php');
}
else
{
	header('Location:../private.php');
}