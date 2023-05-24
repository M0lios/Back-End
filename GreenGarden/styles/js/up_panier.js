function file(fichier)
{
if(window.XMLHttpRequest)
xhr_object = new XMLHttpRequest();
else if(window.ActiveXObject)
xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
else
return(false);
xhr_object.open("GET", fichier, false);
xhr_object.send(null);
if(xhr_object.readyState == 4) return(xhr_object.responseText);
else return(false);
}

// Add - panier
function MoinsItem(id){
	var value_init = document.getElementById("number_item_add_panier_"+id).value;
	if(value_init > 1){
		document.getElementById("number_item_add_panier_"+id).value = parseInt(value_init - 1);
	    var value_init_ht = document.getElementById("number_item_add_prix_achat_"+id).value;
		document.getElementById("number_item_add_prix_quantity_panier_"+id).value = CalculePrix(document.getElementById("number_item_add_panier_"+id).value, value_init_ht);
		document.getElementById('bn_item_basket').innerHTML -= 1;
		let quantity = document.getElementById("number_item_add_panier_"+id).value;
		
		let value_tot_panier = document.getElementById("total_prix_panier").innerHTML;
		let value_prix_achat = document.getElementById("number_item_add_prix_achat_"+id).value;
		document.getElementById("total_prix_panier").innerHTML = Number.parseFloat(value_tot_panier - value_prix_achat).toFixed(2);
		
		Update_item(id, quantity);
	}
}
// Add + panier
function PlusItem(id){
	var value_init = document.getElementById("number_item_add_panier_"+id).value;
	if(value_init < 101){
		document.getElementById("number_item_add_panier_"+id).value = (parseInt(value_init)+1);
	    var value_init_ht = document.getElementById("number_item_add_prix_achat_"+id).value;
		document.getElementById("number_item_add_prix_quantity_panier_"+id).value = CalculePrix(document.getElementById("number_item_add_panier_"+id).value, value_init_ht);
		let test = document.getElementById('bn_item_basket').innerHTML;
		document.getElementById('bn_item_basket').innerHTML = parseInt(test) + 1;
		let quantity = document.getElementById("number_item_add_panier_"+id).value;
		
		let value_tot_panier = document.getElementById("total_prix_panier").innerHTML;
		let value_prix_achat = document.getElementById("number_item_add_prix_achat_"+id).value;
		let total_prix_dans_panier = parseFloat(value_tot_panier) + parseFloat(value_prix_achat);
		document.getElementById("total_prix_panier").innerHTML = Number.parseFloat(total_prix_dans_panier).toFixed(2);

		Update_item(id, quantity);
	}
}

function Update_item(id, quantity){
  // Créez un objet XMLHttpRequest
  var xhr = new XMLHttpRequest();

  // Définissez la fonction de rappel qui sera exécutée lorsque la réponse sera reçue
  xhr.onreadystatechange = function() {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        // La requête s'est terminée avec succès
        // Vous pouvez effectuer des actions supplémentaires ici si nécessaire
      } else {
        // Il y a eu une erreur lors de la requête
        // Gérez l'erreur ici
      }
    }
  };

  xhr.open('GET', 'session/update_add_id_panier.php?id='+id+'&quantity='+quantity, true);
  xhr.send('');

  console.log("get ok");
}

//function pour calculer le prix (avec deux chiffre après la virgule)
function CalculePrix(quantity, prix){
	return Number.parseFloat(quantity*prix).toFixed(2);
}

function DeleteItemPanier(id){
  // Créez un objet XMLHttpRequest
  var xhr = new XMLHttpRequest();

  // Définissez la fonction de rappel qui sera exécutée lorsque la réponse sera reçue
  xhr.onreadystatechange = function() {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        // La requête s'est terminée avec succès
        // Vous pouvez effectuer des actions supplémentaires ici si nécessaire
      } else {
        // Il y a eu une erreur lors de la requête
        // Gérez l'erreur ici
      }
    }
  };

  xhr.open('GET', 'session/delete_id_panier.php?id='+id, true);
  xhr.send('');

  console.log("get ok");

  var value_init = document.getElementById("number_item_add_panier_"+id).value;
  let test = document.getElementById('bn_item_basket').innerHTML;
  document.getElementById('bn_item_basket').innerHTML = parseInt(test) - value_init;


  let value_tot_panier = document.getElementById("total_prix_panier").innerHTML;
  let value_prix_achat = document.getElementById("number_item_add_prix_quantity_panier_"+id).value;
  document.getElementById("total_prix_panier").innerHTML = Number.parseFloat(value_tot_panier - value_prix_achat).toFixed(2);

  // Vérifier si l'élément div existe
  if (!!document.getElementById("card_panier_"+id)) {
	// Supprimer la div du DOM
	document.getElementById("card_panier_"+id).remove();
  }

  // Vous pouvez également envoyer d'autres données supplémentaires si nécessaire

  if(texte = file('session/delete_id_panier.php?id='+ id))
  {
  	if(texte == "0")
  	{
		console.log("test ok");
		document.getElementById('all_info').innerHTML += "<div class='alert alert-warning' role='alert'>Votre panier est vide.</div>";
		document.getElementById('button_price_all').style.display = "none";
		document.getElementById('button_valider_panier').style.display = "none";
  	}
	else{
		console.log("test non ok");
	}
  }

}
