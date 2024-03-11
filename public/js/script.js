

document.addEventListener("DOMContentLoaded", function() {// attend que la page est completement charger avant l'execution du js

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
   
   

}});




document.addEventListener("DOMContentLoaded", function() {// attend que la page est completement charger avant l'execution du js
  
    



    if (window.location.href.includes('home')    ) {// si l'url contient le mot home

       //bouton ajouter - retirer quantité
         
     var qttInput = document.getElementById('qtt');// input du formulaire qui a fiche la quantité
     const incrBtn = document.getElementById('increaseBtn');
     const decrsBtn = document.getElementById('decreaseBtn');

     incrBtn.addEventListener('click', function() {
           
           qttInput.value = parseInt(qttInput.value) + 1;// parsetInt() permet de convertir une chaine de carractere en nombre entier
           // additionne  une unité a la quantité total du chiffre present dans le input
       });
   
       decrsBtn.addEventListener('click', function() {
           
       
           if (parseInt(qttInput.value) > 1) {// si la valeur est superieur a un
               qttInput.value = parseInt(qttInput.value) - 1;// alors on enleve une unité au chiffre dans la fenetre d'input
           }
       });








       // Requette Ajax avec jQuery pour favoris
       var productId = $(this).data('product-id') ;
       var favoriteBtn = document.querySelectorAll('a.addFavorite');

       $(favoriteBtn).click(function(event) {
       
        event.preventDefault();
       
        $.ajax({
          url:" /user/addfavorite/{id} ",
          method: 'POST',
          data: {id: productId},
          success: function(response) {
            console.log('Ajouté aux favoris avec succès !');
            // Gérer la réponse du serveur si nécessaire
        },
        error: function(xhr, status, error) {
            console.error('Erreur lors de l\'ajout aux favoris:', error);
            // Gérer les erreurs si nécessaire
        }
        })
       })


     







/////////////////////////////////////////////////////////////////////
 
        var template = document.getElementById('template');// changement de style du template lorsque l'on est sur home

        template.classList.remove('template');
        template.classList.add('templateHome');// changement de style pour la template lorque l'on est sur la homepage

    //animation nuage header//



    const topAreaHeight = 350; // Hauteur de la zone de passage de la souris en pixels
    const effectElement = document.createElement('div');// creation d'une div pour le passage de la souris

    effectElement.classList.add('top-area-effect');// ajout de la classe css a la div crée
    document.body.appendChild(effectElement);// sur l'element body(qui est la partie visible) de la page, on integre la div que l'on a crée 



    document.addEventListener('mousemove', function(event) {
        const y = event.pageY;// verifie le mouvement de la souris sur la hauteur de la page

        if (y <= topAreaHeight) {// si l'axe vertical de la souris est inferieure a la zone definis
            console.log("enhaut")
            cloudLeft.classList.add('animate-cloudLeft');// on lance les animation d'ouverture nuages
            cloudRight.classList.add('animate-cloudRight');
            cloudLeft.classList.remove('reverse-cloudLeft');//et on enleve  les animations d'ouverture des nuages
            cloudRight.classList.remove('reverse-cloudRight');
        } else {// si l'axe vertical est en dessous de la zone
            console.log("enbas")
            cloudLeft.classList.add('reverse-cloudLeft');// on lance la fermeture des nuages
            cloudRight.classList.add('reverse-cloudRight');
            cloudLeft.classList.remove('animate-cloudLeft');//et on enleve l'animation d'ouverture des nuages
            cloudRight.classList.remove('animate-cloudRight');
        }
    });






        //choix de la classe pour animation  et style des nuages des nuages
        
    
      var element1 = document.getElementById('cloudLeft');
      var element2= document.getElementById('cloudRight')
   
      element1.classList.remove ( "cloudLeft");
      element2.classList.remove ( 'cloudRight');
        element1.classList.add ( "cloudLeft-home");
        element2.classList.add ( 'cloudRight-home');
    
    
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
 
  
      nextSlide();
      resetInterval()
 
  });
  
  function resetInterval() {
    clearInterval(interval); // Arrête l'intervalle actuel
    interval = setInterval(nextSlide, 4000); // Redémarre le défilement automatique
  }
  


  interval = setInterval(nextSlide, 4000);











    };});
    
   



    document.addEventListener("DOMContentLoaded", function() {// attend que la page est completement charger avant l'execution du js
     
     
    if (window.location.href.includes('Show')) {// si l'url contient le mot show, alors le script qui suit s'execute
    
        
        //carousel



        const carousel = document.querySelector('.carouselShow');//selectionne la div avec la class carouselShow
        const prevBtn = carousel.querySelector('.previousShow');// selectionne le bouton qui a la class previousShow
        const nextBtn = carousel.querySelector('.nextShow'); 
        const carouselInner = carousel.querySelector('.carouselShow-inner');
        const carouselItems = carousel.querySelectorAll('.carouselShow-item');
       
        
      


        var currentSlide = 0;
    
        let currentIndex = 0;
        let interval;//demarre la variable interval qui passe a l'image suivante selon le temp definis
    
    function nextSlide() {
      carouselItems[currentSlide].classList.remove('active');// on enleve la classe active de la div de l'image en cour ou se situe la classe carouselShow-item
      currentSlide = (currentSlide + 1) % carouselItems.length;// je rajoute une valeur de 1 a la variable currentSlide  pour passer a l'image suivante, 
      //le modulo (%) avec carouselItems.lenght permet de ne pas depasser le nombre d'image presente sur la page, permettant de ne pas juste afficher rien 
      carouselItems[currentSlide].classList.add('active');// on ajoute la classe active a la nouvelle image qui a la classe carouselShow-item
    }
    
    function previousSlide() {
        carouselItems[currentSlide].classList.remove('active');// on enleve la classe active de la div de l'image en cour ou se situe la classe carouselShow-item
        currentSlide = (currentSlide- 1 +  carouselItems.length) % carouselItems.length;//j'enleve une valeur de 1 a la variable currentSlide  pour passer a l'image precedente,
         //currentSlide - 1 , soustrait de 1 la valeur de l'item ,  + carouselItems.length permet d'empecher que  la valeur soit negative, si par exemple le slide est a 0  , il revient a 2 lorsque le nombre total d'image est 3,
         // le modulo (%) avec carouselItems.length permet de boucler
        carouselItems[currentSlide].classList.add('active')// on ajoute la classe active a la nouvelle image qui a la classe carouselShow-item
          
      }
    

    




        
    
    prevBtn.addEventListener('click', () => {//lance les fonction suivante lorsque l'on appuie sur le bouton prevBtn
        previousSlide();
        resetInterval()
       
      });
      
      nextBtn.addEventListener('click', () => {//lance les fonction suivante lorsque l'on appuie sur le bouton nextBtn
        
          nextSlide();
          resetInterval()
       
     
      });
      
      function resetInterval() {
        clearInterval(interval); // Arrête l'intervalle actuel
        interval = setInterval(nextSlide, 4000); // Redémarre le défilement automatique
      }
      
    
    
      interval = setInterval(nextSlide, 4000);// definit l'interval lorsque l'on arrive sur la page
    



      // zoom image
      const pictures= document.querySelectorAll('.pictureShowProduct');
      const modal = document.getElementById('modal');// fenetre modal pour afficher l'image
    const modalPicture = document.getElementById('modalPicture');// image de la fenetre modal
    const closeBtn = document.getElementsByClassName('closeModal')[0];// bouton de fermeture de la fenetre modale

  



      pictures.forEach(picture => {// on parcours toute les images charger
        picture.addEventListener('click',()=>{// lorsque l'on clique sur une image bien precise 
        
       
        modal.style.display = 'block';// on fait apparaitre la fenetre modale
        modalPicture.src = picture.src;// on donne la source de l'image sur laquelle on a cliquer a la section image de la fenetre modale affiche l'image correspondante
          });
      });



      closeBtn.addEventListener('click', () => {// lorsque l'on clique sur le bouton de fermeture de la fenetre modal
        modal.style.display = 'none';// enleve la fenetre modale
    });


     
       //bouton ajouter 
         
       var qttInput = document.getElementById('qtt');// input du formulaire qui a fiche la quantité
      const incrBtn = document.getElementById('increaseBtn');
      const decrsBtn = document.getElementById('decreaseBtn');

      incrBtn.addEventListener('click', function() {
            
            qttInput.value = parseInt(qttInput.value) + 1;// parsetInt() permet de convertir une chaine de carractere en nombre entier
            // additionne  une unité a la quantité total du chiffre present dans le input
        });
    
        decrsBtn.addEventListener('click', function() {
            
        
            if (parseInt(qttInput.value) > 1) {// si la valeur est superieur a un
                qttInput.value = parseInt(qttInput.value) - 1;// alors on enleve une unité au chiffre dans la fenetre d'input
            }
        });



     
    



        }

    });