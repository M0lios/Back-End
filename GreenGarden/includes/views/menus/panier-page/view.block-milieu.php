<div class="listing-article">

<h1>Mon panier</h1>

<?php
// Récupération des informations de l'utilisateur connecté
$host = "localhost"; // Nom d'hôte de la base de données
$user = "root"; // Nom d'utilisateur de la base de données
$password_db = ""; // Mot de passe de la base de données
$dbname = "greengarden"; // Nom de la base de données

try {
	$conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $password_db);
	// configuration pour afficher les erreurs pdo
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$totalHT = 0;
	$totalTTC = 0;

	if (isset($_SESSION['panier'])) {

		foreach ($_SESSION['panier'] as $productid => $quantity) {
			$id = $productid;

			$stmt = $conn->prepare("SELECT * FROM t_d_produit WHERE id_produit=:id");
			$stmt->bindValue(':id', $id);
			$stmt->execute();
			$produit = $stmt->fetch(PDO::FETCH_ASSOC);

			$name = $produit['Nom_court'];
			$name_long = $produit['Nom_Long'];
			$photo = $produit['Photo'];
			$priceht = $produit['Prix_Achat'];
			$pricettc = $priceht  * (1 + $produit['Taux_TVA'] / 100);

			$total_productht = $priceht * $quantity;
			$total_productttc = $pricettc * $quantity;
			$totalHT += $total_productht;
			$totalTTC += $total_productttc;

			//echo "<tr><td>{$name}</td><td>{$priceht} € HT</td><td>{$pricettc} € TTC</td>
			//<td>{$quantity}</td><td>{$total_productht} € HT</td><td>{$total_productttc} € TTC</td></tr>";

			echo "
			<div class='card'>
			<div class='row'>
				<div class='col-lg-2 col-md-2 col-sm-6 col-xs-12 col-xs-B-12 d-flex align-items-center justify-content-center'>
					<img src='styles/images/produits/{$id}/{$photo}' class='img-fluid rounded-start' id='panier_albumMini_{$id}'>
				</div>
				<div class='col-md-4 col-md-4 col-sm-6 col-xs-12 col-xs-B-12 d-flex align-items-center justify-content-center'>
					<div class='card-body'>
						<h5 class='card-title'>{$name}</h5>
						<p class='card-text'>{$name_long}</p>
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
												<button class='input-group-text moins_item' onclick='MoinsItem({$id}, {$quantity})'>-</button>
												<input id='number_item_add_panier_' type='number' class='form-control' aria-label='0' value='{$quantity}' min='1' max='100' step='1' disabled>
												<button class='input-group-text plus_item' onclick='PlusItem({$id}, {$quantity})'>+</button>
											</div>
										</div>
									</div>
									<div class='row'>
										<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12 col-xs-B-12 mb-3'>
											<div class='input-group'>
												<button class='input-group-text'>Prix par article : </button>
												<input id='number_item_add_prix' type='number' class='form-control' aria-label='0' value='{$priceht}' disabled>
												<button class='input-group-text'>€</button>
											</div>
										</div>
									</div>



									<div class='row'>
										<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12 col-xs-B-12 mb-3'>
											<div class='input-group'>
												<button class='input-group-text'>Total TTC : </button>
												<input id='number_item_add_prix_quantity_panier_' type='number' class='form-control' aria-label='0' value='{$total_productttc}' disabled>
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
						<button class='btn btn-outline-danger verticlal-center' data-idp='' onclick='DeleteItemPanier({$id}, {$quantity});'>Supprimer</button>
					</div>
				</div>
			</div>
		</div>";


		}
	} else {
		echo "<p>Votre panier est vide.</p>";
	}
} catch (PDOException $e) {
	echo "Connection failed: " . $e->getMessage();
}
?>

<p><a href="catalogue.php">Continuer mes achats</a></p>

</div>