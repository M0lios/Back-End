<div class="listing-article">

<h1>Mon panier</h1>
<div id="all_info"></div>
<?php
require 'includes/class/produit.php';
require 'includes/class/categorie.php';
require 'includes/class/fournisseur.php';
// Récupération des informations de l'utilisateur connecté
$host = "localhost"; // Nom d'hôte de la base de données
$user = "root"; // Nom d'utilisateur de la base de données
$password_db = ""; // Mot de passe de la base de données
$dbname = "greengarden"; // Nom de la base de données

$totalHT = 0;
$totalTTC = 0;

try {
	$conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $password_db);
	// configuration pour afficher les erreurs pdo
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	if (isset($_SESSION['panier'])) {

		foreach ($_SESSION['panier'] as $productid => $quantity) {
			$id = $productid;

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
				<div class='col-md-4 col-md-4 col-sm-6 col-xs-12 col-xs-B-12 d-flex align-items-center justify-content-center'>
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
				<div class='col-md-4 col-md-4 col-sm-6 col-xs-6 col-xs-B-12 d-flex align-items-center justify-content-center'>
					<div class='card-body'>
						<div class='verticlal-center'>
							<div class='row'>
								<div class='col-md-12 col-md-12 col-sm-12 col-xs-12 col-xs-B-12'>
									<div class='row'>
										<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12 col-xs-B-12 mb-3'>
											<div class='input-group'>
												<button class='input-group-text moins_item' onclick='MoinsItem({$id})'>-</button>
												<input id='number_item_add_panier_{$id}' type='number' class='form-control' aria-label='0' value='{$quantity}' min='1' max='100' step='1' disabled>
												<button class='input-group-text plus_item' onclick='PlusItem({$id})'>+</button>
											</div>
										</div>
									</div>
									<div class='row'>
										<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12 col-xs-B-12 mb-3'>
											<div class='input-group'>
												<button class='input-group-text'>Prix unitaire : </button>
												<input id='number_item_add_prix_{$id}' type='number' class='form-control' aria-label='0' value='{$priceht}' disabled>
												<button class='input-group-text'>€</button>
											</div>
										</div>
									</div>

								<div class='row'>
									<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12 col-xs-B-12 mb-3'>
										<div class='input-group'>
											<button class='input-group-text'>Prix TTC : </button>
											<input id='number_item_add_prix_achat_{$id}' type='number' class='form-control' aria-label='0' value='{$pricettc}' step='0.01' disabled>
											<button class='input-group-text'>€</button>
										</div>
									</div>
								</div>
							
									<div class='row'>
										<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12 col-xs-B-12 mb-3'>
											<div class='input-group'>
												<button class='input-group-text'>Total TTC : </button>
												<input id='number_item_add_prix_quantity_panier_{$id}' type='number' class='form-control' aria-label='0' value='{$total_productttc}' disabled>
												<button class='input-group-text'>€</button>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class='col-md-2 col-md-2 col-sm-6 col-xs-6 col-xs-B-12 d-flex align-items-center justify-content-center'>
					<div class='card-body'>
						<button class='btn btn-outline-danger vertical-center' onclick='DeleteItemPanier({$id});'>Supprimer</button>
					</div>
				</div>
			</div>
		</div>";


		}
	} else {
		echo "<div class='alert alert-warning' role='alert'>Votre panier est vide.</div>";
	}
} catch (PDOException $e) {
	echo "Connection failed: " . $e->getMessage();
}
?>


<p>
<br>
<a class='btn btn-outline-success float-left' href="catalogue.php">Continuer mes achats</a>
<?php if($totalTTC != 0): ?>
<a id='button_valider_panier' class='btn btn-outline-info float-right' href="validation_panier.php">Valider mon panier</a>
<?php endif; ?>
<button id='button_price_all' type='button' class='btn btn-warning float-right' <?php if($totalTTC == 0): ?> style="display:none;"<?php endif; ?>>Total du panier TTC : <i id="total_prix_panier">
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

</div>