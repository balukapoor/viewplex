/**
 * Controller that handles all event listeners and DOM
 * changes. Everything DOM, click, shit and :hover goes
 * in here. This module acts as the 'dealer' of the UI
 */
export class ViewierController {

	constructor(App) {

		// Attach passed AppContainer to this scope.
		this.App = App;
		this.Objects = this.App.Objects;

		// Register Event Listeners
		//this.showViewer();

		this.App.Debug.info('ViewierController Initialised!');

	}

	/**
	 * Event Listener Functions here
	 */
	registerEventListeners() {

		let thisToPass = this;

		document.addEventListener('DOMContentLoaded', () => {
			thisToPass.switchRoom();
		}, false);

	}

	showViewer(){
		let thisToPass = this;
		var image = "/images/TestingMultiForm1.jpeg";
		var Room = new thisToPass.Objects.Room(thisToPass.App, image);
		var Canvas = new thisToPass.Objects.Canvas(thisToPass.App);
		thisToPass.App.Instances.Canvas = Canvas;
	}


	/**
	 * Method to add new room to sidebar. This would usually be called from the
	 * UploadHandler module.
	 */
	addRoomToSidebar(Room) {

		const thumb = document.createElement('div');
		thumb.classList.add('uploads-sidebar__upload-thumb');
		thumb.style.backgroundImage = `url(${Room.thumbnail})`;

		const name = document.createElement('span');
		name.classList.add('uploads-sidebar__upload-name');
		name.innerHTML = Room.name;
		name.setAttribute('data-tool-toggle', '');
		name.setAttribute('data-tool', 'renamer');

		const listItem = document.createElement('li');
		listItem.classList.add('uploads-sidebar__upload');
		listItem.setAttribute('id', Room.id);
		listItem.setAttribute('name', Room.name);
		listItem.setAttribute('data-upload', '');

		if (Room.id == 0) {
			listItem.classList.add('active');
		}

		listItem.appendChild(thumb);
		listItem.appendChild(name);

		const sidebar = document.querySelector('[data-uploads-navigation]');
		sidebar.appendChild(listItem);

		listItem.classList.add('thumb-visible');

	}


	/**
	 * Method to show the Main Spinning Loader
	 */
	showAppLoader() {

		const bodyElem = document.querySelector('body');

		bodyElem.classList.add('app-loader-visible');

	}

	/**
	 * Method to hide the Main Spinning Loader
	 */
	hideAppLoader() {

		const bodyElem = document.querySelector('body');

		bodyElem.classList.remove('app-loader-visible');

	}

	/**
	 * Method to capture and flash messages on the screen. Add different types into
	 * the array at the top of the method if you need a custom reponse type. E.g.
	 * caution may need to go in.
	 */
	flash(type, message) {

		let icons = {
			'error': 'error_outline',
			'success': 'check_circle',
			'info': 'info'
		};

		const messageElem = document.createElement('span');
		messageElem.classList.add('message');
		messageElem.innerHTML = message;

		const iconElem = document.createElement('i');
		iconElem.classList.add('material-icons');
		iconElem.innerHTML = icons[type];

		const flashElem = document.createElement('div');
		flashElem.classList.add('flash-message');
		flashElem.classList.add(type);

		flashElem.appendChild(iconElem);
		flashElem.appendChild(messageElem);

		const flashBox = document.querySelector('[data-flash-messages]');

		flashBox.appendChild(flashElem);

		setTimeout(function() {
			flashElem.classList.add('flash-visible');

			setTimeout(function() {
				flashElem.classList.remove('flash-visible');

				setTimeout(function() {
					flashBox.removeChild(flashElem);
				}, 1000);
			}, 5000);
		}, 200);

	}

	/**
	 * Switches the active room when clicked on the sidebar. It runs a method from the Canvas
	 * object to switch the canvas source.
	 */
	switchRoom() {

		let thisToPass = this;

		document.addEventListener('click', function(e) {

		  	if (e.target.parentElement.matches('[data-upload]')) {

		  		if (!e.target.matches('.uploads-sidebar__upload-name')) {
		  			thisToPass.showAppLoader();

			  		const upload = e.target.parentElement,
			  			  uploadId = upload.getAttribute('id');

			  		const currentActiveUpload = document.querySelector('[data-upload].active');
			  		currentActiveUpload.classList.remove('active');

			  		upload.classList.add('active');
			  		const Room = thisToPass.App.Rooms[uploadId];

			  		thisToPass.App.Instances.Canvas.loadInImage(Room);

			  		// Refresh canvas objects here.
		  		}

		  	}

		  });

	}

}
