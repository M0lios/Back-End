<div class="listing-article">

<h1>Mes commandes</h1>
<?php if(isset($_SESSION['user_id']) == true && $_SESSION['user_id'] != "") { ?>

<nav class="nav nav-pills flex-column flex-sm-row new-nav">
  <?php if(!isset($_GET['option'])): ?>
	<button class="flex-sm-fill text-sm-center active">Toutes mes commandes</button>
  <?php else: ?>
	<a class="flex-sm-fill text-sm-center nav-link" href="<?php echo $page; ?>.php">Toutes mes commandes</a>
  <?php endif; ?>
  <?php if(isset($_GET['option']) && $_GET['option'] == "en_cours"): ?>
	<button class="flex-sm-fill text-sm-center active">En cours</button>
  <?php else: ?>
	<a class="flex-sm-fill text-sm-center nav-link" href="<?php echo $page; ?>.php?option=en_cours">En cours</a>
  <?php endif; ?>
  <?php if(isset($_GET['option']) && $_GET['option'] == "termine"): ?>
	<button class="flex-sm-fill text-sm-center active">Terminé</button>
  <?php else: ?>
	<a class="flex-sm-fill text-sm-center nav-link" href="<?php echo $page; ?>.php?option=termine">Terminé</a>
  <?php endif; ?>
  <?php if(isset($_GET['option']) && $_GET['option'] == "retour"): ?>
	<button class="flex-sm-fill text-sm-center active">Retourné</button>
  <?php else: ?>
	<a class="flex-sm-fill text-sm-center nav-link" href="<?php echo $page; ?>.php?option=retour">Retourné</a>
  <?php endif; ?>
  
</nav>
<br>
<?php
require 'includes/class/produit.php';
require 'includes/class/categorie.php';
require 'includes/class/fournisseur.php';

$user_id = $_SESSION['user_id'];

// Récupération des informations de l'utilisateur connecté
$host = "localhost"; // Nom d'hôte de la base de données
$user = "root"; // Nom d'utilisateur de la base de données
$password_db = ""; // Mot de passe de la base de données
$dbname = "greengarden"; // Nom de la base de données

$tot_c = 0;

try {
	$conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $password_db);
	// configuration pour afficher les erreurs pdo
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//on récup l'id du client grâce à l'id user
$stmt = $conn->prepare("SELECT * FROM t_d_client WHERE Id_User=:userid");
$stmt->bindValue(':userid', $user_id);
$stmt->execute();
$client = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $conn->prepare('SELECT COUNT(*) AS total FROM t_d_commande WHERE Id_Client=:client');
$stmt->bindValue(':client', $client['Id_Client']);
$stmt->execute();
$commande_a = $stmt->fetch(PDO::FETCH_ASSOC);

	if($commande_a['total'] >= 1):

		if(!isset($_GET['option'])):
		
				$stmt = $conn->prepare('SELECT com.Id_Commande, com.Num_Commande, com.Date_Commande, com.Id_Statut, p_com.Libelle_TypePaiement AS Paiement, s_com.Libelle_Statut AS Statut,
									    (SELECT ROUND(SUM((Quantite*Prix_Achat)+(((Quantite*Prix_Achat)*Taux_TVA)/100)), 2) FROM t_d_lignecommande WHERE Id_Commande = com.Id_Commande) AS Prix
										FROM t_d_commande AS com 
										JOIN t_d_statut_commande AS s_com ON com.Id_Statut = s_com.Id_Statut 
										JOIN t_d_type_paiement AS p_com ON com.Id_TypePaiement = p_com.Id_TypePaiement
										JOIN t_d_lignecommande AS lign ON com.Id_Commande=lign.Id_Commande
										WHERE com.Id_Client=:client
										GROUP BY Id_Commande 
										ORDER BY com.Id_Commande DESC');			
			$stmt->bindValue(':client', $client['Id_Client']);
			$stmt->execute();
			$commande = $stmt->fetchAll();
			$tot_c = $commande_a['total'];
		elseif(isset($_GET['option']) && $_GET['option'] == "en_cours"):
			$stmt = $conn->prepare('SELECT COUNT(*) AS total FROM t_d_commande 
			                        WHERE Id_Client=:client AND Id_Statut=:statut_saisi
									   OR Id_Client=:client AND Id_Statut=:statut_prepa
									   OR Id_Client=:client AND Id_Statut=:statut_exp
									   OR Id_Client=:client AND Id_Statut=:statut_liv_in_progress');
			$stmt->bindValue(':client', $client['Id_Client']);
			$stmt->bindValue(':statut_saisi', 1);
			$stmt->bindValue(':statut_prepa', 3);
			$stmt->bindValue(':statut_exp', 4);
			$stmt->bindValue(':statut_liv_in_progress', 8);
			$stmt->execute();
			$commande_t = $stmt->fetch(PDO::FETCH_ASSOC);
			
			if($commande_t['total'] >= 1):
			
				$stmt = $conn->prepare('SELECT com.Id_Commande, com.Num_Commande, com.Date_Commande, com.Id_Statut, p_com.Libelle_TypePaiement AS Paiement, s_com.Libelle_Statut AS Statut,
									    (SELECT ROUND(SUM((Quantite*Prix_Achat)+(((Quantite*Prix_Achat)*Taux_TVA)/100)), 2) FROM t_d_lignecommande WHERE Id_Commande = com.Id_Commande) AS Prix
										FROM t_d_commande AS com 
										JOIN t_d_statut_commande AS s_com ON com.Id_Statut = s_com.Id_Statut 
										JOIN t_d_type_paiement AS p_com ON com.Id_TypePaiement = p_com.Id_TypePaiement
										JOIN t_d_lignecommande AS lign ON com.Id_Commande=lign.Id_Commande
										WHERE com.Id_Client=:client AND com.Id_Statut=:statut_saisi
										   OR com.Id_Client=:client AND com.Id_Statut=:statut_prepa
										   OR com.Id_Client=:client AND com.Id_Statut=:statut_exp
										   OR com.Id_Client=:client AND com.Id_Statut=:statut_liv_in_progress
										GROUP BY Id_Commande
										ORDER BY com.Id_Commande DESC');
				$stmt->bindValue(':client', $client['Id_Client']);
				$stmt->bindValue(':statut_saisi', 1);
				$stmt->bindValue(':statut_prepa', 3);
				$stmt->bindValue(':statut_exp', 4);
				$stmt->bindValue(':statut_liv_in_progress', 8);
				$stmt->execute();
				$commande = $stmt->fetchAll();
				$tot_c = $commande_t['total'];
			
			else:
				echo "<div class='alert alert-warning' role='alert'>Vous n'avez aucune commande en cours.</div>";
			endif;
		
		elseif(isset($_GET['option']) && $_GET['option'] == "termine"):
			$stmt = $conn->prepare('SELECT COUNT(*) AS total FROM t_d_commande 
			                        WHERE Id_Client=:client AND Id_Statut=:statut_solde');
			$stmt->bindValue(':client', $client['Id_Client']);
			$stmt->bindValue(':statut_solde', 6);
			$stmt->execute();
			$commande_t = $stmt->fetch(PDO::FETCH_ASSOC);
			
			if($commande_t['total'] >= 1):
										
				$stmt = $conn->prepare('SELECT com.Id_Commande, com.Num_Commande, com.Date_Commande, com.Id_Statut, p_com.Libelle_TypePaiement AS Paiement, s_com.Libelle_Statut AS Statut,
									    (SELECT ROUND(SUM((Quantite*Prix_Achat)+(((Quantite*Prix_Achat)*Taux_TVA)/100)), 2) FROM t_d_lignecommande WHERE Id_Commande = com.Id_Commande) AS Prix
										FROM t_d_commande AS com 
										JOIN t_d_statut_commande AS s_com ON com.Id_Statut = s_com.Id_Statut 
										JOIN t_d_type_paiement AS p_com ON com.Id_TypePaiement = p_com.Id_TypePaiement
										JOIN t_d_lignecommande AS lign ON com.Id_Commande=lign.Id_Commande
										WHERE com.Id_Client=:client AND com.Id_Statut=:statut_solde
										GROUP BY Id_Commande
										ORDER BY com.Id_Commande DESC');
				$stmt->bindValue(':client', $client['Id_Client']);
				$stmt->bindValue(':statut_solde', 6);
				$stmt->execute();
				$commande = $stmt->fetchAll();
				$tot_c = $commande_t['total'];
			
			else:
				echo "<div class='alert alert-warning' role='alert'>Vous n'avez aucune commande terminé.</div>";
			endif;
		
		elseif(isset($_GET['option']) && $_GET['option'] == "retour"):
			$stmt = $conn->prepare('SELECT COUNT(*) AS total FROM t_d_commande 
			                        WHERE Id_Client=:client AND Id_Statut=:statut_retour');
			$stmt->bindValue(':client', $client['Id_Client']);
			$stmt->bindValue(':statut_retour', 7);
			$stmt->execute();
			$commande_t = $stmt->fetch(PDO::FETCH_ASSOC);
			
			if($commande_t['total'] >= 1):
			
				$stmt = $conn->prepare('SELECT com.Id_Commande, com.Num_Commande, com.Date_Commande, com.Id_Statut, p_com.Libelle_TypePaiement AS Paiement, s_com.Libelle_Statut AS Statut,
									    (SELECT ROUND(SUM((Quantite*Prix_Achat)+(((Quantite*Prix_Achat)*Taux_TVA)/100)), 2) FROM t_d_lignecommande WHERE Id_Commande = com.Id_Commande) AS Prix
										FROM t_d_commande AS com 
										JOIN t_d_statut_commande AS s_com ON com.Id_Statut = s_com.Id_Statut 
										JOIN t_d_type_paiement AS p_com ON com.Id_TypePaiement = p_com.Id_TypePaiement
										JOIN t_d_lignecommande AS lign ON com.Id_Commande=lign.Id_Commande
										WHERE com.Id_Client=:client AND com.Id_Statut=:statut_retour
										GROUP BY Id_Commande
										ORDER BY com.Id_Commande DESC');
				$stmt->bindValue(':client', $client['Id_Client']);
				$stmt->bindValue(':statut_retour', 7);
				$stmt->execute();
				$commande = $stmt->fetchAll();
				$tot_c = $commande_t['total'];
			
			else:
				echo "<div class='alert alert-warning' role='alert'>Vous n'avez aucune commande en cours de retour.</div>";
			endif;
		else:
			echo "<div class='alert alert-warning' role='alert'>Vous n'avez réalisé aucune commande.</div>";
		endif;
		
		
		if($tot_c > 0):
		echo "<div class='row'>";
            foreach ($commande as $c) {				
				if ($tot_c == 1) {
					echo "<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12 col-xs-B-12 justify-content-center'>";
				}
				elseif ($tot_c == 2) {
					echo "<div class='col-lg-6 col-md-6 col-sm-6 col-xs-12 col-xs-B-12 justify-content-center'>";
				}
				else{
					echo "<div class='col-lg-4 col-md-4 col-sm-6 col-xs-12 col-xs-B-12 justify-content-center'>";
				}
				echo "<a href='commande_inf.php?id={$c['Id_Commande']}'>";
				echo "<div class='card'>
						<div class='card-body'>
							<h1 class='card-text'>Commande : N° {$c['Id_Commande']}</h1>
							<p class='card-text'>Nom : {$c['Num_Commande']}</p>
							<p class='card-text'>Date : {$c['Date_Commande']}</p>
							<p>
								<button class='btn btn-round btn-outline-dark'>Prix : {$c['Prix']} €</button>
							</p>
							<p>";
							
							if($c['Id_Statut'] == 1 || $c['Id_Statut'] == 3 || $c['Id_Statut'] == 4 || $c['Id_Statut'] == 8):
								echo "<button class='btn btn-round btn-outline-warning'>Statut : En cours</button>";
							elseif($c['Id_Statut'] == 6):
								echo "<button class='btn btn-round btn-outline-success'>Statut : Terminé</button>";
							elseif($c['Id_Statut'] == 7):
								echo "<button class='btn btn-round btn-outline-danger'>Statut : Retour en cours</button>";
							else:
								echo "<button class='btn btn-round btn-outline-danger'>Statut : {$c['Statut']}</button>";
							endif;
							
							echo "
								<button class='btn btn-round btn-outline-dark'>{$c['Statut']}</button>
							</p>
							<a href='commande_inf.php?id={$c['Id_Commande']}' class='btn btn-round btn-outline-success button-100-margin'>Voir la commande</a>
						</div>
					</div>";
				echo "</a>";
				echo "</div>";
            }
		echo "</div>";
		endif;
		
		
		
		
	else:
		echo "<div class='alert alert-warning' role='alert'>Vous n'avez réalisé aucune commande.</div>";
	endif;

} catch (PDOException $e) {
	echo "Connection failed: " . $e->getMessage();
}
?>


<p>
<br>
<a class='btn btn-outline-success float-left' href="catalogue.php">Continuer mes achats</a>
<br><br>
</p>

<?php 
} else {
	header('Location: connexion.php'); // Redirection vers la page de connexion
	exit();
}
?>
</div>