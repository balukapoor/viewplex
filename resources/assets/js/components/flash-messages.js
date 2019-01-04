$(document).ready(function () {

	if ($('.flash-message').length > 0) {

		var timeIncrement = 0,
			loopCount = 0;

		$('.flash-message').each(function () {

			var thisToThat = $(this);

			setTimeout(function() {

				thisToThat.addClass('visible');

				setTimeout(function() {
					thisToThat.removeClass('visible');
				}, 5000);

			}, timeIncrement+=200);

			loopCount++;

		});

		var timeToClose = (5000 * loopCount) - (200 * loopCount) + 1000;

		$('.flash-message-box').addClass('visible');

		setTimeout(function() {
			$('.flash-message-box').removeClass('visible');
		}, timeToClose);



	}

});