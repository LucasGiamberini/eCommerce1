

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




document.addEventListener("DOMContentLoaded", function() {
    
    if (window.location.href.includes('home')) {
    const carousel = document.querySelector('.carousel');
    const prevBtn = carousel.querySelector('.prev');
    const nextBtn = carousel.querySelector('.next'); 
    const carouselInner = carousel.querySelector('.carousel-inner');
    const carouselItems = carousel.querySelectorAll('.carousel-item');

        var currentSlide = 0;
//var carouselItems = document.querySelectorAll('.carousel-item');
    let currentIndex = 0;
    let interval;

function nextSlide() {
  carouselItems[currentSlide].classList.remove('active');
  currentSlide = (currentSlide + 1) % carouselItems.length;
  carouselItems[currentSlide].classList.add('active');
}

function previousSlide() {
    carouselItems[currentSlide].classList.remove('active');
    currentSlide = (currentSlide- 1 +  carouselItems.length) % carouselItems.length;
    carouselItems[currentSlide].classList.add('active')
      
  }

 // Défilement automatique toutes les 3 secondes (ajustez selon vos besoins)

prevBtn.addEventListener('click', () => {
    previousSlide();
    resetInterval()
  });
  
  nextBtn.addEventListener('click', () => {
    if (currentIndex < carouselItems.length - 1) {
  
      nextSlide();
      resetInterval()
    }
  });
  
  function resetInterval() {
    clearInterval(intervalId); // Arrête l'intervalle actuel
    intervalId = setInterval(nextSlide, 4000); // Redémarre le défilement automatique
  }
  


  interval = setInterval(nextSlide, 4000);

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