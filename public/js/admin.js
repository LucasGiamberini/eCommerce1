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