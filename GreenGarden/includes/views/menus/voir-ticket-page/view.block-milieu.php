<div class="listing-article">
	<form method="post" action="">
	<div class="row">
		<div class='col-lg-6 col-md-6 col-sm-12 col-xs-12 col-xs-B-12'>
			<h1>Les tickets</h1>
		</div>
		<div class='col-lg-6 col-md-6 col-sm-12 col-xs-12 col-xs-B-12'>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-xs-B-12">
				<div class="row">
					<div class='col-lg-6 col-md-6 col-sm-12 col-xs-12 col-xs-B-12'>
						<input class="form-control" type="search" placeholder="Numéro de commande" aria-label="Rechercher"  type="text" name="search_term" id="search_term" 
						value="<?php if(isset($_POST['search'])) { echo htmlspecialchars($_POST['search_term']); } ?>"/>
					</div>
					<div class='col-lg-6 col-md-6 col-sm-12 col-xs-12 col-xs-B-12'>
					<?php if (isset($_POST['search'])) { ?>
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-xs-B-12">
							<div class="row">
								<button class="btn btn-outline-success col-lg-6 col-md-6 col-sm-12 col-xs-12 col-xs-B-12" type="submit" name="search" type="submit">Rechercher</button>
							<a href="
							<?php if(!isset($_GET['option'])): ?>
								<?php echo $page; ?>.php
							<?php elseif(isset($_GET['option']) && $_GET['option'] == "en_cours"): ?>
								<?php echo $page; ?>.php?option=en_cours
							<?php elseif(isset($_GET['option']) && $_GET['option'] == "termine"): ?>
								<?php echo $page; ?>.php?option=termine
							<?php endif; ?>
							" class="btn btn-outline-danger col-lg-6 col-md-6 col-sm-12 col-xs-12 col-xs-B-12" type="submit">Reset</a>
							</div>
						</div>
					<?php }else{ ?>
						<button class="btn btn-outline-success col-lg-12 col-md-12 col-sm-12 col-xs-12 col-xs-B-12" type="submit" name="search">Rechercher</button>
					<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	</form>


<?php if(isset($_SESSION['user_id']) == true && $_SESSION['user_id'] != "") { ?>

<nav class="nav nav-pills flex-column flex-sm-row new-nav">
  <?php if(!isset($_GET['option'])): ?>
	<button class="flex-sm-fill text-sm-center active">Tous les tickets</button>
  <?php else: ?>
	<a class="flex-sm-fill text-sm-center nav-link" href="<?php echo $page; ?>.php">Tous les tickets</a>
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
  
</nav>
<br>
<?php

require 'includes/functions/functions.php';
	
$user_id = $_SESSION['user_id'];

// Récupération des informations de l'utilisateur connecté
$host = "localhost"; // Nom d'hôte de la base de données
$user = "root"; // Nom d'utilisateur de la base de données
$password_db = ""; // Mot de passe de la base de données
$dbname = "greengarden"; // Nom de la base de données

$tot_t = 0;

try {
	$conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $password_db);
	// configuration pour afficher les erreurs pdo
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//on récup l'id du client grâce à l'id user
$stmt = $conn->prepare("SELECT * FROM t_d_client WHERE Id_User=:userid");
$stmt->bindValue(':userid', $user_id);
$stmt->execute();
$client = $stmt->fetch(PDO::FETCH_ASSOC);

/*SELECT COUNT(*)
FROM t_d_ticket AS t JOIN t_d_commande AS c ON t.Num_Commande=c.Num_Commande WHERE c.Id_Client=2

SELECT t.Id_Ticket, t.Num_Ticket, t.Num_Commande, t.Titre_Ticket, t.Id_Statut, t.Id_Motif, c.Id_Client
FROM t_d_ticket AS t JOIN t_d_commande AS c ON t.Num_Commande=c.Num_Commande WHERE c.Id_Client=2*/


/*$stmt = $conn->prepare('SELECT COUNT(*)
                        FROM t_d_ticket AS t JOIN t_d_commande AS c ON t.Num_Commande=c.Num_Commande 
						WHERE c.Id_Client=:client');
$stmt->bindValue(':client', $client['Id_Client']);
$stmt->execute();
$ticket_a = $stmt->fetch(PDO::FETCH_ASSOC);*/

$stmt = $conn->prepare('SELECT COUNT(*) AS total FROM t_d_ticket');
$stmt->execute();
$ticket_a = $stmt->fetch(PDO::FETCH_ASSOC);

if($ticket_a['total'] >= 1):
	
 if (isset($_POST['search'])) {
    $search_term = escape_string($_POST['search_term']);


	if(!isset($_GET['option'])):
	
			$stmt = $conn->prepare('SELECT COUNT(*) AS total FROM t_d_ticket WHERE Num_Commande LIKE :search');
			$stmt->bindValue(':search', '%' . $search_term . '%');
			$stmt->execute();
			$ticket_t = $stmt->fetch(PDO::FETCH_ASSOC);
			
			if($ticket_t['total'] >= 1):
			
				$stmt = $conn->prepare('SELECT * FROM t_d_ticket 
										 WHERE Num_Commande LIKE :search
										    ORDER BY Id_Ticket DESC');
			    $stmt->bindValue(':search', '%' . $search_term . '%');
				$stmt->execute();
				$ticket = $stmt->fetchAll();
				$tot_t = $ticket_t['total'];
			
			
			else:
				echo "<div class='alert alert-warning' role='alert'>Aucun ticket dans la recherche.</div>";
			endif;
	
	elseif(isset($_GET['option']) && $_GET['option'] == "en_cours"):
	
			$stmt = $conn->prepare('SELECT COUNT(*) AS total FROM t_d_ticket 
			                        WHERE Num_Commande LIKE :search AND Id_Statut=:statut_saisi
									   OR Num_Commande LIKE :search AND Id_Statut=:statut_in_progress');
			$stmt->bindValue(':search', '%' . $search_term . '%');
			$stmt->bindValue(':statut_saisi', 1);
			$stmt->bindValue(':statut_in_progress', 3);
			$stmt->execute();
			$ticket_t = $stmt->fetch(PDO::FETCH_ASSOC);
			
			if($ticket_t['total'] >= 1):
			
				$stmt = $conn->prepare('SELECT * FROM t_d_ticket 
										 WHERE Num_Commande LIKE :search AND Id_Statut=:statut_saisi
										    OR Num_Commande LIKE :search AND Id_Statut=:statut_in_progress 
										    ORDER BY Id_Ticket DESC');
			    $stmt->bindValue(':search', '%' . $search_term . '%');
				$stmt->bindValue(':statut_saisi', 1);
				$stmt->bindValue(':statut_in_progress', 3);
				$stmt->execute();
				$ticket = $stmt->fetchAll();
				$tot_t = $ticket_t['total'];
			
			
			else:
				echo "<div class='alert alert-warning' role='alert'>Aucun ticket n'est en cours dans la recherche.</div>";
			endif;
	
	elseif(isset($_GET['option']) && $_GET['option'] == "termine"):
	
			$stmt = $conn->prepare('SELECT COUNT(*) AS total FROM t_d_ticket 
			                        WHERE Num_Commande LIKE :search AND Id_Statut=:statut_annul
									   OR Num_Commande LIKE :search AND Id_Statut=:statut_termine');
			$stmt->bindValue(':search', '%' . $search_term . '%');
			$stmt->bindValue(':statut_annul', 2);
			$stmt->bindValue(':statut_termine', 4);
			$stmt->execute();
			$ticket_t = $stmt->fetch(PDO::FETCH_ASSOC);
			
			if($ticket_t['total'] >= 1):
			
				$stmt = $conn->prepare('SELECT * FROM t_d_ticket 
										 WHERE Num_Commande LIKE :search AND Id_Statut=:statut_annul
										    OR Num_Commande LIKE :search AND Id_Statut=:statut_termine 
										    ORDER BY Id_Ticket DESC');
			    $stmt->bindValue(':search', '%' . $search_term . '%');
				$stmt->bindValue(':statut_annul', 2);
				$stmt->bindValue(':statut_termine', 4);
				$stmt->execute();
				$ticket = $stmt->fetchAll();
				$tot_t = $ticket_t['total'];
			
			else:
				echo "<div class='alert alert-warning' role='alert'>Aucun ticket n'est terminé dans la recherche.</div>";
			endif;
	else:
		echo "<div class='alert alert-warning' role='alert'>IL n'y a aucun ticket dans la recherche.</div>";			
	endif;


  

} else {
	
	if(!isset($_GET['option'])):
	
			$stmt = $conn->prepare('SELECT * FROM t_d_ticket ORDER BY Id_Ticket DESC');
			$stmt->execute();
			$ticket = $stmt->fetchAll();
			$tot_t = $ticket_a['total'];
	
	elseif(isset($_GET['option']) && $_GET['option'] == "en_cours"):
	
			$stmt = $conn->prepare('SELECT COUNT(*) AS total FROM t_d_ticket 
			                        WHERE Id_Statut=:statut_saisi
									   OR Id_Statut=:statut_in_progress');
			$stmt->bindValue(':statut_saisi', 1);
			$stmt->bindValue(':statut_in_progress', 3);
			$stmt->execute();
			$ticket_t = $stmt->fetch(PDO::FETCH_ASSOC);
			
			if($ticket_t['total'] >= 1):
			
				$stmt = $conn->prepare('SELECT * FROM t_d_ticket 
										 WHERE Id_Statut=:statut_saisi
										    OR Id_Statut=:statut_in_progress 
										    ORDER BY Id_Ticket DESC');
				$stmt->bindValue(':statut_saisi', 1);
				$stmt->bindValue(':statut_in_progress', 3);
				$stmt->execute();
				$ticket = $stmt->fetchAll();
				$tot_t = $ticket_t['total'];
			
			
			else:
				echo "<div class='alert alert-warning' role='alert'>Aucun ticket n'est en cours.</div>";
			endif;
	
	elseif(isset($_GET['option']) && $_GET['option'] == "termine"):
	
			$stmt = $conn->prepare('SELECT COUNT(*) AS total FROM t_d_ticket 
			                        WHERE Id_Statut=:statut_annul
									   OR Id_Statut=:statut_termine');
			$stmt->bindValue(':statut_annul', 2);
			$stmt->bindValue(':statut_termine', 4);
			$stmt->execute();
			$ticket_t = $stmt->fetch(PDO::FETCH_ASSOC);
			
			if($ticket_t['total'] >= 1):
			
				$stmt = $conn->prepare('SELECT * FROM t_d_ticket 
										 WHERE Id_Statut=:statut_annul
										    OR Id_Statut=:statut_termine 
										    ORDER BY Id_Ticket DESC');
				$stmt->bindValue(':statut_annul', 2);
				$stmt->bindValue(':statut_termine', 4);
				$stmt->execute();
				$ticket = $stmt->fetchAll();
				$tot_t = $ticket_t['total'];
			
			else:
				echo "<div class='alert alert-warning' role='alert'>Aucun ticket n'est terminé.</div>";
			endif;
	else:
		echo "<div class='alert alert-warning' role='alert'>IL n'y a aucun ticket.</div>";			
	endif;
	
}

		if($tot_t > 0):
		echo "<div class='row'>";
            foreach ($ticket as $t) {				
				if ($tot_t == 1) {
					echo "<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12 col-xs-B-12 justify-content-center'>";
				}
				elseif ($tot_t == 2) {
					echo "<div class='col-lg-6 col-md-6 col-sm-6 col-xs-12 col-xs-B-12 justify-content-center'>";
				}
				else{
					echo "<div class='col-lg-4 col-md-4 col-sm-6 col-xs-12 col-xs-B-12 justify-content-center'>";
				}
				echo "<a href='commande_inf.php?id={$t['Id_Ticket']}'>";
				echo "<div class='card'>
						<div class='card-body'>
							<h1 class='card-text'>Ticket : N° {$t['Id_Ticket']}</h1>
							<p class='card-text'>Numéro de commande : {$t['Num_Commande']}</p>
							<p class='card-text'>Date : {$t['Date_Ticket']}</p>
							<p>";
							
							if($t['Id_Statut'] == 1 || $t['Id_Statut'] == 3):
								echo "<button class='btn btn-round btn-outline-warning'>Statut : En cours</button>";
							elseif($t['Id_Statut'] == 2 || $t['Id_Statut'] == 4):
								echo "<button class='btn btn-round btn-outline-success'>Statut : Terminé</button>";
							else:
								echo "<button class='btn btn-round btn-outline-danger'>Statut : {$t['Id_Statut']}</button>";
							endif;
							
							echo "
							</p>
							<a href='ticket_inf.php?id={$t['Id_Ticket']}' class='btn btn-round btn-outline-success button-100-margin'>Voir le ticket</a>
						</div>
					</div>";
				echo "</a>";
				echo "</div>";
            }
		echo "</div><br>";
		endif;

else:
	echo "<div class='alert alert-warning' role='alert'>Aucun ticket n'a été réalisé.</div>";
endif;


} catch (PDOException $e) {
	echo "Connection failed: " . $e->getMessage();
}
?>


<?php 
} else {
	header('Location: connexion.php'); // Redirection vers la page de connexion
	exit();
}
?>
</div>