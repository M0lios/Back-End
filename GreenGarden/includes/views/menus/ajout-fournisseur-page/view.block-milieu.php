<?php if (isset($_SESSION['user_type']) > 1 ): ?>

<div class="listing-article">

<a href="fournisseur.php" class='btn btn-round btn-outline-dark float-right' >Voir les Fournisseurs</a>

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
        isset($_POST['nom_fournisseur'])
    ) {
        $nom_fournisseur = escape_string($_POST['nom_fournisseur']);

        $stmt = $conn->prepare("SELECT COUNT(*) AS total from t_d_fournisseur where Nom_Fournisseur=:nom");
		$stmt->bindValue(':nom', $nom_fournisseur);
        $stmt->execute();
        $fournisseur = $stmt->fetch(PDO::FETCH_ASSOC);
		
		//Si fournisseur pas présent avec les param Nom_Fournisseur
        if($fournisseur['total'] < 1){
            // Ajout du fournisseur à la base de données
            try {
                $stmt = $conn->prepare("INSERT INTO t_d_fournisseur (
				Nom_Fournisseur
				) VALUES (
				:nom
				)");
                $stmt->bindValue(':nom', $nom_fournisseur);
                $stmt->execute();
                $product_id = $conn->lastInsertId();
				
				//Redirection sur le produit créé
                header("Location: fournisseur.php?id={$product_id}");
                exit();

            } catch (PDOException $e) {
                echo "Erreur: " . $e->getMessage();
                exit();
            }
		}
		else{
			echo "Le Fournisseur : <b>{$_POST['nom_fournisseur']}</b> existe déjà !";
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



    <h1>Ajout d'un Fournisseur</h1>
<div class="card margin-card-produit">
	<div class="card-body">
    <form method="post" enctype="multipart/form-data">
		
		<div class="input-group mb-3">
			<span class="input-group-text" id="basic-fournisseur">Nom</span>
			<input type="text" class="form-control" placeholder="Nom du Fournisseur" aria-label="Nom du Fournisseur" aria-describedby="basic-fournisseur" id="nom_fournisseur" name="nom_fournisseur" required>
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