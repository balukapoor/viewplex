console.log('running select');

var flagSource = $('[data-flag-src]').data('flag-src');

$(document).on('click', '[data-open-dial-code-menu]', function () {

	$('body').addClass('dial-code-menu-open');

});

$(document).on('click', '[data-close-dial-code-menu]', function (e) {

	$('body').removeClass('dial-code-menu-open');

});

function selectCode(thisToPass) {

	var countryCode = $(thisToPass).data('country-code'),
		dialCode = $(thisToPass).data('dial-value');

	$('[data-dial-code]').empty();
	$('[data-dial-code]').text(dialCode);

	$('.dial-code-dropdown__menu li').removeClass('selected');

	$(thisToPass).addClass('selected');

	$('[data-dial-code-flag]').attr('src', flagSource + '/' + countryCode + '-32.png');

	$('body').removeClass('dial-code-menu-open');

	$('[data-dial-code-hidden-input]').val(dialCode);

	console.log('country code selected: ', countryCode);

}

$(document).on('click', '.dial-code-dropdown__menu li', function () {

	var thisToPass = this;

	selectCode(thisToPass);

});


$(document).ready(function () {

	var countryCode = $('.dial-code-dropdown__menu li.selected').data('country-code'),
		dialCode = $('.dial-code-dropdown__menu li.selected').data('dial-value');

	$('[data-dial-code]').text(dialCode);

	$('[data-dial-code-flag]').attr('src', flagSource + '/' + countryCode + '-32.png');

	$('[data-dial-code-hidden-input]').val(dialCode);

});