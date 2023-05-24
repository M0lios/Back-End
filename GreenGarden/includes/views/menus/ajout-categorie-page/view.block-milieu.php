<?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] > 1): ?>

<div class="listing-article">

<a href="categorie.php" class='btn btn-round btn-outline-dark float-right' >Voir les Catégories</a>

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
        isset($_POST['libelle'])
    ) {		
        $libelle = escape_string($_POST['libelle']);
		if(isset($_POST['checkbox'])) {
			$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM t_d_categorie 
			                        WHERE Libelle=:libelle");
			$stmt->bindValue(':libelle', escape_string($_POST['libelle']));
			$stmt->execute();
			$donnee_existe = $stmt->fetch(PDO::FETCH_ASSOC);
			$parent = null;
		}
		else{
			echo $_POST['checkbox'];
			$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM t_d_categorie 
			                        WHERE Libelle=:libelle AND Id_Categorie_Parent=:parent");
			$stmt->bindValue(':libelle', escape_string($_POST['libelle']));
			$stmt->bindValue(':parent', escape_string($_POST['parent']));
			$stmt->execute();
			$donnee_existe = $stmt->fetch(PDO::FETCH_ASSOC);
			$parent = escape_string($_POST['parent']);
		}		
		$count_item = $donnee_existe['total'];
		//Si produit pas présent avec les param Libelle et Id_Categorie_Parent
        if($count_item < 1){
            // Ajout du produit à la base de données
            try {
                $stmt = $conn->prepare("INSERT INTO t_d_categorie (
				Libelle,
				Id_Categorie_Parent
				) VALUES (
				:libelle,
				:parent
				)");
                $stmt->bindValue(':libelle', $libelle);
                $stmt->bindValue(':parent', $parent);
                $stmt->execute();
                $product_id = $conn->lastInsertId();
				
				//Redirection sur la catégorie créé
                header("Location: categorie.php?id={$product_id}");
                exit();

            } catch (PDOException $e) {
                echo "Erreur: " . $e->getMessage();
                exit();
            }
		}
		else{
			echo "La catégorie existe déjà !";
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

<h1>Ajout d'une Catégorie</h1>
<div class="card margin-card-produit">
	<div class="card-body">
    <form method="post" enctype="multipart/form-data">
		
		<div class="input-group mb-3">
			<span class="input-group-text" id="basic-libelle">Catégorie</span>
			<input type="text" class="form-control" placeholder="Nom de la catégorie" aria-label="Nom de la catégorie" aria-describedby="basic-libelle" id="libelle" name="libelle" required>
		</div>
		
		<div class="form-check form-switch">
			<input class="form-check-input" type="checkbox" role="switch" id="checkbox" name="checkbox" checked="checked">
			<label class="form-check-label" for="checkbox">Parent de la catégorie</label>
		</div>

		<div class="input-group mb-3" id="select_parent" style="display:none;">
			<label class="input-group-text" for="categorie">Catégorie Parent</label>
			<select class="form-select" id="parent" name="parent" required>
				<option selected>Choisissez une catégorie parent</option>
				<?php
					$stmt = $conn->query("SELECT * from t_d_categorie WHERE Id_Categorie_Parent IS NULL ");
					if ($stmt->rowCount() > 0) {
						while ($row = $stmt->fetch()) {
							echo "<option value='". $row['Id_Categorie'] ."'>" . $row['Libelle'] . "</option>";
						}
					}
				?>
			</select>
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