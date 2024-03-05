

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
  
    


    if (window.location.href.includes('home')    ) {





 // changement de style du template lorsque l'on est sur home
        var template = document.getElementById('template');

        template.classList.remove('template');
        template.classList.add('templateHome');

    //animation nuage header//



    const topAreaHeight = 350; // Hauteur de la zone de passage de la souris en pixels
    const effectElement = document.createElement('div');

    effectElement.classList.add('top-area-effect');
    document.body.appendChild(effectElement);



    document.addEventListener('mousemove', function(event) {
        const y = event.pageY;

        if (y <= topAreaHeight) {
            console.log("enhaut")
            cloudLeft.classList.add('animate-cloudLeft');
            cloudRight.classList.add('animate-cloudRight');
            cloudLeft.classList.remove('reverse-cloudLeft');
            cloudRight.classList.remove('reverse-cloudRight');
        } else {
            console.log("enbas")
            cloudLeft.classList.add('reverse-cloudLeft');
            cloudRight.classList.add('reverse-cloudRight');

        }
    });






        //choix de la classe pour animation des nuages
        var currentPage = window.location.pathname;
    if (currentPage === '/home'|| currentPage === '/') {
      var element1 = document.getElementById('cloudLeft');
      var element2= document.getElementById('cloudRight')
   
      element1.classList.remove ( "cloudLeft");
      element2.classList.remove ( 'cloudRight');
        element1.classList.add ( "cloudLeft-home");
        element2.classList.add ( 'cloudRight-home');
    
    }
//carousel

    const carousel = document.querySelector('.carousel');
    const prevBtn = carousel.querySelector('.prev');
    const nextBtn = carousel.querySelector('.next'); 
    const carouselInner = carousel.querySelector('.carousel-inner');
    const carouselItems = carousel.querySelectorAll('.carousel-item');

        var currentSlide = 0;

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











    };});
    
   



    document.addEventListener("DOMContentLoaded", function() {
     
     
    if (window.location.href.includes('Show')) {
    
        
        //carousel



        const carousel = document.querySelector('.carouselShow');
        const prevBtn = carousel.querySelector('.previousShow');
        const nextBtn = carousel.querySelector('.nextShow'); 
        const carouselInner = carousel.querySelector('.carouselShow-inner');
        const carouselItems = carousel.querySelectorAll('.carouselShow-item');
       

      


        var currentSlide = 0;
    
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
    



      // zoom image
      const pictures= document.querySelectorAll('.pictureShowProduct');
      const modal = document.getElementById('modal');
    const modalPicture = document.getElementById('modalPicture');
    const closeBtn = document.getElementsByClassName('closeModal')[0];

  



      pictures.forEach(picture => {
        picture.addEventListener('click',()=>{
        
       
        modal.style.display = 'block';
        modalPicture.src = picture.src;
          });
      });



      closeBtn.addEventListener('click', () => {
        modal.style.display = 'none';
    });


     
       //bouton ajouter 
         
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



     
    



        }

    });