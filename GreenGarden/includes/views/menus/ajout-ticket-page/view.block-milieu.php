<?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 4): ?>

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
        isset($_POST['num_commande']) 
		&& isset($_POST['motif_ticket']) 
		&& isset($_POST['titre_ticket'])
        && isset($_POST['text_ticket'])
    ) {
        $num_commande = escape_string($_POST['num_commande']);
        $motif_ticket = escape_string($_POST['motif_ticket']);
        $titre_ticket = escape_string($_POST['titre_ticket']);
        $text_ticket = escape_string($_POST['text_ticket']);

        $stmt_count = $conn->prepare("SELECT COUNT(*) AS total 
		                                from t_d_commande 
										where Num_Commande=:num_commande AND Id_Statut !=:two AND Id_Statut !=:six");
        $stmt_count->bindValue(':num_commande', $_POST['num_commande']);
        $stmt_count->bindValue(':two', 2);
        $stmt_count->bindValue(':six', 6);
        $stmt_count->execute();
        $count_item = $stmt_count->fetch(PDO::FETCH_ASSOC);
		
		//Si produit pas présent avec les param
        if($count_item['total'] > 0){
            // Ajout du produit à la base de données
            try {
                $stmt = $conn->prepare("INSERT INTO t_d_ticket (
				Num_Commande,
				Date_Ticket,
				Titre_Ticket,
				Text_Ticket,
				Id_Statut,
				Id_Motif,
				Id_User
				) VALUES (
				:num_commande,
				:date_ticket,
				:titre_ticket,
				:text_ticket,
				:statut,
				:motif_ticket,
				:id_user
				)");
                $stmt->bindValue(':num_commande', $num_commande);
                $stmt->bindValue(':date_ticket', date('Y-m-d H:i:s'));
                $stmt->bindValue(':titre_ticket', $titre_ticket);
                $stmt->bindValue(':text_ticket', $text_ticket);
                $stmt->bindValue(':statut', 1);
                $stmt->bindValue(':motif_ticket', $motif_ticket);
                $stmt->bindValue(':id_user', $_SESSION['user_id']);
                $stmt->execute();
                $ticket_id = $conn->lastInsertId();
				
				//Redirection sur le produit créé
				echo "<script>window.location.href = 'voir-ticket.php?id={$ticket_id}';</script>";
                exit();

            } catch (PDOException $e) {
                echo "Erreur: " . $e->getMessage();
                exit();
            }
		}
		else{
			echo "La commande n'existe pas !";
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



    <h1>Ajout d'un ticket</h1>
<div class="card margin-card-produit">
	<div class="card-body">
    <form method="post" enctype="multipart/form-data">
	
		<div class="mb-3">
		<label class="input-group-text" for="num_commande">Numéro de commande</label>
			<select
			class="select2 selectpicker form-select" 
			data-max-options="1"
			data-live-search="true"
			data-selected-text-format="count > 1"
			data-placeholder="Sélectionnez un numéro de commande"
			id="num_commande" 
			name="num_commande"
			required>
				<option selected disabled></option>
				<?php
					$stmt = $conn->query("SELECT Num_Commande 
					                        FROM t_d_commande 
											WHERE Id_Statut != 2 
											  AND Id_Statut != 6");
					if ($stmt->rowCount() > 0) {
						while ($row = $stmt->fetch()) {
							echo "<option value='". $row['Num_Commande'] ."'>" . $row['Num_Commande'] . "</option>";
						}
					}
				?>
			</select>
		</div>
		
		<div class="input-group mb-3">
			<label class="input-group-text" for="motif_ticket">Motif</label>
			<select 
			class="form-select"
			id="motif_ticket"
			name="motif_ticket"
			required>
				<option selected disabled>Sélectionnez un motif</option>
				<?php
					$stmt = $conn->query("SELECT * from t_d_motif_ticket");
					if ($stmt->rowCount() > 0) {
						while ($row = $stmt->fetch()) {
							echo "<option value='". $row['Id_Motif'] ."'>" . $row['Libelle_Statut'] . "</option>";
						}
					}
				?>
			</select>
		</div>
		
		<div class="input-group mb-3">
			<span class="input-group-text" id="basic-titre">Titre</span>
			<input type="text" class="form-control" placeholder="Titre du ticket" aria-label="Nom du produit" aria-describedby="basic-titre" id="titre_ticket" name="titre_ticket" required>
		</div>
		
		<div class="input-group mb-3">
			<span class="input-group-text" id="basic-text">Texte (description du ticket)</span>
			<textarea type="text" class="form-control" placeholder="Description du ticket" aria-label="Description du ticket" aria-describedby="basic-text" id="text_ticket" name="text_ticket" required></textarea>
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