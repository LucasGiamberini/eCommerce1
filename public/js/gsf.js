

document.addEventListener("DOMContentLoaded", function() {

    if (window.location.href.includes('edit')) {
    
    document.getElementById("deleteProfile").addEventListener("click", function(event) {
       
        // Affiche la boîte de dialogue de confirmation
        var confirmation = confirm("Voulez-vous vraiment supprimer votre profil ? Cette action est irreversible et entrainera la supression de vos donnée");
        
        // Si l'utilisateur clique sur "OK" dans la boîte de dialogue de confirmation
        if (confirmation) {
            var deleteUrl = this.getAttribute("data-delete-url");
            // Redirige vers l'URL de suppression du profil
            window.location.href = deleteUrl;
        }
    });
   
    if (window.location.href.includes('edit')) {


    }


}});






document.addEventListener('DOMContentLoaded', (event) => {
    // Récupérer les boutons radio
 
// bouton ajouter
if (window.location.href.includes('Show')) {

    var tempo = 2000;
 
    setTimeout(function() {
        console.log('hello2')
    document.getElementById('increaseBtn').addEventListener('click', function() {
        var qttInput = document.getElementById('qtt');
        qttInput.value = parseInt(qttInput.value) + 1;
    });

    document.getElementById('decreaseBtn').addEventListener('click', function() {
        var qttInput = document.getElementById('qtt');
    
        if (parseInt(qttInput.value) > 1) {
            qttInput.value = parseInt(qttInput.value) - 1;
        }
    });
    })}



});