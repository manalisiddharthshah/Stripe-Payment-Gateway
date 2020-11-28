<!DOCTYPE html>
<html>

<head>
	<title>
		Payment Form
	</title>
</head>

<body>
	<script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
	<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
	
	<div class="col-xs-12 col-md-4">
		<div class="panel panel-default">
			<div class="panel-body">
				<span class="paymentErrors alert-danger"></span>
				<form action="process.php" method="POST" id="paymentForm">
					<div class="form-group">
						<label for="name">Name</label>
						<input type="text" name="custName" class="form-control">
					</div>
					<div class="form-group">
						<label for="email">Email</label>
						<input type="email" name="custEmail" class="form-control">
					</div>
					<div class="form-group">
						<label>Card Number</label>
						<input type="text" name="cardNumber" size="20" autocomplete="off" id="cardNumber" class="form-control" />
					</div>
					<div class="row">
						<div class="col-xs-4">
							<div class="form-group">
								<label>CVC</label>
								<input type="text" name="cardCVC" size="4" autocomplete="off" id="cardCVC" class="form-control" />
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-10">
							<div class="form-group">
								<label>Expiration (MM/YYYY)</label>
								<div class="col-xs-5">
									<input type="text" name="cardExpMonth" placeholder="MM" size="2" id="cardExpMonth" class="form-control" />
								</div>
								<div class="col-xs-5">
									<input type="text" name="cardExpYear" placeholder="YY" size="4" id="cardExpYear" class="form-control" />
								</div>
							</div>
						</div>
					</div>

					<br>

					<div class="form-group">
						<input type="submit" id="makePayment" class="btn btn-success" value="Make Payment">
					</div>
				</form>
			</div>
		</div>
	</div>
</body>
<script>
	// set your stripe publishable key

	Stripe.setPublishableKey('pk_test_3QNl57QG4EpxFfy4d2HIUaQT0071zTdyzG');

	$(document).ready(function() {
		$("#paymentForm").submit(function(event) {
			$('#makePayment').attr("disabled", "disabled");

			// create stripe token to make payment

			Stripe.createToken({
				number: $('#cardNumber').val(),
				cvc: $('#cardCVC').val(),
				exp_month: $('#cardExpMonth').val(),
				exp_year: $('#cardExpYear').val()
			}, handleStripeResponse);
			return false;
		});
	});

	// handle the response from stripe

	function handleStripeResponse(status, response) {
		console.log(JSON.stringify(response));
		if (response.error) {
			$('#makePayment').removeAttr("disabled");
			$(".paymentErrors").html(response.error.message);
		} else {
			var payForm = $("#paymentForm");
			//get stripe token id from response
			var stripeToken = response['id'];
			//set the token into the form hidden input to make payment
			payForm.append("<input type='hidden' name='stripeToken' value='" + stripeToken + "' />");
			console.log(stripeToken);
			payForm.get(0).submit();
		}
	}
</script>

<!-- Test Card Details:
4242424242424242 Visa
5555555555554444 Mastercard
378282246310005 American Express
 -->

</html>