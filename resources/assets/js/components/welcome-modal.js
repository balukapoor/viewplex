$(document).ready(function () {

	if ($('.welcome-modal').length > 0) {
		$('body').addClass('welcome-modal-open');
	}

});

$(document).on('click', '[data-close-welcome-modal]', function () {

	$('body').removeClass('welcome-modal-open');

});