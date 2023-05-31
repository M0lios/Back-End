<header>
<nav class="navbar navbar-expand-lg fixed-top navbar-green" id="navbar">
  <div class="container-fluid">
    <div id="logo">
      <a class="navbar-brand" href="#"><img id="logo-first" src="styles/images/logo/gg.jpg" rel="gg logo" class="logo-title"></a>
	</div>
    <button class="navbar-toggler green navbar-new-border" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="true" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon toggler-icon-new-color"></span>
    </button>  
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav navbar-nav-scroll me-auto mb-2 mb-lg-0">
		<?php if($page == "index"): ?>
        <li class="nav-item active-li">
          <a class="nav-link color-green" href="#">Accueil</a>
		<?php else: ?>
        <li class="nav-item">
          <a class="nav-link color-green" href="index.php">Accueil</a>
		<?php endif; ?>
        </li>
		<?php if($page == "catalogue" || $page == "produit"): ?>
        <li class="nav-item active-li">
          <a class="nav-link color-green" href="#">Catalogue</a>
		<?php else: ?>
        <li class="nav-item">
          <a class="nav-link color-green" href="catalogue.php">Catalogue</a>
		<?php endif; ?>
        </li>
        <?php if($page == "panier"): ?>
        <li class="nav-item active-li"  id="modal_panier">
          <a class="nav-link color-green" type="button" href="#">
          <?php else: ?>
            <li class="nav-item">
          <a class="nav-link color-green" href="panier.php">
          <?php endif; ?>
		  Mon panier 
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="grey" width="30" height="25"><path d="M253.3 35.1c6.1-11.8 1.5-26.3-10.2-32.4s-26.3-1.5-32.4 10.2L117.6 192H32c-17.7 0-32 14.3-32 32s14.3 32 32 32L83.9 463.5C91 492 116.6 512 146 512H430c29.4 0 55-20 62.1-48.5L544 256c17.7 0 32-14.3 32-32s-14.3-32-32-32H458.4L365.3 12.9C359.2 1.2 344.7-3.4 332.9 2.7s-16.3 20.6-10.2 32.4L404.3 192H171.7L253.3 35.1zM192 304v96c0 8.8-7.2 16-16 16s-16-7.2-16-16V304c0-8.8 7.2-16 16-16s16 7.2 16 16zm96-16c8.8 0 16 7.2 16 16v96c0 8.8-7.2 16-16 16s-16-7.2-16-16V304c0-8.8 7.2-16 16-16zm128 16v96c0 8.8-7.2 16-16 16s-16-7.2-16-16V304c0-8.8 7.2-16 16-16s16 7.2 16 16z"/></svg>

<?php
$nb_type_prod = 0;

if (isset($_SESSION['panier'])) {    
		foreach ($_SESSION['panier'] as $productid => $quantity) {      
			$nb_type_prod = $nb_type_prod + (1*$quantity);
    }
}
?>      
      <span class="position-absolute badge rounded-pill bg-danger span-position-panier" id="bn_item_basket" value="<?php echo $nb_type_prod; ?>"><?php echo $nb_type_prod; ?></span>
</a>
        </li>
		<?php if($page == "commande" || $page == "commande_inf"): ?>
        <li class="nav-item active-li">
          <a class="nav-link color-green" href="#">Mes commandes</a>
		<?php else: ?>
        <li class="nav-item">
          <a class="nav-link color-green" href="commande.php">Mes commandes</a>
		<?php endif; ?>
        </li>
		<?php if($page == "ticket"): ?>
        <li class="nav-item active-li">
          <a class="nav-link color-green" href="#">Mes tickets</a>
		<?php else: ?>
        <li class="nav-item">
          <a class="nav-link color-green" href="ticket.php">Mes tickets</a>
		<?php endif; ?>
        </li>
    <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] > 1): ?>
	<?php if($page == "ajout-produit" || $page == "ajout-fournisseur" || $page == "fournisseur" || $page == "ajout-categorie" || $page == "categorie" || $page == "ajout-ticket"): ?>
	<li class="nav-item active-li dropdown">
	<?php else: ?>
	<li class="nav-item dropdown">
	<?php endif; ?>
          <a class="nav-link color-green dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            Ajout
          </a>
		<ul class="dropdown-menu background-black border-yellow">
		<?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 4): ?>
		<?php if($page == "ajout-ticket"): ?>
        <li class="active-li">
          <a class="dropdown-item color-green" href="#">Ticket</a>
		<?php else: ?>
        <li>
          <a class="dropdown-item color-green" href="ajout-ticket.php">Ticket</a>
		<?php endif; ?>
        </li>
		<?php endif; ?>
		<?php if($page == "ajout-produit"): ?>
        <li class="active-li">
          <a class="dropdown-item color-green" href="#">Produit</a>
		<?php else: ?>
        <li>
          <a class="dropdown-item color-green" href="ajout-produit.php">Produit</a>
		<?php endif; ?>
        </li>
		<?php if($page == "ajout-fournisseur" || $page == "fournisseur"): ?>
        <li class="active-li">
          <a class="dropdown-item color-green" href="#">Fournisseur</a>
		<?php else: ?>
        <li>
          <a class="dropdown-item color-green" href="ajout-fournisseur.php">Fournisseur</a>
		<?php endif; ?>
        </li>
		<?php if($page == "ajout-categorie" || $page == "categorie"): ?>
        <li class="active-li">
          <a class="dropdown-item color-green" href="#">Catégorie</a>
		<?php else: ?>
        <li>
          <a class="dropdown-item color-green" href="ajout-categorie.php">Catégorie</a>
		<?php endif; ?>
        </li>
	          </ul>
        </li>
    <?php endif; ?>
    <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] > 1): ?>
	<?php if($page == "voir-ticket"): ?>
	<li class="nav-item active-li dropdown">
	<?php else: ?>
	<li class="nav-item dropdown">
	<?php endif; ?>
          <a class="nav-link color-green dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
            Voir
          </a>
		<ul class="dropdown-menu background-black border-yellow">
		<?php if($page == "voir-ticket"): ?>
        <li class="active-li">
          <a class="dropdown-item color-green" href="#">Ticket</a>
		<?php else: ?>
        <li>
          <a class="dropdown-item color-green" href="voir-ticket.php">Ticket</a>
		<?php endif; ?>
        </li>
	          </ul>
        </li>
    <?php endif; ?>
      </ul>
	  <?php if (isset($_SESSION['user_id'])): ?>
      <ul class="d-flex">
        <a class="btn btn-outline-danger button-sizing" href="<?php echo $page; ?>.php?logout=true">Déconnexion</a>
      </ul>
	  <?php else: ?>
	  <?php if($page != "connexion"): ?>
      <ul class="d-flex">
        <a class="btn btn-outline-success button-sizing" href="connexion.php">Connexion</a>
      </ul>
	  <?php endif; ?>
	  <?php if($page != "inscription"): ?>
      <ul class="d-flex">
        <a class="btn btn-outline-danger button-sizing" href="inscription.php">Inscription</a>
      </ul>
	  <?php endif; ?>
	  <?php endif; ?>
    </div>
  </div>
</nav>
</header>
<div class="margin-after-header"></div>