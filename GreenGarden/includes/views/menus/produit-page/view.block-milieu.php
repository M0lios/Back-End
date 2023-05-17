<div class="listing-article">

<button class='btn btn-round btn-outline-dark' onclick="goBack()">Retour</button>

<?php
$host = "localhost";
$user = "root";
$pwd = "";
$dbname = "greengarden";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $pwd);
} catch (PDOException $e) {
    echo "Connection failed " . $e->getMessage();
}

if (isset($_GET['id'])) {

    $id_produit = $_GET['id'];

    try {
        $stmt = $conn->prepare("SELECT * FROM t_d_produit where id_produit=:id");
        $stmt->bindValue(':id', $id_produit);
        $stmt->execute();

		if ($stmt->rowCount() > 0) {	
		
        $produit = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt = $conn->prepare("SELECT * FROM t_d_categorie where Id_Categorie=:idcat");
        $stmt->bindValue(':idcat', $produit['Id_Categorie']);
        $stmt->execute();
        $categorie = $stmt->fetch(PDO::FETCH_ASSOC);
		
		
		echo "			
			<button class='btn btn-round btn-outline-dark float-right'>{$produit['Ref_fournisseur']}</button>
			<div class='card margin-card-produit'>
			  <div class='row g-0'>
				<div class='col-md-3 d-flex align-items-center justify-content-center'>
					<img src='styles/images/produits/{$produit['Id_Produit']}/{$produit['Photo']}' class='img-fluid rounded-start' alt='{$produit['Photo']}'>
				</div>
				<div class='col-md-9'>
				<div class='row'>
					<div class='card-body col-lg-12 col-md-12 col-sm-12 col-xs-12 col-xs-B-12'>
					<div class='row'>
					<div class='col-lg-8 col-md-8 col-sm-8 col-xs-12 col-xs-B-12'>
						<h1>{$produit['Nom_court']}</h1>
						<p>Catégorie: {$categorie['Libelle']}</p>
						<p>Description: {$produit['Nom_Long']}</p>
						<p>Prix HT: <b>{$produit['Prix_Achat']} €</b></p>
					</div>
					<div class='col-lg-4 col-md-4 col-sm-4 col-xs-12 col-xs-B-12 d-flex align-items-center justify-content-center'>
							<form method='GET'>
								<input type='hidden' name='id' value='{$id_produit}'>
								<input class='btn btn-round btn-outline-success vertical-center' type='submit' value='Ajouter au panier'>
							</form>
					</div>
					</div>
					</div>
				</div>
				</div>
			  </div>
			</div>
		";
		
		
		}
		else{
			echo "<br><button class='btn btn-round btn-danger button-margin-bottom'>Aucun produit trouvé !</button>";
		}
		
    } catch (PDOException $e) {
        echo
        "Erreur: " . $e->getMessage();
        exit();
    }
} else {
    echo "<br><button class='btn btn-round btn-danger button-margin-bottom'>Aucun produit trouvé !</button>";
    exit;
}

?>
</div>