const request = async (args, endpoint) => {
    const response = await fetch(ajaxObject.ajaxUrl + '/' + endpoint, {
        method: "post",
        headers: {
            "Accept": "application/json",
            "Content-Type": "application/json"
        }, 
        body: JSON.stringify(args)
    });

    const result = await response.json();
    return result; 
};

$(document).ready(function () {

    $("form").on("submit", function(e){
        e.preventDefault();
    });

    function addUser(){
        let addUserForm = $("#Add-user-form");
        addUserForm.on("submit", function(e){
            e.preventDefault();
            var args = {},
                nonce = $(this).find("input[name='_nonce']").val();

            let fields = addUserForm.find(".arg");
                fields.each(function () {
                    let item = $(this);
                    args[item.attr("name")] = $(item).val();
            }); 
            args["_nonce"] = nonce;
            request(args, "add-user").then(function (res) {
                if (res.status === 'OK') {
                    let message = "Lisäsit käyttäjän onnistuneesti";
                    console.log(message);
                    return message;
                }
            }); 

        });
    }

    function addProduct(){
        let addProductForm = $("#Add-product-form");
        addProductForm.on("submit", function(e){
            e.preventDefault();
            var args = {},
                nonce = $(this).find("input[name='_nonce']").val();

            let fields = addProductForm.find(".arg");
                fields.each(function () {
                    let item = $(this);
                    args[item.attr("name")] = $(item).val();
            }); 
            
            args["_nonce"] = nonce;

            request(args, "add-product").then(function (res) {
                if (res.status === 'OK') {
                    let message = "Lisäsit tuotteen onnistuneesti";
                    console.log(message);
                    return message;
                }
            }); 

        });
    }

    function apiSecret(){
        const store = {};
        // Inserts the jwt to the store object
        store.setJWT = function (data) {
        this.JWT = data;
        console.log(data);
        };


    }
    
    addUser();
    addProduct();

});  
 