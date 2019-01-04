console.log('verfiy ');


function flash(type, message) {

	let icons = {
		'error': 'error_outline',
		'success': 'check_circle',
		'info': 'info'
	};

	var messageElem = document.createElement('span');
	messageElem.classList.add('message');
	messageElem.innerHTML = message;

	var iconElem = document.createElement('i');
	iconElem.classList.add('material-icons');
	iconElem.innerHTML = icons[type];

	var flashElem = document.createElement('div');
	flashElem.classList.add('flash-message');
	flashElem.classList.add(type);

	flashElem.appendChild(iconElem);
	flashElem.appendChild(messageElem);

	var flashBox = document.querySelector('[data-flash-messages]');

	flashBox.appendChild(flashElem);

	setTimeout(function() {
		flashElem.classList.add('visible');

		setTimeout(function() {
			flashElem.classList.remove('visible');

			setTimeout(function() {
				flashBox.removeChild(flashElem);
			}, 1000);
		}, 5000);
	}, 200);

}


function hidePhoneScreen() {

	$('.section-form__phone-verify').addClass('hidden-left');

}

function showCodeScreen() {

	$('.section-form__code').addClass('visible');

}


$(document).on('click', '[data-ajax-verify]', function (e) {

	e.preventDefault();

	var csrf = $('[data-csrf-field] input').val(),
		dialCode = $('input[name="dial_code"]').val(),
		oldDialCode = $('input[name="old_dial_code"]').val(),
		phone = $('input[name="phone"]').val(),
		oldPhone = $('input[name="old_phone"]').val();

	var data = {
		'dial-code': dialCode,
		'old-dial-code': oldDialCode,
		'phone': phone,
		'old-phone': oldPhone
	};



	$.ajax({
		url: 'verify-account',
		method: 'post',
		// dataType: "json",
        data: {
            _token: csrf,
            input: data
        },
		success: function (res) {

			console.log(res);

			if (res == 'invalid number') {
				flash('error', 'It appears that your phone number is incorrect.');
			}

			if (res == 'message sent') {
				flash('success', 'Message has been sent');

				hidePhoneScreen();
				showCodeScreen();

			}

		},
		error: function (res) {
			console.log(res);
		}
	});

});


$(document).on('click', '[data-ajax-verify-code]', function (e) {

	e.preventDefault();

	var csrf = $('[data-csrf-field] input').val(),
		authCode = $('input[name="authy_code"]').val();

	$.ajax({
		url: 'verify-account-code',
		method: 'post',
        data: {
            _token: csrf,
            authCode: authCode
        },
		success: function (res) {

			console.log(res);

			if (res == 'success') {
				$('.submit-form').submit();
			}

			if (res == 'code invalid') {
				flash('error', 'It appears that the code you intered was incorrect.');
			}

		}
	});

});
