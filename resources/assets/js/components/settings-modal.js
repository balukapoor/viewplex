console.log('Working !!!');
function openModal() {
	$('body').addClass('settings-modal-open');
}


function closeModal() {
	$('body').removeClass('settings-modal-open');
}

function setupForm(link) {

	var action = link.data('action'),
		names = link.data('names'),
		niceNames = link.data('nice-names'),
		inputType = link.data('input-type');

	for (var i = 0; i < names.length; i++) {

		var input = '<input type="' + inputType + '" name="' + names[i] + '" required placeholder="' + niceNames[i] + '">',
			label = '<label>' + niceNames[i] + '</label>',
			wrap = '<div class="input-wrap">' + label + input + '</div>';

		$('.form-modal__input-area').append(wrap);

	}


	$('.form-modal__inner').attr('action', action);

	$('.form-modal__input-area').append('<button class="btn btn-primary small green">Save</button>');

}

function destroyForm() {

	// Factor animation time
	setTimeout(function() {
		$('.form-modal__input-area').empty();
	}, 500);

}


$(document).on('click', '[data-open-form-modal]', function () {

	openModal();

	setupForm($(this));

});

$(document).on('click', '[data-close-form-modal]', function () {

	closeModal();

	destroyForm();

});