<?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] > 1): ?>

<div class="listing-article">
<?php

require 'includes/functions/functions.php';

// Récupération des informations de l'utilisateur connecté
$host = "localhost"; // Nom d'hôte de la base de données
$user = "root"; // Nom d'utilisateur de la base de données
$password_db = ""; // Mot de passe de la base de données
$dbname = "greengarden"; // Nom de la base de données

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $password_db);
    // configuration pour afficher les erreurs pdo
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} 
catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérification si le formulaire a été soumis
    if (
        isset($_POST['nom_court']) 
		&& isset($_POST['nom_long']) 
		&& isset($_POST['reference'])
        && isset($_POST['prix'])
        && isset($_POST['tva']) 
		&& isset($_POST['categorie']) 
		&& isset($_FILES['photo'])
    ) {
        $noml_produit = escape_string($_POST['nom_long']);
        $nomc_produit = escape_string($_POST['nom_court']);
        $reference_produit = escape_string($_POST['reference']);
        $prix_produit = escape_string($_POST['prix']);
        $tva = escape_string($_POST['tva']);
        $photo_produit = $_FILES['photo'];

        $stmt = $conn->prepare("SELECT * from t_d_categorie where Id_Categorie=:catego");
        $stmt->bindValue(':catego', escape_string($_POST['categorie']));
        $stmt->execute();
        $categorie = $stmt->fetch(PDO::FETCH_ASSOC);		

        $stmt_fourn = $conn->prepare("SELECT * from t_d_fournisseur where Id_Fournisseur=:fourn");
        $stmt_fourn->bindValue(':fourn', $_POST['fournisseur']);
        $stmt_fourn->execute();
        $fournisseur = $stmt_fourn->fetch(PDO::FETCH_ASSOC);

        $stmt_count = $conn->prepare("SELECT COUNT(*) AS total from t_d_produit where Id_Fournisseur=:fourn AND Ref_fournisseur=:reference");
        $stmt_count->bindValue(':fourn', $_POST['fournisseur']);
        $stmt_count->bindValue(':reference', escape_string($_POST['reference']));
        $stmt_count->execute();
        $count_item = $stmt_count->fetch(PDO::FETCH_ASSOC);
		
		//Si produit pas présent avec les param Id_fournisseur et Ref_Fournisseur
        if($count_item['total'] < 1){
            // Ajout du produit à la base de données
            try {
                $stmt = $conn->prepare("INSERT INTO t_d_produit (
				Nom_Long,
				Nom_court,
				Id_Fournisseur,
				Ref_fournisseur,
				Prix_Achat,
				Taux_TVA,
				Id_Categorie,
			    Photo
				) VALUES (
				:nomlon,
				:nomcourt,
				:fourn,
				:reference,
				:prix,
				:tva,
				:cat,
				:photo
				)");
                $stmt->bindValue(':nomlon', $noml_produit);
                $stmt->bindValue(':nomcourt', $nomc_produit);
                $stmt->bindValue(':fourn', $fournisseur['Id_Fournisseur']);
                $stmt->bindValue(':reference', $reference_produit);
                $stmt->bindValue(':prix', $prix_produit);
                $stmt->bindValue(':tva', $tva);
                $stmt->bindValue(':cat', $categorie['Id_Categorie']);
                $stmt->bindValue(':photo', $photo_produit['name']);
                $stmt->execute();
                $product_id = $conn->lastInsertId();
				
				// Création du dossier si il n'existe pas pour contenir l'image du produit
				if(!is_dir("styles/images/produits/{$product_id}")){
					mkdir("styles/images/produits/{$product_id}");
				}
				
				//Upload de l'image dans le dossier ciblé
				if (upload_file($photo_produit, "styles/images/produits/{$product_id}/")) {	
					echo "Le fichier est uploadé ";
				} else {
					echo "Le fichier uploadé n'est pas une image";
				}
				
				//Redirection sur le produit créé
				echo "<script>window.location.href = 'produit.php?id={$product_id}';</script>";
                exit();

            } catch (PDOException $e) {
                echo "Erreur: " . $e->getMessage();
                exit();
            }
		}
		else{
			echo "Le produit (référence pour ce fournisseur) existe déjà !";
		}
    } 
	else {
        header('Location: index.php');
        exit();
    }
}

?>
    <?php
    // include 'header.php';
    if (isset($error)) : ?>
        <p style="color: red"><?= $error ?></p>
    <?php endif ?>

    <?php if (isset($success)) : ?>
        <p style="color: green"><?= $success ?></p>
    <?php endif ?>



    <h1>Ajout d'un produit</h1>
<div class="card margin-card-produit">
	<div class="card-body">
    <form method="post" enctype="multipart/form-data">
		
		<div class="input-group mb-3">
			<span class="input-group-text" id="basic-nomc">Nom</span>
			<input type="text" class="form-control" placeholder="Nom du produit" aria-label="Nom du produit" aria-describedby="basic-nomc" id="nomc" name="nom_court" required>
		</div>
		
		<div class="input-group mb-3">
			<span class="input-group-text" id="basic-noml">Nom long (description)</span>
			<textarea type="text" class="form-control" placeholder="Description du produit" aria-label="Description du produit" aria-describedby="basic-noml" id="noml" name="nom_long" required></textarea>
		</div>		
		
		<div class="input-group mb-3">
			<label class="input-group-text" for="fournisseur">Fournisseur</label>
			<select class="form-select" id="fournisseur" name="fournisseur" required>
				<option selected>Choisissez un fournisseur</option>
				<?php
					$stmt = $conn->query("SELECT * from t_d_fournisseur");
					if ($stmt->rowCount() > 0) {
						while ($row = $stmt->fetch()) {
							echo "<option value='". $row['Id_Fournisseur'] ."'>" . $row['Nom_Fournisseur'] . "</option>";
						}
					}
				?>
			</select>
		</div>
		
		<div class="input-group mb-3">
			<span class="input-group-text" id="basic-reference">Référence Fournisseur</span>
			<input type="text" class="form-control" placeholder="######" aria-label="######" aria-describedby="basic-reference" id="reference" name="reference" required>
		</div>
		
		<div class="input-group mb-3">
			<span class="input-group-text">Prix HT Fournisseur</span>
			<input type="number" class="form-control" aria-label="Prix en euro" id="prix" name="prix" min="0" step="0.01" value="0.00" required>
			<span class="input-group-text">€</span>
		</div>
		
		<div class="input-group mb-3">
			<span class="input-group-text">Taux TVA</span>
			<input type="number" class="form-control" aria-label="TVA en pourcentage" id="tva" name="tva" min="0" step="0.01" value="0.00" required>
			<span class="input-group-text">%</span>
		</div>	

		<div class="input-group mb-3">
			<label class="input-group-text" for="categorie">Catégorie</label>
			<select class="form-select" id="categorie" name="categorie" required>
				<option selected>Choisissez une catégorie</option>
				<?php
					$stmt = $conn->query("SELECT * from t_d_categorie");
					if ($stmt->rowCount() > 0) {
						while ($row = $stmt->fetch()) {
							echo "<option value='". $row['Id_Categorie'] ."'>" . $row['Libelle'] . "</option>";
						}
					}
				?>
			</select>
		</div>
		
		<div class="mb-3">
			<label for="photo" class="form-label" id="basic-photo">Photo</label>
			<input type="file" class="form-control" aria-describedby="basic-photo" id="photo" name="photo" required>
		</div>		

		<button class="btn btn-outline-success button-100-margin" type="submit" name="ajouter">Ajouter</button>
    </form>
	</div>
</div>
</div>

<?php
else:
	header('Location: index.php');
    exit();
endif;
?>