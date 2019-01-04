/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId])
/******/ 			return installedModules[moduleId].exports;
/******/
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// identity function for calling harmony imports with the correct context
/******/ 	__webpack_require__.i = function(value) { return value; };
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 66);
/******/ })
/************************************************************************/
/******/ ({

/***/ 17:
/***/ (function(module, exports) {

console.log('verfiy ');

function flash(type, message) {

	var icons = {
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

	setTimeout(function () {
		flashElem.classList.add('visible');

		setTimeout(function () {
			flashElem.classList.remove('visible');

			setTimeout(function () {
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
		success: function success(res) {

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
		error: function error(res) {
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
		success: function success(res) {

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

/***/ }),

/***/ 66:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(17);


/***/ })

/******/ });