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
/******/ 	return __webpack_require__(__webpack_require__.s = 64);
/******/ })
/************************************************************************/
/******/ ({

/***/ 15:
/***/ (function(module, exports) {

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

/***/ }),

/***/ 64:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(15);


/***/ })

/******/ });