/**
 * Holds all the methods to do with the individual room/tour piece.
 */

export default class Room {

	constructor(App, image, thumbnail) {

		this.App = App;
		this.Objects = this.App.Objects;
		this.image = image;
		this.thumbnail = thumbnail;
		this.links = [];
		this.id = 0;

	}

}