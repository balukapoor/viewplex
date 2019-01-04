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
/******/ 	return __webpack_require__(__webpack_require__.s = 65);
/******/ })
/************************************************************************/
/******/ ({

/***/ 16:
/***/ (function(module, exports) {

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
	setTimeout(function () {
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

/***/ }),

/***/ 65:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(16);


/***/ })

/******/ });