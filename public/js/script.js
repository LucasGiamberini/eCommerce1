document.addEventListener("DOMContentLoaded", function() {// attend que la page est completement charger avant l'execution du js



/////////////////////////////////////////barre de recherche///////////////////////////////////////
         
const  searchBarIcon = document.getElementById("searchBarIcon");
const  searchBar = document.getElementById("searchBarForm");
const  searchBarInput = document.getElementById("textSearch");


searchBarIcon.onclick = function(){
  searchBarIcon.style.visibility = "hidden";
  searchBar.style.display = "block";
  searchBarInput.focus();
}




//////////////////////////////menu burger ///////////////////////////////
const burgerButton = document.getElementById("hamburger-menu");

burgerButton.addEventListener('click',function(event){
  event.stopPropagation();
 console.log(burgerButton.className);
 
 if (burgerButton.className === "bi bi-list fs-2 white"){
console.log(burgerButton)
burgerButton.classList.remove('bi','bi-list', 'fs-2', 'white');
    burgerButton.classList.add('bi', 'bi-x-lg' ,'fs-2' ,'white');

  }
  else  {
    console.log("hey2")
    burgerButton.classList.remove('bi','bi-x-lg','fs-2','white');
    burgerButton.classList.add('bi', 'bi-list', 'fs-2', 'white');
    

  }

}
);









// bouton pour ajuster la quantité de produit
  function buttonQuantity(){
     
    
    const quantityButtons = document.querySelectorAll('.qttButton');// recupere tout les boutons
//console.log(quantityButtons)
    quantityButtons.forEach((button) => {//parcour tout les boutons quantité
     
      if (!button.hasEventListener) { 
      button.addEventListener('click', (event) => {// lorsque l'on clique sur l'un des bouton avec la classe qttButton
      
       
      
        const buttonId = button.dataset.id;
        console.log(buttonId);
              
              const action = event.target.dataset.action;// Récupére l'action à effectuer (plus ou minus) à partir de l'attribut data-action
              const qttInput =  event.target.parentElement.querySelector('#qtt' );// recupere le parent  de l'element avec la class qtt ( qui est l'input avec la quantité de produit)
   


        console.log(qttInput.value);
        
        if (action === 'plus'){// si la valeur dans la constante est egale a plus
          qttInput.value = parseInt(qttInput.value) + 1;// parsetInt() permet de convertir une chaine de carractere en nombre entier
          // additionne  une unité a la quantité total du chiffre present dans le input

          }
     
          
          else if(action === 'minus'){// si la valeur dans la constante est egale a plus
          if (parseInt(qttInput.value) > 1) {// si la valeur est superieur a un
              qttInput.value = parseInt(qttInput.value) - 1;// alors on enleve une unité au chiffre dans la fenetre d'input
          }}


        })
      }    })
  
  }


  //fonction pour ajouter au favoris 
  function favorite(){
    const favoriteBtn = document.querySelectorAll('a.addFavorite');// selection toute les balises <a> avec la classe addFavorite

$(favoriteBtn).click(function(event) {// lorsque l'on clique sur le bouton de favoris
//$ est un raccourcis pour jQuery
// event est un objet passer a la fonction lorsque le bouton est cliquer
const productId = $(this).data('product-id');// ce sont les donnée transmise dans data-product-id(voir ligne 56 ) 
const favoriteIcon = $(event.currentTarget).find('i#favoriteIcon' + productId);// pour trouver l'id correspondant sur le document
const classfavoriteIcon = favoriteIcon.attr('class');// pour recueillir la class de favorite icon
console.log(favoriteIcon);

event.preventDefault();// fonction pour ne pas executer la commande par defaut lorque l'on clique sur la balise a

if (classfavoriteIcon === "bi-heart fs-2 favoriteIconNavigation") {// si la classe presente est l'icone avec un coeur vide
 
 $.ajax({//appelle de la fonction ajax de jQuery
   
   url: "/user/addfavorite/" + productId,//pour executer l'action avec cette url  et en transmettant l'id du produit
   method: 'POST',// pour envoyer une requette http "Post"
   contentType: "application/json; charset=utf-8",// les donnée au serveur sont de type json et l'encodage des caractère sont de type utf8
   data: {id: productId},// les données qui seront envoyer
   success: function(response) {// lorsque la requete est un succès
     console.log('Ajouté aux favoris avec succès !');
     favoriteIcon.removeClass('bi-heart fs-2 favoriteIconNavigation');// on enleve la classe avec l'icon du coeur vide(favoriteIconNavigation est la classe pour la position et la couleur de l'icone)
     favoriteIcon.addClass('bi-heart-fill fs-2 favoriteIconNavigation');// et on le remplace avec l'icone coeur plein
   },
   error: function (xhr, status, error) {// lorqu'une erreur se produit, 
     console.error('Erreur lors de l\'ajout aux favoris:', error);//on affiche les erreur dans
   }
 });
} 
else {// si l'icone est un coeur plein
 
 $.ajax({
   url: "/user/removeFavorite/" + productId,// on utilise la fonction pour enlever le produit des favoris
   method: 'POST',
   contentType: "application/json; charset=utf-8",
   data: {id: productId},
   success: function(response) {// lorsque le produit a été enlever des favoris
     console.log('Retiré des favoris avec succès !');
     favoriteIcon.removeClass('bi-heart-fill fs-2 favoriteIconNavigation');// on enleve l'icone avec le coeur plein
     favoriteIcon.addClass('bi-heart fs-2 favoriteIconNavigation');// et on le remplace par l'icone avec le coeur vide
   },
   error: function (xhr, status, error) {
     console.error('Erreur lors du retrait des favoris:', error);
   }
 });
}

});

}
  



 
 /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 //////////////////////////////// fenetre de requette ajax home/////////////////////////////////////////////////
 ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    
  if (window.location.href.includes("searchProductByCategory" )
 || window.location.href.includes("newProducts" )
 || window.location.href.includes("search" )
 //|| window.location.href.includes("showCategoryMenu" )
 ) 
 
 {
  
  buttonQuantity();
  favorite();//appelle de la fonction favorite





 //////////////////////////////////////////// requette ajax avec jQuery pour categorie/////////////////////////////////////



 const formCategory= $('form#filter');// on determine le formulaire avec l'id Filter
       
 const resultDiv = $('#ResultBoxHome');// on determine la div du resultat du filtre avec l'id ResultBoxHome

 event.preventDefault();
 
 function executeAjaxCategory(categoryId){
  $.ajax({
    url: "/category/showProductCategory/" + categoryId,//pour executer l'action avec cette url  et en transmettant l'id du produit
    method: 'POST',// pour envoyer une requette http "Post"
    contentType: "application/json; charset=utf-8",// les donnée au serveur sont de type json et l'encodage des caractère sont de type utf8
    data: {id: categoryId},// les données qui seront envoyer
    success: function(response) {
      // traitez la réponse du serveur et mettez à jour la page avec les résultats de la recherche
      resultDiv.html(response);
      
    },
  })
}
 

 document.querySelectorAll("#filter input").forEach(input =>{//selectionne tout les les id avec filter
 
  const categoryId =formCategory.find('input[type="radio"]:checked').val()  ;//releve la valeur du bouton du bouton radio qui a été cocher  
  executeAjaxCategory(categoryId);// execute la fonction qui fait la requete ajax




  
  input.addEventListener("change",(event) => {// lorsque l'on change de input, ici un bouton radio

    const categoryId =formCategory.find('input[type="radio"]:checked').val()  ;
    executeAjaxCategory(categoryId);
    event.preventDefault();//on empeche la redirection vers la page complete page
    
 
  })
 })











  }
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////menu category///////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  if (window.location.href.includes('showCategoryMenu') ) { 
 
    const formCategory= $('form#filter');// on determine le formulaire avec l'id Filter
const categoryId =formCategory.find('input[type="radio"]:checked').val()  ;//releve la valeur du bouton du bouton radio qui a été cocher  
// requette ajax pour les categorie


const resultDiv = $('#ResultBox');// on determine la div du resultat du filtre avec l'id ResultBoxHome


event.preventDefault();
      
   
        $.ajax({
          url: "/category/showCategory" ,//pour executer l'action avec cette url  
          method: 'POST',// pour envoyer une requette http "Post"
          contentType: "application/json; charset=utf-8",// les donnée au serveur sont de type json et l'encodage des caractère sont de type utf8
         
          success: function(response) {
            // traitez la réponse du serveur et mettez à jour la page avec les résultats de la recherche
            resultDiv.html(response);

            
            const formCategory= $('form#filter');// on determine le formulaire avec l'id Filter
       
            const resultDivAjax = $('#ResultBox');// on determine la div du resultat du filtre avec l'id ResultBoxHome
          //  const categorySelectorButton = $('#');
            
            function executeAjaxCategory(categoryId){
              


             if (categoryId === undefined ){// si aucune category n'est selectionner, on lance la fonction pour voir tout les produit
              $.ajax({
                url: "/category/showAllProduct" ,//pour executer l'action avec cette url  et en transmettant l'id du produit
                method: 'POST',// pour envoyer une requette http "Post"
                contentType: "application/json; charset=utf-8",// les donnée au serveur sont de type json et l'encodage des caractère sont de type utf8
                success: function(response) {
                  // traitez la réponse du serveur et mettez à jour la page avec les résultats de la recherche
                  resultDivAjax.html(response);
                  buttonQuantity();
                 favorite();
                }})
             }
             else{// quand le nom d'une categorie est selection
              $.ajax({
               url: "/category/searchProductByCategory/" + categoryId,//pour executer l'action avec cette url  et en transmettant l'id du produit
               method: 'POST',// pour envoyer une requette http "Post"
               contentType: "application/json; charset=utf-8",// les donnée au serveur sont de type json et l'encodage des caractère sont de type utf8
               data: {id: categoryId},// les données qui seront envoyer
               success: function(response) {
                 // traitez la réponse du serveur et mettez à jour la page avec les résultats de la recherche
                 resultDivAjax.html(response);
                 buttonQuantity();
                 favorite();
               },
             })
            }
           }





           let olderLabelId = null; // variable pour stocker l'ancien label sélectionné
           
            document.querySelectorAll("#filter input").forEach(input =>{//selectionne tout les les id avec filter
         
             const categoryId =formCategory.find('input[type="radio"]:checked').val()  ;//releve la valeur du bouton du bouton radio qui a été cocher  
             executeAjaxCategory(categoryId);// execute la fonction qui fait la requete ajax
        
             const labelName = "label"+ categoryId;// on donne le nom de  l'id + l'id de la categorie ;
        
             const labelNameId = $("#"+labelName);
             
            
           
            
             labelNameId.addClass("filterCategoryActive");
           
             

             if (olderLabelId !== null) {
              olderLabelId.removeClass("filterCategoryActive");
            }
           // mettre à jour l'ancien label sélectionné

             
       
             
             input.addEventListener("change",(event) => {// lorsque l'on change de input, ici un bouton radio
         
               const categoryId =formCategory.find('input[type="radio"]:checked').val()  ;
               const labelName = "label"+ categoryId;
         
               const labelNameId = $("#"+labelName);
               console.log(olderLabelId);
               labelNameId.addClass("filterCategoryActive");
   
           
             if (olderLabelId !== null) {
              olderLabelId.removeClass("filterCategoryActive");
            }
             
           //  console.log(olderLabelId);
               executeAjaxCategory(categoryId);
               event.preventDefault();//on empeche la redirection vers la page complete page
          
               olderLabelId = labelNameId;

             })
            
            })
        
        



            
          },
        })
      




  }

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////script home//////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    if (window.location.href.includes('home')    ) {// si l'url contient le mot home
    
      //afficher les nouveauté sur la page d'accueil


      const ButtonnewProduct = $('input[type="radio"]#newProduct');
      const categoryLabel = document.querySelector('label[for="category"]');
      const resultDivNew = $('#ResultBoxHomeNew');
      
      function executeAjaxNew(){
        event.preventDefault();// fonction pour ne pas executer la commande par defaut lorque l'on clique sur la balise a
      
        $.ajax({//appelle de la fonction ajax de jQuery  
          url: "/category/newProducts" ,//pour executer l'action avec cette url  
          method: 'POST',// pour envoyer une requette http "Post"
          contentType: "application/json; charset=utf-8",// les donnée au serveur sont de type json et l'encodage des caractère sont de type utf8
         
          success: function(response) {// lorsque la requete est un succès
            resultDivNew.html(response);
            favorite();
          buttonQuantity();
         
          },
          
        });
      }
 
 
 
 


      if (ButtonnewProduct.prop('checked') )  {
      
        executeAjaxNew()
       
      }



 /////////////////////// requette ajax avec jQuery pour categorie//////////////////////////////////////////////////////////

       const formCategory= $('form#filter');// on determine le formulaire avec l'id Filter
       
       const resultDiv = $('#ResultBoxHome');// on determine la div du resultat du filtre avec l'id ResultBoxHome

       const ButtonCategory = $('#category');

      
    

       if (ButtonCategory.prop('checked') )  {
        executeAjaxCategory() 
      }
       
       
       function executeAjaxCategory(){
       event.preventDefault();
     
   
        $.ajax({
          url: "/category/showCategory" ,//pour executer l'action avec cette url  
          method: 'POST',// pour envoyer une requette http "Post"
          contentType: "application/json; charset=utf-8",// les donnée au serveur sont de type json et l'encodage des caractère sont de type utf8
         
          success: function(response) {
            // traitez la réponse du serveur et mettez à jour la page avec les résultats de la recherche
            resultDiv.html(response);

            
            const formCategory= $('form#filter');// on determine le formulaire avec l'id Filter
       
            const resultDivAjax = $('#ResultBox');// on determine la div du resultat du filtre avec l'id ResultBoxHome
          //  const categorySelectorButton = $('#');
            
            function executeAjaxCategory(categoryId){
              


             if (categoryId === undefined ){// si aucune category n'est selectionner, on lance la fonction pour voir tout les produit
              $.ajax({
                url: "/category/showAllProductHome" ,//pour executer l'action avec cette url  et en transmettant l'id du produit
                method: 'POST',// pour envoyer une requette http "Post"
                contentType: "application/json; charset=utf-8",// les donnée au serveur sont de type json et l'encodage des caractère sont de type utf8
                success: function(response) {
                  // traitez la réponse du serveur et mettez à jour la page avec les résultats de la recherche
                  resultDivAjax.html(response);
                  buttonQuantity();
                  favorite();
                }})
             }
             else{// quand le nom d'une categorie est selection
              $.ajax({
               url: "/category/searchProductByCategoryHome/" + categoryId,//pour executer l'action avec cette url  et en transmettant l'id du produit
               method: 'POST',// pour envoyer une requette http "Post"
               contentType: "application/json; charset=utf-8",// les donnée au serveur sont de type json et l'encodage des caractère sont de type utf8
               data: {id: categoryId},// les données qui seront envoyer
               success: function(response) {
                 // traitez la réponse du serveur et mettez à jour la page avec les résultats de la recherche
                 resultDivAjax.html(response);
                 buttonQuantity();
                 favorite();
               },
             })
            }
           }





           let olderLabelId = null; // variable pour stocker l'ancien label sélectionné
           
            document.querySelectorAll("#filter input").forEach(input =>{//selectionne tout les les id avec filter
         
             const categoryId =formCategory.find('input[type="radio"]:checked').val()  ;//releve la valeur du bouton du bouton radio qui a été cocher  
             executeAjaxCategory(categoryId);// execute la fonction qui fait la requete ajax
        
             const labelName = "label"+ categoryId;// on donne le nom de  l'id + l'id de la categorie ;
        
             const labelNameId = $("#"+labelName);
             
            
           
            
             labelNameId.addClass("filterCategoryActive");
           
             

             if (olderLabelId !== null) {
              olderLabelId.removeClass("filterCategoryActive");
            }
           // mettre à jour l'ancien label sélectionné

             
       
             
             input.addEventListener("change",(event) => {// lorsque l'on change de input, ici un bouton radio
         
               const categoryId =formCategory.find('input[type="radio"]:checked').val()  ;
               const labelName = "label"+ categoryId;
         
               const labelNameId = $("#"+labelName);
              
               labelNameId.addClass("filterCategoryActive");
   
           
             if (olderLabelId !== null) {
              olderLabelId.removeClass("filterCategoryActive");
            }
             
           //  console.log(olderLabelId);
               executeAjaxCategory(categoryId);
               event.preventDefault();//on empeche la redirection vers la page complete page
          
               olderLabelId = labelNameId;

             })
            
            }) 
          },
        })
      



        
      
       document.querySelectorAll("#filter input").forEach(input =>{//selectionne tout les les id avec filter
       
        const categoryId =formCategory.find('input[type="radio"]:checked').val()  ;//releve la valeur du bouton du bouton radio qui a été cocher  
        executeAjaxCategory(categoryId);// execute la fonction qui fait la requete ajax




        
        input.addEventListener("change",(event) => {// lorsque l'on change de input, ici un bouton radio
      
          const categoryId =formCategory.find('input[type="radio"]:checked').val()  ;
          executeAjaxCategory(categoryId);
          event.preventDefault();//on empeche la redirection vers la page complete page
          
       
        })
       })

      }
    







/////////////////////////////// pour changer de style quand on est sur home   //////////////////////////////////////
 
        var template = document.getElementById('template');// changement de style du template lorsque l'on est sur home

        template.classList.remove('template');
        template.classList.add('templateHome');// changement de style pour la template lorque l'on est sur la homepage



///////////////////////////////////////////////animation nuage header///////////////////////////////////////////////////


      const header = document.getElementById("header");// selectionne le header
      const logo = document.getElementById('logo');// selectionne le logo dans le header
    const topAreaHeight = 350; // Hauteur de la zone de passage de la souris en pixels
    const effectElement = document.createElement('div');// creation d'une div pour le passage de la souris

    effectElement.classList.add('top-area-effect');// ajout de la classe css a la div crée
    document.body.appendChild(effectElement);// sur l'element body(qui est la partie visible) de la page, on integre la div que l'on a crée 



    document.addEventListener('mousemove', function(event) {
        const y = event.pageY;// verifie le mouvement de la souris sur la hauteur de la page

        if (y <= topAreaHeight) {// si l'axe vertical de la souris est inferieure a la zone definis
            console.log("enhaut")
            logo.classList.add('logoAnimate');
            header.classList.add('headerAnimation');
            cloudLeft.classList.add('animate-cloudLeft');// on lance les animation d'ouverture nuages
            cloudRight.classList.add('animate-cloudRight');
            cloudLeft.classList.remove('reverse-cloudLeft');//et on enleve  les animations d'ouverture des nuages
            cloudRight.classList.remove('reverse-cloudRight');
        } else {// si l'axe vertical est en dessous de la zone
            console.log("enbas")
            logo.classList.remove('logoAnimate');
            header.classList.remove('headerAnimation');
            cloudLeft.classList.add('reverse-cloudLeft');// on lance la fermeture des nuages
            cloudRight.classList.add('reverse-cloudRight');
            cloudLeft.classList.remove('animate-cloudLeft');//et on enleve l'animation d'ouverture des nuages
            cloudRight.classList.remove('animate-cloudRight');
        }
    });






//////////////////////////////////choix de la classe pour animation  et style des nuages des nuages////////////////////////////////////////////
        
    
      var element1 = document.getElementById('cloudLeft');
      var element2= document.getElementById('cloudRight')
   
      element1.classList.remove ( "cloudLeft");
      element2.classList.remove ( 'cloudRight');
        element1.classList.add ( "cloudLeft-home");
        element2.classList.add ( 'cloudRight-home');
    
    
//////////////////////////////////////////////////////////carousel///////////////////////////////////////////////////////////////////////

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
    
   
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////// show product/////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    document.addEventListener("DOMContentLoaded", function() {// attend que la page est completement charger avant l'execution du js
     
     
    if (window.location.href.includes('Show')) {// si l'url contient le mot show, alors le script qui suit s'execute
    
        
 ////////////////////////////////////////////////////////carousel/////////////////////////////////////////////////////////////////////////



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
    



  //////////////////////////////////////////// zoom image////////////////////////////////////////////
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





///////////////////////////////////// Requette Ajax avec jQuery pour favoris Show/////////////////////////////////////////////////
       
           const favoriteBtn = document.querySelectorAll('a.addFavorite');
           
           
          
           $(favoriteBtn).click(function(event) {
            event.preventDefault();
           
           
            const productId = $(this).data('product-id');
            const favoriteIcon = $(event.currentTarget).find('i#favoriteIcon' + productId);
            var classfavoriteIcon = favoriteIcon.attr('class');
             console.log(classfavoriteIcon );
           
            
           
             if (classfavoriteIcon === "bi-heart fs-2 favoriteIconShow") {
              
             //  console.log('ajouter')
              $.ajax({
                 
                 url: "/user/addfavorite/" + productId,
                 method: 'POST',
                 contentType: "application/json; charset=utf-8",
                 data: {id: productId},
                 success: function(response) {
                   console.log('Ajouté aux favoris avec succès !');
                   favoriteIcon.removeClass('bi-heart fs-2 favoriteIconShow');
                   favoriteIcon.addClass('bi-heart-fill fs-2 favoriteIconShow');
                 },
                 error: function (xhr, status, error) {
                   console.error('Erreur lors de l\'ajout aux favoris:', error);
                 }
               });
             }// bi-heart-fill fs-2 favoriteIconShow
             else if (classfavoriteIcon === "bi-heart-fill fs-2 favoriteIconShow") {
              
               $.ajax({
                 url: "/user/removeFavorite/" + productId,
                 method: 'POST',
                 contentType: "application/json; charset=utf-8",
                 data: {id: productId},
                 success: function(response) {
                   console.log('Retiré des favoris avec succès !');
                   favoriteIcon.removeClass('bi-heart-fill fs-2 favoriteIconShow');
                   favoriteIcon.addClass('bi-heart fs-2 favoriteIconShow');
                 },
                 error: function (xhr, status, error) {
                   console.error('Erreur lors du retrait des favoris:', error);
                 }
               });
             }
           
           });
           
     
//////////////////////////////bouton ajouter/////////////////////////////////////////////////////////////////////////// 
         
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