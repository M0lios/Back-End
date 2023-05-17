<div class="listing-article">
	<form method="post" action="">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-xs-B-12">
	<div class="row">
		<div class='col-lg-6 col-md-6 col-sm-12 col-xs-12 col-xs-B-12'>
			<h1><a href="ajout-categorie.php" class='btn btn-round btn-outline-dark'>Retour</a> / Catégories</h1>
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
	
	require 'includes/functions/functions.php';
	
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
        $search_term = escape_string($_POST['search_term']);
        $sql = "SELECT t1.Id_Categorie, t1.Libelle, t1.Id_Categorie_Parent, t2.Libelle AS Libelle_Parent 
				FROM t_d_categorie AS t1 LEFT JOIN t_d_categorie AS t2 ON t1.Id_Categorie_Parent = t2.Id_Categorie 
				WHERE t1.Libelle like :search OR t2.Libelle like :search";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':search', '%' . $search_term . '%');
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
			echo "
			<div class='row'>
				<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12 col-xs-B-12'>
					<br><p class='espace-search'>{$stmt->rowCount()} catégorie(s) trouvé !</p>
				</div>
			</div>
			";
			echo "
				<div class='table-responsive'>
					<table class='table table-success table-striped table-hover'>
						<thead class='table-dark'>
							<tr>
								<th scope='col'>#</th>
								<th scope='col'>Libellé</th>
								<th scope='col'>Parent</th>
							</tr>
						</thead>
						<tbody>
			";
            while ($row = $stmt->fetch()) {
				echo "
							<tr>
								<th scope='row'>{$row['Id_Categorie']}</th>
								<td>{$row['Libelle']}</td>
								<td>{$row['Libelle_Parent']}</td>
							</tr>
				";
            }
			echo "
						</tbody>
					</table>
				</div>
			";			
			echo "<button class='btn btn-round btn-danger button-margin-bottom'>Plus aucun résultat !</button>";
        }
		else{
			echo "<button class='btn btn-round btn-danger button-margin-bottom'>Aucune catégorie trouvé !</button>";
		}
    } else {
        $sql = "SELECT t1.Id_Categorie, t1.Libelle, t1.Id_Categorie_Parent, t2.Libelle AS Libelle_Parent 
				FROM t_d_categorie AS t1 LEFT JOIN t_d_categorie AS t2 ON t1.Id_Categorie_Parent = t2.Id_Categorie";
        $stmt = $conn->query($sql);

        if ($stmt->rowCount() > 0) {
			echo "
			<div class='row'>
				<div class='col-lg-12 col-md-12 col-sm-12 col-xs-12 col-xs-B-12'>
					<br><p class='espace-search'>{$stmt->rowCount()} catégorie(s) trouvé !</p>
				</div>
			</div>
			";
			echo "
				<div class='table-responsive'>
					<table class='table table-success table-striped table-hover'>
						<thead class='table-dark'>
							<tr>
								<th scope='col'>#</th>
								<th scope='col'>Libellé</th>
								<th scope='col'>Parent</th>
							</tr>
						</thead>
						<tbody>
			";
            while ($row = $stmt->fetch()) {				
				echo "
							<tr>
								<th scope='row'>{$row['Id_Categorie']}</th>
								<td>{$row['Libelle']}</td>
								<td>{$row['Libelle_Parent']}</td>
							</tr>
				";
            }
			echo "
						</tbody>
					</table>
				</div>
			";	
			echo "<button class='btn btn-round btn-danger button-margin-bottom'>Plus aucun résultat !</button>";
        }
		else{
			echo "<button class='btn btn-round btn-danger button-margin-bottom'>Aucune catégorie trouvé !</button>";
		}
    }
	?>
	</div>
	</div>
	</div>
</div>