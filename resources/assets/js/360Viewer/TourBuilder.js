/**
 * Class that builds the 3d tour based on data provided by laravel.
 */
export default class TourBuilder {

	constructor(App) {

		// Attach passed AppContainer to this scope.
		this.App = App;
		this.Objects = this.App.Objects;

		this.App.Debug.info('Tour Builder Started');

		let rooms = document.querySelector('[data-tour-data]');
		rooms = JSON.parse(rooms.getAttribute('data-tour-data'));

		let imageBase = document.querySelector('[data-image-base]');
		imageBase = imageBase.getAttribute('data-image-base');

		// Add image into room objects
		for (var i in rooms) {

			var image = imageBase + rooms[i].name + '.jpg';

			// var image = new Image;

    		// image.onload = function() {

    		// 	imageBatch.push(image);
    		// 	resolve(image);

      //   	};
      //   	image.src = fileReader.result;

			// var thumbnail = this.generateRoomThumbnail(image);
			var thumbnail = 'thumbnail'

			var Room = new this.Objects.Room(this.App, null, thumbnail);

			Room.id = rooms[i].id;
			Room.name = rooms[i].name;
			Room.image = {};
			Room.image.src = image;

			this.App.Rooms[rooms[i].id] = Room;

			this.App.Instances.UiController.addRoomToSidebar(Room);

		}

		this.App.Instances.UiController.displayUploadsSidebar();

		var Canvas = new this.Objects.Canvas(this.App);
		this.App.Instances.Canvas = Canvas;
		this.App.Instances.UiController.showToolbar();


		console.log(this.App);

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
