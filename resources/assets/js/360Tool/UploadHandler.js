/**
 * Holds all the methods to do with uploading images, validating them,
 * making them into 'Room' objects and passing them through to other classes.
 */

export default class UploadHandler {

	constructor(App) {
		this.App = App;
		this.Objects = this.App.Objects;
		this.Rooms = this.App.Rooms;
	}

	/**
	 * This method is called on click of the first upload batch.
	 * It validates and makes objects of all the images.
	 */
	initialUploadSequence(images) {

  		if (images.length) {

  			this.App.Debug.info('Processing Initial Uploads');

			this.App.Instances.UiController.showProgressUploadBar(4)
			this.App.Instances.UiController.incrementProgressUploadBar();

  			// 1). Validate file type before proceeding. Assume true to start.
  			let validFileTypes = true;

  			for (var i = 0; i < images.length; i++) {

  				var isValidFileType = this.validateFileType(images[i]);

  				if (isValidFileType) {
  					continue;
  				} else {
  					validFileTypes = false;
  					break;
  				}

  			}

  			if (!validFileTypes) {
  				this.App.Debug.warn('One or more Invalid file types uploaded.');
  				this.App.Instances.UiController.flash('error', 'One or more Invalid file types uploaded.');
  				this.App.Instances.UiController.hideProgressUploadBar();
  				return;
  			}
  			this.App.Instances.UiController.incrementProgressUploadBar();

  			// 2). Validate file size before proceeding. Assume true to start.
  			let validFileSizes = true;

  			for (var i = 0; i < images.length; i++) {

  				var isValidFileSize = this.validateFileSize(images[i]);

  				if (isValidFileSize) {
  					continue;
  				} else {
  					validFileSizes = false;
  					break;
  				}

  			}

  			if (!validFileSizes) {
  				this.App.Debug.warn('One or more images exceed the maximum file size.');
  				this.App.Instances.UiController.flash('error', 'One or more images exceed the maximum file size.');
  				this.App.Instances.UiController.hideProgressUploadBar();
  				return;
  			}
  			this.App.Instances.UiController.incrementProgressUploadBar();

  			// 3). Generate image objects. This must be run inside of a promise function
  			// as the image genererated can only be accessed in the onload function.
  			// JS will continue with the loop before the images may have been made.
  			// So we needed a way to pause the execution whilst the images are being made.
  			// We use the promises "then" function to run all code after the execution.

  			let promise;
  			let imageBatch = [];

  			promise = new Promise(function(resolve, reject) {

	  			for (var i = 0; i < images.length; i++) {

	  				var readerPromise = new Promise(function(resolve, reject) {

	  					var fileReader = new FileReader();

		  				fileReader.onload = function (e) {

			  				var image = new Image;

			        		image.onload = function() {

			        			imageBatch.push(image);
			        			resolve(image);

				        	};
				        	image.src = fileReader.result;

				  		}

				  		fileReader.readAsDataURL(images[i]);

	  				}).then(function(result) {
	  					if (imageBatch.length == images.length) {
			        		resolve(imageBatch);
			        	}
	  				});

				}

			})
			.then(function(result) {

				// Run loop to validate all images dimensions. This will loop through the newly
				// created images. This has to be done afterwards as the uploaded files don't provide
				// Image dimensions until an HTML image has been made.
				let renderedImages = result;
				let validDimensions = true;

				for (var i = 0; i < renderedImages.length; i++) {

					var isValidDimensions = this.validateImageDimensions(renderedImages[i]);

	  				if (isValidDimensions) {
	  					continue;
	  				} else {
	  					validDimensions = false;
	  					break;
	  				}

				}

				if (!validDimensions) {
	  				this.App.Debug.warn('One or more files have invalid dimensions.');
	  				this.App.Instances.UiController.flash('error', 'One or more files have invalid dimensions.');
	  				this.App.Instances.UiController.hideProgressUploadBar();
	  				return;
	  			}
	  			this.App.Instances.UiController.incrementProgressUploadBar();

	  			// Timeout to stop it jerking into the animations
	  			let thisToPass = this;
	  			setTimeout(function() {

	  				thisToPass.App.Instances.UiController.displayUploadsSidebar();
		  			thisToPass.App.Instances.UiController.hideInitialUi();
				  	thisToPass.App.Instances.UiController.showAppLoader();
				  	thisToPass.App.Instances.UiController.removeProgressBar();

		  			for (var i = 0; i < renderedImages.length; i++) {

		  				var thumbnail = thisToPass.generateRoomThumbnail(renderedImages[i]);

		  				// Instanciate new Image objects and pass them through to AppContainers
				  		// Rooms object for global reference
				  		var Room = new thisToPass.Objects.Room(thisToPass.App, renderedImages[i], thumbnail);

				  		Room.id = i;
				  		Room.name = 'Room ' + (i + 1);

				  		thisToPass.Rooms[i] = Room;

				  		thisToPass.App.Instances.UiController.addRoomToSidebar(Room);

		  				if (i === 0) {
		  					// Make canvas, on constructor, load in first image from rooms object.
		  					var Canvas = new thisToPass.Objects.Canvas(thisToPass.App);
		  					thisToPass.App.Instances.Canvas = Canvas;
		  					thisToPass.App.Instances.UiController.showToolbar();
		  				}

			  		}
	  			}, 1000);

			}.bind(this));


  		} else {
  			this.App.Debug.warn('Images input empty.');
  			this.App.Instances.UiController.flash('error', 'Please select an image..');
  		}

	}

	uploadToolUpload(image) {

		this.App.Debug.info('New upload started');

		if (image.length) {

  			this.App.Debug.log('Processing Upload');

  			image = image[0];

			// this.App.Instances.UiController.showProgressUploadBar(4)
			// this.App.Instances.UiController.incrementProgressUploadBar();


  			// 1). Validate file type before proceeding. Assume true to start.
  			let isValidFileType = this.validateFileType(image);

			if (!isValidFileType) {
				this.App.Debug.warn('Invalid file type uploaded.');
  				this.App.Instances.UiController.flash('error', 'Invalid file type uploaded.');
  				// this.App.Instances.UiController.hideProgressUploadBar();
  				return;
			}

  			// this.App.Instances.UiController.incrementProgressUploadBar();


  			// 2). Validate file size before proceeding. Assume true to start.
			var isValidFileSize = this.validateFileSize(image);

			if (!isValidFileSize) {
				this.App.Debug.warn('Image exceeds the maximum file size.');
  				this.App.Instances.UiController.flash('error', 'Image exceeds the maximum file size.');
  				// this.App.Instances.UiController.hideProgressUploadBar();
  				return;
			}

  			// this.App.Instances.UiController.incrementProgressUploadBar();



  			// 3). Generate image objects. This must be run inside of a promise function
  			// as the image genererated can only be accessed in the onload function.
  			// JS will continue with the loop before the image may have been made.
  			// So we needed a way to pause the execution whilst the image are being made.
  			// We use the promises "then" function to run all code after the execution.

  			let promise = new Promise(function(resolve, reject) {

				let fileReader = new FileReader();

  				fileReader.onload = function (e) {

	  				var image = new Image;

	        		image.onload = function() {

	        			resolve(image);

		        	};
		        	image.src = fileReader.result;

		  		}

		  		fileReader.readAsDataURL(image);

			})
			.then(function(result) {

				// Run loop to validate all image dimensions. This will loop through the newly
				// created image. This has to be done afterwards as the uploaded files don't provide
				// Image dimensions until an HTML image has been made.
				let renderedimage = result;
				let isValidDimensions = this.validateImageDimensions(renderedimage);

	  			if (!isValidDimensions) {
	  				this.App.Debug.warn('One or more files have invalid dimensions.');
	  				this.App.Instances.UiController.flash('error', 'One or more files have invalid dimensions.');
	  				// this.App.Instances.UiController.hideProgressUploadBar();
	  				return;
	  			}

	  			// this.App.Instances.UiController.incrementProgressUploadBar();

	  			// Timeout to stop it jerking into the animations

				let thumbnail = this.generateRoomThumbnail(renderedimage);

				// Instanciate new Image objects and pass them through to AppContainers
				// Rooms object for global reference
				let Room = new this.Objects.Room(this.App, renderedimage, thumbnail);

				let roomCount = 0;

				for (var i in this.App.Rooms) {

					while (roomCount < this.App.Rooms[i].id) {
						roomCount++;
					}

					roomCount++;
				}

				Room.id = roomCount;
				Room.name = 'Room ' + (roomCount + 1);

				this.Rooms[roomCount] = Room;

				this.App.Instances.UiController.addRoomToSidebar(Room);

				this.App.Instances.UiController.flash('success', 'Image uploaded successfully!');

				this.App.Instances.NewUpload.off();

			}.bind(this));


  		} else {
  			this.App.Debug.warn('image input empty.');
  			this.App.Instances.UiController.flash('error', 'Please select an image..');
  		}

















	}

	removeUpload() {

	}

	/**
	 * @returns [boolean]
	 */
	validateFileType(image) {

		const validData = this.App.Config.validImages,
			  fileTypes = validData.fileTypes;

		let valid = false;

		// Check filetypes
		for (var i = 0; i < fileTypes.length; i++) {
			if (image.type == fileTypes[i]) {
				valid = true;
				break;
			}
		}

		return valid;
	}

	/**
	 * @returns [boolean]
	 */
	validateFileSize(image) {

		const validData = this.App.Config.validImages,
			  maxSizeInBytes = validData.maxSizeInBytes;

		let valid = false;

		if (image.size <= maxSizeInBytes) {
			valid = true;
		}

		return valid;
	}



	/**
	 * @returns [boolean]
	 *
	 * Image object must be made first before using this method.
	 */
	validateImageDimensions(image) {

		const validData = this.App.Config.validImages,
			  resMinWidth = validData.resMinWidth,
			  resMinHeight = validData.resMinHeight;

		let valid = true;

		if (image.width >= resMinWidth && image.height >= resMinHeight) {

			if (image.width != image.height && image.width != (image.height * 2)) {
				valid = false;
			}

		} else {
			valid = false;
		}

		return valid;
	}


	generateRoomThumbnail(img) {

		var canvas = document.createElement('canvas'),
			ctx = canvas.getContext("2d");

		ctx.drawImage(img, 0, 0);

		const MAX_WIDTH = 272;
		const MAX_HEIGHT = 147;

		let width = img.width;
		let height = img.height;

		if (width > height) {
			if (width > MAX_WIDTH) {
				height *= MAX_WIDTH / width;
				width = MAX_WIDTH;
			}
		} else {
			if (height > MAX_HEIGHT) {
		    	width *= MAX_HEIGHT / height;
		    	height = MAX_HEIGHT;
		  	}
		}

		canvas.width = width;
		canvas.height = height;

		var ctx = canvas.getContext("2d");
		ctx.drawImage(img, 0, 0, width, height);

		const dataurl = canvas.toDataURL("image/png");

		return dataurl;

	}

}