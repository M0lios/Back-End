// Récupérer la référence de la case à cocher et du div
var checkbox = document.getElementById('checkbox');
var div = document.getElementById('select_parent');

// Ajouter un écouteur d'événements sur le changement de la case à cocher
checkbox.addEventListener('change', function() {
  // Vérifier si la case à cocher est cochée
  if (checkbox.checked) {
    // Afficher le div
    $(div).hide();
  } else {
    // Cacher le div
    $(div).show();
  }
});