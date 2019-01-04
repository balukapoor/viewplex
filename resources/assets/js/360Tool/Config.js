/**
 * Config vars in here.
 */

let options = {

	// Dev debug
	'debug': true,

	// Valid file types to be used by validator
	'validImages': {
		'fileTypes': [
			'image/jpeg',
			'image/png'
		],
		'resMinWidth': 2000,
		'resMinHeight': 2000,
		'maxSizeInBytes': 10485760 // 10mb
	}
}

module.exports = options;