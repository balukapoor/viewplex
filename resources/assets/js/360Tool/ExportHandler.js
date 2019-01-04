/**
 * Holds all the methods to do with exporting the tour and each individual room.
 */

export default class ExportHandler {

	constructor(App) {

		this.App = App;

		this.App.Debug.info('Export handler started.');

		// Grab CSRF field
		let csrfField = document.querySelector('[data-export-csrf]');
		this.csrfToken = csrfField.getAttribute('data-export-csrf');

		// ..and the user id
		let userId = document.querySelector('[data-user-id]');
		this.userId = userId.getAttribute('data-user-id');

		// Get the total number of rooms.
		this.roomCount = 0;
		for (let i in this.App.Rooms) {
			this.roomCount++;
		};

		// Start the progress bar
		let steps = this.roomCount + 1;
		this.App.Instances.UiController.showProgressUploadBar(steps);

		// Begin the export
		this.makeTour();

	}

	makeTour() {

		let rooms = this.App.Rooms,
			thisToPass = this;

		// Get a thumbnail from the first room to use
		// as the tours thumbnail.
		let	gotThumb = false,
			thumb;

		for (var i in rooms) {

			if (!gotThumb) {
				thumb = rooms[i].thumbnail;
			}

		}

		// Make new promise. This will allow the halt of the image uploads
		// until the Ajax request to make a new Tour instance in laravel
		// has returned successfull.
		let promise = new Promise(function(resolve, reject) {

	    	// Make new data object. This is so we can strip out the images
	    	// as we don't want to store them in the DB.
	    	let data = {};
	    	for (var i in rooms) {

	    		data[i] = {};

	    		data[i].id = rooms[i].id;	    		
	    		data[i].links = rooms[i].links;
	    		data[i].name = 'ysfd';

	    	}

	    	// Make a new form and add data. This is required for JS's
	    	// XMLhttp request to work as XMLhttp needs a 'form' to submit
	    	// it's data from.
	    	let formData = new FormData();
	        formData.append('data', JSON.stringify(data));
	        formData.append('userId', thisToPass.userId);
	        formData.append('_token', thisToPass.csrfToken);
	        formData.append('tourName', 'testtour');
	        formData.append('tourPostcode', 'lu11ql');
	        formData.append('thumb', thumb);

	        // Make XMLhttp request. On ready, resolve the promise and
	        // continue with the image upload process.
	        console.log(formData);
	        console.log(JSON.stringify(data));
	    	var xmlHttp = new XMLHttpRequest();
	        xmlHttp.onreadystatechange = function()
	        {
	            if(xmlHttp.readyState == 4 && xmlHttp.status == 200)
	            {

	            	let response = JSON.parse(xmlHttp.responseText),
	            		tourId = response.tourId;

	                thisToPass.App.Instances.UiController.incrementProgressUploadBar();

	                // Resolve and pass through returned tour id.
	                resolve(tourId);
	                console.log("Xml status: ", xmlHttp.status);


	            }
	        };

	        // Set the post route and send data.
	        xmlHttp.open('post', '/tours/new');
	        xmlHttp.send(formData);
	        console.log("Xml status: ", xmlHttp.status);

		}).then(function(tourId) {

			// Begin image upload process.
			this.uploadImages(tourId);

		}.bind(this));
	}


	uploadImages(tourId) {

		let rooms = this.App.Rooms,
			thisToPass = this,
			loopCount = 0;

		let secondPromise = new Promise(function(resolve, reject) {

			for (let i in rooms) {

				var secondPromise = new Promise(function(resolve, reject) {

					var formData = new FormData();

			        formData.append('image', rooms[i].image.src);
			        formData.append('name', rooms[i].name);
			        formData.append('userId', thisToPass.userId);
			        formData.append('_token', thisToPass.csrfToken);
			        formData.append('tourName', 'testtour');
			        formData.append('tourId', tourId);
		         	console.log("Xml status: ", xmlHttp.status);

			    	var xmlHttp = new XMLHttpRequest();

			        xmlHttp.onreadystatechange = function()
			        {
			            if(xmlHttp.readyState == 4 && xmlHttp.status == 200)
			            {
			                thisToPass.App.Instances.UiController.incrementProgressUploadBar();
			                loopCount++;
			                resolve();
		                 	console.log("Xml status: ", xmlHttp.status);
			            }

			            console.log("Xml status: ", xmlHttp.status);
			        }

			        xmlHttp.open('post', '/tours/new/store-image');

			        xmlHttp.send(formData);


				}).then(function(tourId) {

					if (loopCount == thisToPass.roomCount) {
			        	resolve();
			        }

				});

			}

		}).then(function() {

			setTimeout(function() {
				window.location = "http://www.yoururl.com";
			}, 1000);

		});

	}

}