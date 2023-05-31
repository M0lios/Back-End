<footer class="text-center text-lg-start bg-light text-muted">
  <section class="p-4 border-bottom">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 col-xs-B-12">
		  <div class="row">
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 col-xs-B-12 mx-auto text-center">
				<h6 class="text-uppercase fw-bold">
					Mentions légales
				</h6>
				<p>
					Blabla vive le jardinage.
				</p>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 col-xs-B-12 mx-auto text-center">
				<h6 class="text-uppercase fw-bold ">
					Mail
				</h6>
				<p>
					moi@afpa.com
				</p>
			</div>
		  </div>
        </div>
	</div>
  </section>
  <div class="text-center p-4" style="background-color: rgba(0, 0, 0, 0.05);">
    © <?php echo date('Y'); ?> Copyright:
    <a class="text-reset fw-bold" href="#">Damien Cauët</a>
  </div>
</footer>
<script src="styles/js/jquery.js"></script>
<script src="styles/js/bootstrap.js"></script>
<script src="styles/js/back.js"></script>

<?php if($page == "ajout-categorie"): ?>
<script src="styles/js/ajout-categorie/ajout-categorie.js"></script>
<?php endif; ?>
<?php if($page == "inscription"): ?>
<script src="styles/js/inscription/page_inscrip.js"></script>
<?php endif; ?>
<?php if($page == "produit"): ?>
<script src="styles/js/add_panier.js"></script>
<?php endif; ?>
<?php if($page == "panier"): ?>
<script src="styles/js/up_panier.js"></script>
<?php endif; ?>
<?php if($page == "ajout-ticket"): ?>
<script src="styles/js/select.js"></script>
<?php endif; ?>
</body>
</html>