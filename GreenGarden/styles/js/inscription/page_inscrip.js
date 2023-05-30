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

var btn_parti = document.getElementById("particulier");
var btn_pro= document.getElementById("entreprise");
var soci = document.getElementById("block_nom_soci");

document.getElementById("particulier").addEventListener("click", function() {
    if (btn_parti.checked){
        soci.style.display = "none";
    }
});

document.getElementById("entreprise").addEventListener("click", function() {
    if (btn_pro.checked){
        soci.style.display = "block";
    }
});

document.getElementById("e_mail").addEventListener("keyup", function() {
	
  DeleteErrorMsg("e_mail_existe");
  var e_mail = document.getElementById("e_mail").value;
  console.log(e_mail);
  if(texte = file('reponse/e_mail_existe.php?e_mail='+ e_mail))
  {
    console.log(texte);
  	if(texte == "2" || texte == "0")
  	{
		DeleteErrorMsg("e_mail_existe");
  	}
	else{
		
    // Récupération de l'élément input
    var inputElementNom = document.getElementById("e_mail");
    
    // Création d'un élément div
    var spanElementErrorNom = document.createElement('div');
    spanElementErrorNom.setAttribute("class", "alert alert-danger padding-div-alert");
    spanElementErrorNom.setAttribute("id", "div_e_mail_existe");
    // Ajout d'un contenu au span
    spanElementErrorNom.textContent = "Le champ Email est invalide, l'Email existe déjà";
    
    // Insertion du span après l'input
    inputElementNom.insertAdjacentElement('afterend', spanElementErrorNom);
		
	}
  }
});

function checkForm(f) {
console.log("ok");
var count_error = 0;
var login = f.elements['login'].value;
var nom = f.elements['nom'].value;
var prenom = f.elements['prenom'].value;
var mail = f.elements['e_mail'].value;
var password = f.elements['password'].value;
var repeatpassword = f.elements['repeatpassword'].value;
var tel = f.elements['tel'].value;
//Le champ "login" doit comporter au moins 2 caractères.
if(login.length < 2){
	console.log("Le champ 'login' doit comporter au moins 2 caractères.");
	ShadowBox(f.elements['login'], "bad");
	count_error++;
	//On crée le message d'erreur
	CreateAlertSpan("login");
}
else{
	ShadowBox(f.elements['login'], "ok");
	// On supprime le msg si il existe
	DeleteErrorMsg("login");
}


//Le champ "Nom" doit comporter au moins 2 caractères.
if(nom.length < 2){
	console.log("Le champ 'Nom' doit comporter au moins 2 caractères.");
	ShadowBox(f.elements['nom'], "bad");
	count_error++;
	//On crée le message d'erreur
	CreateAlertSpan("nom");	
}
else{
	ShadowBox(f.elements['nom'], "ok");
	// On supprime le msg si il existe
	DeleteErrorMsg("nom");
}

//Le champ "Prénom" doit comporter au moins 2 caractères.
if(prenom.length < 2){
	console.log("Le champ 'Prénom' doit comporter au moins 2 caractères.");
	ShadowBox(f.elements['prenom'], "bad");
	count_error++;
	//On crée le message d'erreur
	CreateAlertSpan("prenom");	
}
else{
	ShadowBox(f.elements['prenom'], "ok");
	DeleteErrorMsg("prenom");
}

//Email
if(checkEmail(mail))
{
	console.log("email valide");
	ShadowBox(f.elements['e_mail'], "ok");
	DeleteErrorMsg("e_mail");
}
else{
	console.log("email invalide");
	ShadowBox(f.elements['e_mail'], "bad");
	count_error++;
	//On crée le message d'erreur
	CreateAlertSpan("e_mail");	
}

//Password
if(checkPassword(password))
{
	console.log("password valide");
	ShadowBox(f.elements['password'], "ok");
	DeleteErrorMsg("password");
}
else{
	console.log("password invalide");
	ShadowBox(f.elements['password'], "bad");
	count_error++;
	//On crée le message d'erreur
	CreateAlertSpan("password");	
}

//Contrôle si mdp est = a repeat mdp
if(password != repeatpassword || repeatpassword.length < 6){
	console.log("Le champ 'Repeat password' n'est pas égale au champ Password");
	ShadowBox(f.elements['repeatpassword'], "bad");
	count_error++;
	//On crée le message d'erreur
	CreateAlertSpan("repeatpassword");	
}
else{
	console.log("repeatpassword valide");
	ShadowBox(f.elements['repeatpassword'], "ok");
	DeleteErrorMsg("repeatpassword");
}


//Tel
if(checkTel(tel))
{
	console.log("tel valide");
	ShadowBox(f.elements['tel'], "ok");
	DeleteErrorMsg("tel");
}
else{
	console.log("tel invalide");
	ShadowBox(f.elements['tel'], "bad");
	count_error++;
	//On crée le message d'erreur
	CreateAlertSpan("tel");	
}

if (btn_pro.checked){
    var nom_soci=f.elements['nom_soci'].value;
    if(nom_soci.length < 2){
        console.log("Le champ 'Nom de la societe' doit comporter au moins 2 caractères.");
        ShadowBox(f.elements['nom_soci'], "bad");
        count_error++;
        //On crée le message d'erreur
        CreateAlertSpan("nom_soci");	
    }
    else{
        ShadowBox(f.elements['nom_soci'], "ok");
        // On supprime le msg si il existe
        DeleteErrorMsg("nom_soci");
    }
}


    if(count_error > 0){
        return false;
    }

}

//function pour le boxshadow sur les inputs
function ShadowBox(element_focus, status_focus){
	if(status_focus == "bad"){
		element_focus.style.boxShadow = "0 0 5px 1px red";
	}
	else{
		element_focus.style.boxShadow = "0 0 5px 1px green";
	}
}

//function CreateAlertSpan
function CreateAlertSpan(div_id) {
    // On supprime le msg si il existe
    DeleteErrorMsg(div_id);
    var message_alert;
    if(div_id == "login"){
        message_alert = "Le champ Login doit comporter au moins 2 lettres";
    }
    else if(div_id == "nom"){
        message_alert = "Le champ Nom doit comporter au moins 2 lettres";
    }
    else if(div_id == "prenom"){
        message_alert = "Le champ Prénom doit comporter au moins 2 lettres";
    }
    else if(div_id == "e_mail"){
        message_alert = "Le champ Email est invalide, il doit contenir au moins 5 lettres, un @, un '.'";
    }
    else if(div_id == "password"){
        message_alert = "Le champ Mot de passe est invalide, il doit contenir au moins 6 lettres, un chiffre, une majuscule, une minuscule et un caractère spécial";
    }
    else if(div_id == "repeatpassword"){
        message_alert = "Le champ Reapet Password n'est pas identique au champ Mot de passe";
    }
    else if(div_id == "tel"){
        message_alert = "Le champ Tel n'est pas valide";
    }
    else if(div_id == "nom_soci"){
        message_alert = "Le champ Nom de la Société doit comporter au moins 2 lettres";
    }
    else{
        message_alert = "Ce champ est invalide";
    }
        
    // Récupération de l'élément input
    var inputElementNom = document.getElementById(div_id);
    
    // Création d'un élément div
    var spanElementErrorNom = document.createElement('div');
    spanElementErrorNom.setAttribute("class", "alert alert-danger padding-div-alert");
    spanElementErrorNom.setAttribute("id", "div_"+div_id);
    // Ajout d'un contenu au span
    spanElementErrorNom.textContent = message_alert;
    
    // Insertion du span après l'input
    inputElementNom.insertAdjacentElement('afterend', spanElementErrorNom);
        
}

//function CreateAlertSpan
function CreateAlertSpanEmailExist(div_id) {
    // On supprime le msg si il existe
    DeleteErrorMsg(div_id);
    var message_alert;
    if(div_id == "e_mail"){
        message_alert = "Le champ Email est invalide, l'Email saissi existe déjà !";
    }
    else{
        message_alert = "Ce champ est invalide";
    }
        
    // Récupération de l'élément input
    var inputElementNom = document.getElementById(div_id);
    
    // Création d'un élément div
    var spanElementErrorNom = document.createElement('div');
    spanElementErrorNom.setAttribute("class", "alert alert-danger padding-div-alert");
    spanElementErrorNom.setAttribute("id", "div_"+div_id);
    // Ajout d'un contenu au span
    spanElementErrorNom.textContent = message_alert;
    
    // Insertion du span après l'input
    inputElementNom.insertAdjacentElement('afterend', spanElementErrorNom);
        
}
    
    //function delete message
    function DeleteErrorMsg(div_id){
        if(!!document.getElementById("div_"+div_id) == true){
            const element = document.getElementById("div_"+div_id);
            element.remove();
        }
    }

    // Function regex pour password
    function checkPassword(password) {
    // 6 cacratère minimum
    // 1 Maj
    // 1 Minuscule
    // 1 chiffre
    // 1 caractère spec
    /*
    ^ : Indique le début de la chaîne de caractères
    (?=.*[a-z]) : Vérifie qu'il y a au moins une lettre minuscule dans la chaîne
    (?=.*[A-Z]) : Vérifie qu'il y a au moins une lettre majuscule dans la chaîne
    (?=.*\d) : Vérifie qu'il y a au moins un chiffre dans la chaîne
    (?=.*[@$!%*?&]) : Vérifie qu'il y a au moins un caractère spécial dans la chaîne
    [A-Za-z\d@$!%*?&]{6,} : Correspond à une chaîne qui contient au moins 6 caractères et qui peut contenir des lettres (majuscules et minuscules), des chiffres et des caractères spéciaux
    $ : Indique la fin de la chaîne de caractères
    */
        var re = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,}$/;
        return re.test(password);
    }

    // Function regex pour l'email
    function checkEmail(email) {
	    var emailRegex = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	    return emailRegex.test(email);
    }


    // Function regex pour l'email
    function checkTel(tel) {
        var telRegex = /^(0|\+33\s?|0033\s?)[1-9](\s?\d{2}){4}$/;
	    return telRegex.test(tel);
    }