<div class="listing-article">

<button class='btn btn-round btn-outline-dark' onclick="goBack()">Retour</button>
<br><br>
<h1>Commande : N° <?= $_GET['id']; ?></h1>

<?php if(isset($_SESSION['user_id']) == true && $_SESSION['user_id'] != "") { ?>
<?php
require 'includes/class/produit.php';
require 'includes/class/categorie.php';
require 'includes/class/fournisseur.php';

if (isset($_GET['id']) == true && isset($_GET['id']) > 0 && is_numeric($_GET['id'])) {

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
$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM t_d_user WHERE Id_User=:userid");
$stmt->bindValue(':userid', $user_id);
$stmt->execute();
$nb_user = $stmt->fetch(PDO::FETCH_ASSOC);

if($nb_user['total'] == 1){
	
//on récup l'id du client grâce à l'id user
$stmt = $conn->prepare("SELECT * FROM t_d_client WHERE Id_User=:userid");
$stmt->bindValue(':userid', $user_id);
$stmt->execute();
$client = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $conn->prepare('SELECT COUNT(*) AS total FROM t_d_commande WHERE Id_Client=:client AND Id_Commande=:commande');
$stmt->bindValue(':client', $client['Id_Client']);
$stmt->bindValue(':commande', $_GET['id']);
$stmt->execute();
$commande_a = $stmt->fetch(PDO::FETCH_ASSOC);

	if($commande_a['total'] >= 1):
	
		$stmt = $conn->prepare('SELECT com.Id_Statut, s_com.Libelle_Statut AS Statut 
		                        FROM t_d_commande AS com 
								JOIN t_d_statut_commande AS s_com ON com.Id_Statut = s_com.Id_Statut 
								WHERE Id_Commande=:commande');
		$stmt->bindValue(':commande', $_GET['id']);
		$stmt->execute();
		$commande_c = $stmt->fetch(PDO::FETCH_ASSOC);	
	
		$stmt = $conn->prepare('SELECT * FROM t_d_lignecommande WHERE Id_Commande=:commande');
		$stmt->bindValue(':commande', $_GET['id']);
		$stmt->execute();
		$commande = $stmt->fetchAll();
		
		$totalHT = 0;
		$totalTTC = 0;
		
		if($commande_c['Id_Statut'] == 1 || $commande_c['Id_Statut'] == 3 || $commande_c['Id_Statut'] == 4 || $commande_c['Id_Statut'] == 8):
			echo "<button class='btn btn-round btn-outline-warning'>Statut : En cours</button>";
		elseif($commande_c['Id_Statut'] == 6):
			echo "<button class='btn btn-round btn-outline-success'>Statut : Terminé</button>";
		elseif($c['Id_Statut'] == 7):
			echo "<button class='btn btn-round btn-outline-danger'>Statut : Retour en cours</button>";
		else:
			echo "<button class='btn btn-round btn-outline-danger'>Statut : {$commande_c['Statut']}</button>";
		endif;
		
		echo "<br><br>";
		
	    foreach ($commande as $c) {	


			
			$id = $c['Id_Produit'];
			$quantity = $c['Quantite'];

			$p = new Produit();
            $produit = $p->getProduitById($id)[0];

			$priceht = $produit['Prix_Achat'];
			$pricettc = number_format(($priceht  * (1 + $produit['Taux_TVA'] / 100)), 2, '.', '');

			$total_productht = number_format(($priceht * $quantity), 2, '.', '');
			$total_productttc = number_format(($pricettc * $quantity), 2, '.', '');
			$totalHT += $total_productht;
			$totalTTC += $total_productttc;
			
			$c = new Categorie();
			$categorie = $c->getCategorieById($produit['Id_Categorie'])[0];
		
			$f = new Fournisseur();
			$fournisseur = $f ->getFournisseurById($produit['Id_Fournisseur'])[0];

			echo "
			<div class='card' id='card_panier_{$id}'>
			<div class='row'>
				<div class='col-lg-2 col-md-2 col-sm-6 col-xs-12 col-xs-B-12 d-flex align-items-center justify-content-center'>
					<img src='styles/images/produits/{$produit['Id_Produit']}/{$produit['Photo']}' class='img-fluid rounded-start' alt='{$produit['Photo']}'>
				</div>
				<div class='col-md-5 col-md-5 col-sm-6 col-xs-12 col-xs-B-12 d-flex align-items-center justify-content-center'>
					<div class='card-body'>
						<h1>{$produit['Nom_court']}</h1>
						<p>Catégorie: {$categorie['Libelle']}</p>
						<p>Description: {$produit['Nom_Long']}</p>
						<p>
							<button class='btn btn-round btn-outline-dark'>{$produit['Ref_fournisseur']}</button>
							<button class='btn btn-round btn-outline-dark'>{$fournisseur['Nom_Fournisseur']}</button>
						</p>
					</div>
				</div>
				<div class='col-md-5 col-md-5 col-sm-6 col-xs-6 col-xs-B-12 d-flex align-items-center justify-content-center'>
					<div class='card-body'>
						<div class='verticlal-center'>
							<div class='row'>
								<div class='col-md-12 col-md-12 col-sm-12 col-xs-12 col-xs-B-12'>
									<div class='row'>
										<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12 col-xs-B-12 mb-3'>
											<div class='input-group'>
												<button class='input-group-text'>Quantité : </button>
												<input type='number' class='form-control' aria-label='0' value='{$quantity}' min='1' max='100' step='1' disabled>
											</div>
										</div>
									</div>
									<div class='row'>
										<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12 col-xs-B-12 mb-3'>
											<div class='input-group'>
												<button class='input-group-text'>Prix unitaire : </button>
												<input type='number' class='form-control' aria-label='0' value='{$priceht}' disabled>
												<button class='input-group-text'>€</button>
											</div>
										</div>
									</div>

								<div class='row'>
									<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12 col-xs-B-12 mb-3'>
										<div class='input-group'>
											<button class='input-group-text'>Prix TTC : </button>
											<input type='number' class='form-control' aria-label='0' value='{$pricettc}' step='0.01' disabled>
											<button class='input-group-text'>€</button>
										</div>
									</div>
								</div>
							
									<div class='row'>
										<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12 col-xs-B-12 mb-3'>
											<div class='input-group'>
												<button class='input-group-text'>Total TTC : </button>
												<input type='number' class='form-control' aria-label='0' value='{$total_productttc}' disabled>
												<button class='input-group-text'>€</button>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>";


			
			
			
			
			
			
			
		}
	
	else:
		echo "<div class='alert alert-warning' role='alert'>La commande n'est pas valide.</div>";
		header('Location: commande.php'); // Redirection vers la page de connexion
		exit();
	endif;
	
}
else{
    echo "la session user_id est inexistante dans la BDD !";
	header('Location: connexion.php'); // Redirection vers la page de connexion
	exit();
}


} catch (PDOException $e) {
	echo "Connection failed: " . $e->getMessage();
}
?>


<p>
<br>
<a class='btn btn-outline-success float-left' href="catalogue.php">Continuer mes achats</a>
<button type='button' class='btn btn-warning float-right' <?php if($totalTTC == 0): ?> style="display:none;"<?php endif; ?>>Total de la commande TTC : <i>
<?php
if($totalTTC != 0):
echo number_format($totalTTC, 2, '.', '');
else:
echo "0.00";
endif;
?>
</i> €</button>
<br><br>
</p>

<?php 

} else {
		echo "<div class='alert alert-warning' role='alert'>La commande n'est pas valide.</div>";
}

} else {
	header('Location: connexion.php'); // Redirection vers la page de connexion
	exit();
}
?>
</div>