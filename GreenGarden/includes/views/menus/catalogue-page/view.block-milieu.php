<div class="listing-article">
	<form method="post" action="">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-xs-B-12">
	<div class="row">
		<div class='col-lg-6 col-md-6 col-sm-12 col-xs-12 col-xs-B-12'>
			<h1>Catalogue</h1>
		</div>
		<div class='col-lg-6 col-md-6 col-sm-12 col-xs-12 col-xs-B-12'>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-xs-B-12">
				<div class="row">
					<div class='col-lg-6 col-md-6 col-sm-12 col-xs-12 col-xs-B-12'>
						<input class="form-control" type="search" placeholder="Rechercher" aria-label="Rechercher"  type="text" name="search_term" id="search_term" 
						value="<?php if(isset($_POST['search'])) { echo htmlspecialchars($_POST['search_term']); } ?>"/>
					</div>
					<div class='col-lg-6 col-md-6 col-sm-12 col-xs-12 col-xs-B-12'>
					<?php if (isset($_POST['search'])) { ?>
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-xs-B-12">
							<div class="row">
								<button class="btn btn-outline-success col-lg-6 col-md-6 col-sm-12 col-xs-12 col-xs-B-12" type="submit" name="search" type="submit">Rechercher</button>
								<a href="<?php echo $page; ?>.php" class="btn btn-outline-danger col-lg-6 col-md-6 col-sm-12 col-xs-12 col-xs-B-12" type="submit">Reset</a>
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
	</div>
	</form>
	<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-xs-B-12">
	<div class="row">	
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
    if (isset($_POST['search'])) {
        $search_term = $_POST['search_term'];
        $sql = "select * from t_d_produit WHERE nom_court like :search or nom_Long like :search";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':search', '%' . $search_term . '%');
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
			echo "
			<div class='row'>
				<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12 col-xs-B-12'>
					<br><p class='espace-search'>{$stmt->rowCount()} produit(s) trouvé !</p>
				</div>
			</div>
			";
            while ($row = $stmt->fetch()) {				
				if ($stmt->rowCount() == 1) {
					echo "<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12 col-xs-B-12 justify-content-center'>";
				}
				elseif ($stmt->rowCount() == 2) {
					echo "<div class='col-lg-6 col-md-6 col-sm-6 col-xs-12 col-xs-B-12 justify-content-center'>";
				}
				else{
					echo "<div class='col-lg-4 col-md-4 col-sm-6 col-xs-12 col-xs-B-12 justify-content-center'>";
				}
				echo "<a href='produit.php?id={$row['Id_Produit']}'>";
				echo "<div class='card margin-card'>
				        <div class='d-flex align-items-center justify-content-center'>
						<img src='styles/images/produits/{$row['Id_Produit']}/{$row['Photo']}' class='img-fluid rounded-start' alt='{$row['Photo']}'>
						</div>
						<div class='card-body'>
							<h5 class='card-title'>{$row['Nom_court']}</h5>
							<p class='card-text'>{$row['Nom_Long']}</p>							
							<a href='produit.php?id={$row['Id_Produit']}' class='btn btn-round btn-outline-success button-100-margin'>Voir le produit</a>
						</div>
					</div>";				
				echo "</a>";
				echo "</div>";
            }
			echo "<button class='btn btn-round btn-danger button-margin-bottom'>Plus aucun résultat !</button>";
        }
		else{
			echo "<button class='btn btn-round btn-danger button-margin-bottom'>Aucun produit trouvé !</button>";
		}
    } else {
        $sql = "select * from t_d_produit";
        $stmt = $conn->query($sql);

        if ($stmt->rowCount() > 0) {
			echo "
			<div class='row'>
				<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12 col-xs-B-12'>
					<br><p class='espace-search'>{$stmt->rowCount()} produit(s) trouvé !</p>
				</div>
			</div>
			";
            while ($row = $stmt->fetch()) {				
				if ($stmt->rowCount() == 1) {
					echo "<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12 col-xs-B-12 justify-content-center'>";
				}
				elseif ($stmt->rowCount() == 2) {
					echo "<div class='col-lg-6 col-md-6 col-sm-6 col-xs-12 col-xs-B-12 justify-content-center'>";
				}
				else{
					echo "<div class='col-lg-4 col-md-4 col-sm-6 col-xs-12 col-xs-B-12 justify-content-center'>";
				}
				echo "<a href='produit.php?id={$row['Id_Produit']}'>";
				echo "<div class='card margin-card'>
				        <div class='d-flex align-items-center justify-content-center'>
						<img src='styles/images/produits/{$row['Id_Produit']}/{$row['Photo']}' class='img-fluid rounded-start' alt='{$row['Photo']}'>
						</div>
						<div class='card-body'>
							<h5 class='card-title'>{$row['Nom_court']}</h5>
							<p class='card-text'>{$row['Nom_Long']}</p>							
							<a href='produit.php?id={$row['Id_Produit']}' class='btn btn-round btn-outline-success button-100-margin'>Voir le produit</a>
						</div>
					</div>";				
				echo "</a>";
				echo "</div>";
            }
			echo "<button class='btn btn-round btn-danger button-margin-bottom'>Plus aucun résultat !</button>";
        }
		else{
			echo "<button class='btn btn-round btn-danger button-margin-bottom'>Aucun produit trouvé !</button>";
		}
    }
	?>
	</div>
	</div>
	</div>
</div>