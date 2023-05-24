// Add - panier
function MoinsItem(){
	var value_init = document.getElementById("number_item_add_panier_").value;
	if(value_init > 1){
		document.getElementById("number_item_add_panier_").value = parseInt(value_init - 1);
	    var value_init_ht = document.getElementById("number_item_add_prix_achat").value;
		document.getElementById("number_item_add_prix_quantity_panier_").value = CalculePrix(document.getElementById("number_item_add_panier_").value, value_init_ht);
	}
}
// Add + panier
function PlusItem(){
	var value_init = document.getElementById("number_item_add_panier_").value;
	if(value_init < 101){
		document.getElementById("number_item_add_panier_").value = (parseInt(value_init)+1);
	    var value_init_ht = document.getElementById("number_item_add_prix_achat").value;
		document.getElementById("number_item_add_prix_quantity_panier_").value = CalculePrix(document.getElementById("number_item_add_panier_").value, value_init_ht);
	}
}

//function pour calculer le prix (avec deux chiffre après la virgule)
function CalculePrix(quantity, prix){
	return Number.parseFloat(quantity*prix).toFixed(2);
}

//href='{$page}.php?id={$id_produit}&ajout=true'

// add panier
document.getElementById("button_add_product").addEventListener("click", function() {
	var value_init = document.getElementById("number_item_add_panier_").value;
	// Redirection vers une nouvelle URL
	window.location.href = window.location.href + "&ajout=true&quantity=" + value_init;
});