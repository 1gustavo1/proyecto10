function reportError(msg) {
	// Show the error in the form:
	$('#payment-errors').text(msg).addClass('alert alert-error');
	// re-enable the submit button:
	$('#submitBtn').prop('disabled', false);
	return false;}

$(document).ready(function() {
	
	// Watch for a form submission:
	$("#payment-form").submit(function(event) {

		
		// disable the submit button to prevent repeated clicks:
		$('#submitBtn').attr("disabled", "disabled");

		// Get the values:
		var ccNum = $('.card-number').val(), cvcNum = $('.card-cvc').val(), expMonth = $('.card-expiry-month').val(), expYear = $('.card-expiry-year').val(),nombre = $('.nombre').val();
		
		
		
			// Get the Stripe token:
			Stripe.createToken({
				number: ccNum,
				cvc: cvcNum,
				exp_month: expMonth,
				exp_year: expYear
			}, stripeResponseHandler);

			return false;

	}); 
}); // Function handles the Stripe response:
function stripeResponseHandler(status, response) {
	
	// Check for an error:
	if (response.error) {

		reportError(response.error.message);
		
	} else { // No errors, submit the form:

	  var f = $("#payment-form");

	  // Token contains id, last4, and card type:
	  var token = response.id;
	  	var price = 20;
	var nombre = $('.nombre').val()
	  // Insert the token into the form so it gets submitted to the server
f.append("<input type='hidden' name='stripeToken' value='" + token + "' />");
f.append("<input type='hidden' name='price' value='" + price + "' />");
f.append("<input type='hidden' name='nombre' value='" + nombre + "' />");
	
	  f.get(0).submit();




request.done(function(msg)
        {
            if (msg.result === 0)
            {
                // Customize this section to present a success message and display whatever
                // should be displayed to the user.
                alert("The credit card was charged successfully!");
            }
            else
            {
                // The card was NOT charged successfully, but we interfaced with Stripe
                // just fine. There's likely an issue with the user's credit card.
                // Customize this section to present an error explanation
                alert("The user's credit card failed.");
            }
        });



	}
	
} // End of stripeResponseHandler() function.



