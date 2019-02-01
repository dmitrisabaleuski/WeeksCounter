// Variable to hold request
var request;

// Bind to the submit event of our form
$("#accountManager").submit(function (event) {

    // Prevent default posting of form - put here to work in case of errors
    event.preventDefault();

    // Abort any pending request
    if (request) {
        request.abort();
    }
    // setup some local variables
    var $form = $(this);

    // Let's select and cache all the fields
    var $inputs = $form.find("input, select, button, textarea");

    // Serialize the data in the form
    var serializedData = $form.serialize();

    // Let's disable the inputs for the duration of the Ajax request.
    // Note: we disable elements AFTER the form data has been serialized.
    // Disabled form elements will not be serialized.
    $inputs.prop("accountManager", true);
    request = $.ajax({
        url: "/{user_role}/user/edit/{id}",
        type: "post",
        data: serializedData,
        success: function (data) {

            $(".result").html(data);
        },
        error: function () {
            alert('Not OKay');
        }
    });

});
