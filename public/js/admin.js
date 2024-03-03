if (window.location.href.includes('edit') || window.location.href.includes('new')) {
    console.log('hello')
    document.addEventListener('DOMContentLoaded', (event) => {
    // ajouter produit dans la bdd
        var productTypeRadios = document.querySelectorAll('select[id="add_product_ProductType"]');
    
       
    
        document.getElementById('add_product_Consumable').style.display = 'none';
        document.getElementById('add_product_Aromiser').style.display = 'none';
        document.getElementById('add_product_Box').style.display = 'none';
        // Ajouter un écouteur d'événements 'change' à chaque bouton radio
        productTypeRadios.forEach(function(radio) {
            radio.addEventListener('change', function(event) {
                // Récupérer la valeur du bouton radio sélectionné
                var selectedValue = event.target.value;
                console.log(selectedValue);
                // Cacher tous les formulaires spécifiques
                document.getElementById('add_product_Aromiser').style.display = 'none';
                document.getElementById('add_product_Consumable').style.display = 'none';
                document.getElementById('add_product_Box').style.display = 'none';
        //        document.getElementById('aromiserForm').style.display = 'none';
          //      document.getElementById('consumableForm').style.display = 'none';
                
                // Afficher le formulaire spécifique correspondant à la valeur sélectionnée
                if (selectedValue === 'option1') {
                    document.getElementById('add_product_Box').style.display = 'block';
                } else if (selectedValue === 'option2') {
                    document.getElementById('add_product_Aromiser').style.display = 'block';
                } else if (selectedValue === 'option3') {
                    document.getElementById('add_product_Consumable').style.display = 'block';
                }
            });
        });
    })
    
    }

    // header




const cloudLeft = document.getElementById("cloudLeft");
const cloudRight = document.getElementById("cloudRight");

// Fonction pour démarrer l'animation cloud1
function startCloud1Animation() {
  cloudLeft.style.animation = "cloud1 2s  forwards";
}

// Fonction pour démarrer l'animation cloud2
function startCloud2Animation() {
  cloudRight.style.animation = "cloud2 2s forwards";
}



// Fonction pour réinitialiser l'animation cloud1
function resetCloud1Animation() {
  cloudLeft.style.animation = "cloud1 2s  backwards";
}

// Fonction pour réinitialiser l'animation cloud2
function resetCloud2Animation() {
  cloudRight.style.animation = "cloud2 2s backwards";
}

// Événement de défilement de la souris
document.addEventListener("mousemove", function(event) {
  // Position verticale du curseur
  const mouseY = event.clientY;
  // Hauteur à laquelle vous voulez déclencher l'animation
 const triggerHeight = 300 ; // Vous pouvez ajuster cette valeur selon vos besoins

// Si la position du curseur dépasse la hauteur de déclenchement
if (mouseY > triggerHeight) {
  startCloud1Animation();
  startCloud2Animation();
}
});

// Événement quand la souris quitte la zone de déclenchement
document.addEventListener("mouseleave", function(event) {
// Réinitialiser les animations
resetCloud1Animation();
resetCloud2Animation();
});
