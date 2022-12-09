/*var request;

$(".button").on(function(event){
    event.preventDefault();

    if(request){
        request.abort();
    }
    var $form = $(this);

    var $inputs = $form.find("input, select, button, textarea");
    var serializedData = $form.serialize();
    request = $.ajax({
        url: "/index.php",
        type: "post",
        data: serializedData
    });
    // Callback handler that will be called on success
    request.done(function (){
        // Log a message to the console
        console.log("Hooray, it worked!");
    });

    // Callback handler that will be called on failure
    request.fail(function (){
    
        // Log the error to the console
        console.error(
            "Error occuered"
        );
    });

    // Callback handler that will be called regardless
    // if the request failed or succeeded
    request.always(function () {
        // Reenable the inputs
        $inputs.prop("disabled", false);
    });
})*/
$(".button").on(function(){
function createAJAXRequestToPopulateList(category) {
    return $.ajax({ url: '../index.php',
                    data: {method: 'statusFilter',
                           category: category},
                    type: 'post'
    });
  }

function addActivityItem(){
    const selector = document.getElementById("categorySelector");
   const ajaxRequest = createAJAXRequestToPopulateList(selector.options[selector.selectedIndex].value);
    ajaxRequest.done(populateList);
}

function populateList(responseData) {
    console.log(responseData);
}
})
