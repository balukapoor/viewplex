/**
 * Controller that handles all event listeners and DOM
 * changes. Everything DOM, click, shit and :hover goes
 * in here. This module acts as the 'dealer' of the UI
 */
export class UiController {

	constructor(App) {

		// Attach passed AppContainer to this scope.
		this.App = App;
		this.Objects = this.App.Objects;

		// Register Event Listeners
		this.registerEventListeners();

		this.App.Debug.info('UIController Initialised!');

	}

	/**
	 * Event Listener Functions here
	 */
	registerEventListeners() {

		let thisToPass = this;

		document.addEventListener('DOMContentLoaded', () => {
			thisToPass.uploadClick();
			thisToPass.switchRoom();
			thisToPass.toolsToggleListener();
			thisToPass.hideUiWhileInteracting();
			thisToPass.export();
		}, false);

	}


	/**
	 * On Click of upload
	 */
	uploadClick() {

	  	const thisToPass = this;

		document.addEventListener('click', function(e) {

		  	if (e.target.matches('[data-init-360]')) {

		  		e.preventDefault();

		  		// Grab Images
		  		const imagesInput = document.querySelector('[data-images-input]'),
		  			  images = imagesInput.files;

		  		// Check if UploadHandler instance already exists
		  		let UploadHandler;

		  		if (!thisToPass.App.Instances.UploadHandler) {
		  			UploadHandler = new thisToPass.Objects.UploadHandler(thisToPass.App);
			  		thisToPass.App.Instances.UploadHandler = UploadHandler;
		  		} else {
		  			UploadHandler = thisToPass.App.Instances.UploadHandler;
		  		}

		  		// Call initialUploadSequence Method
		  		UploadHandler.initialUploadSequence(images);

			}

		});
	}


	/**
	 * Method to hide the initial view that you see when first uploading.
	 */
	hideInitialUi() {

		this.App.Debug.log('Initial UI Hidden');

		const bodyElem = document.querySelector('body');

		bodyElem.classList.add('initial-ui-hidden');

	}


	/**
	 * Method to display the rooms sidebar
	 */
	displayUploadsSidebar() {

		this.App.Debug.log('Uploads Sidebar Displayed');

		const bodyElem = document.querySelector('body');

		bodyElem.classList.add('uploads-sidebar-visible');

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
	 * Method to add new room to sidebar. This would usually be called from the
	 * UploadHandler module.
	 */
	addTextToRoomConnector(Room) {

		const text = document.createElement('a');
		text.classList.add('text-container');
		text.setAttribute('data-id', Room.id);
		text.innerHTML = Room.name;

		const name = document.createElement('li');
		name.classList.add('textRoomConnector');
		name.setAttribute('id', "txt"+Room.id);

		name.appendChild(text);

		const txt = document.querySelector('[data-room-connector-text]');
		txt.appendChild(name);

		console.log("Selected Room: ", Room);
	}

	removeTextToRoomConnector(Room) {

		const text = document.getElementsByClass('text-container');
		if (text.getAttribute('data-id') == Room.id) {
			console.log("Removed text: ", text);
			text.style.visibility = 'false';
		}
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
	 * Method to show the progress bar.
	 */
	showProgressUploadBar(steps) {

		const progressBar = document.querySelector('[data-upload-progress-bar]');

		this.UploadProgressBar = {};
		this.UploadProgressBar.steps = steps;
		this.UploadProgressBar.incremented = 0;

		progressBar.classList.add('progress-bar-visible');

	}

	/**
	 * Method to hide the progress bar.
	 */
	hideProgressUploadBar() {

		const progressBar = document.querySelector('[data-upload-progress-bar]'),
			  progressBarInner = document.querySelector('[data-upload-progress-bar-inner]');

		progressBar.classList.remove('progress-bar-visible');
		progressBarInner.style.width = 0;

		this.UploadProgressBar.incremented = 0;

	}

	/**
	 * Method to remove progress bar. It needs to be removed so any new
	 * progress bars can be targeted with .querySelector().
	 */
	removeProgressBar() {

		const progressBar = document.querySelector('[data-upload-progress-bar]');

		progressBar.remove();

		this.UploadProgressBar.incremented = 0;

	}

	/**
	 * Method to increment the progress bar.
	 */
	incrementProgressUploadBar() {
		const percentageToIncrement = 100 / this.UploadProgressBar.steps,
			  progressBarInner = document.querySelector('[data-upload-progress-bar-inner]');

		this.UploadProgressBar.incremented++;
		progressBarInner.style.width = (percentageToIncrement * this.UploadProgressBar.incremented) + '%';
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

			  		// Refresh canvas objects here.
			  		thisToPass.App.Instances.Canvas.loadInImage(Room);
		  		}

		  	}

		  });

	}

	/**
	 * Shows the toolbar.. Duh.
	 */
	showToolbar() {
		const bodyElem = document.querySelector('body');

		bodyElem.classList.add('uploads-toolbar-visible');
	}


	/**
	 * Method to register tools to be used by toolbar.
	 */
	registerTools() {

		const tools = {
			testFunc: (state) => { this.testFunc(state); },
			lookAround: (state) => { this.lookAround(state); },
			moveObject: (state) => { this.moveObject(state); },
			roomConnector: (state, e) => { this.roomConnectorTool(state, e); },
			removeRoomConnector: (state, e) => { this.removeRoomConnectorTool(state, e); },
			renamer: (state, e) => { this.renamerTool(state, e); },
			newUpload: (state, e) => { this.newUploadTool(state, e); }
		};

		return tools;

	}


	/**
	 * Method that controls the toolbar functions. This method detects if they are toggled
	 * on or off and adjusts the functions with a State Param. If button is off and clicked,
	 * it grabs the function name through it's data attr and runs it, passing through the state
	 * based off of the global this.activeTool variable.
	 */
	toolsToggleListener() {

		let thisToPass = this;

		const tools = this.registerTools();

		document.addEventListener('click', function(e) {

		  	if (e.target.parentElement.matches('[data-tool-toggle]') || e.target.matches('[data-tool-toggle]')) {

		  		let toggle;

		  		if (e.target.matches('[data-tool-toggle]')) {
		  			toggle = e.target;
		  		} else {
		  			toggle = e.target.parentElement;
		  		}

		  		const toggleFor = toggle.getAttribute('data-tool'),
		  			  currentActiveToggle = document.querySelector('[data-tool-toggle].active');


		  		if (typeof(thisToPass.activeTool) == "undefined" && thisToPass.activeTool == null) {

		  			thisToPass.activeTool = toggleFor;
		  			tools[toggleFor]('on', e);
		  			toggle.classList.add('active');

		  		} else {

		  			if (thisToPass.activeTool == 'off') {
		  				tools[toggleFor]('on', e);
		  				thisToPass.activeTool = toggleFor;
		  				toggle.classList.add('active');
		  			} else {
		  				if (toggleFor == thisToPass.activeTool) {
			  				tools[toggleFor]('off', e);
			  				thisToPass.activeTool = 'off';
			  				toggle.classList.remove('active');
			  			} else {
			  				tools[thisToPass.activeTool]('off', e);
			  				currentActiveToggle.classList.remove('active');
			  				tools[toggleFor]('on', e);
			  				thisToPass.activeTool = toggleFor;
			  				toggle.classList.add('active');
			  			}
		  			}

		  		}

		  	}

		});


	}


	/**
	 * Initialiser tool for the roomConnector Tool. This is bound to an event listener
	 * in the toolsToggleListener() method above and is controlled by it also.
	 */
	roomConnectorTool(state, e) {

		let RoomConnector;

		if (!this.App.Instances.RoomConnector) {
  			RoomConnector = new this.Objects.RoomConnector(this.App);
	  		this.App.Instances.RoomConnector = RoomConnector;
  		} else {
  			RoomConnector = this.App.Instances.RoomConnector;
		}

		if (state == 'on') {
			RoomConnector.on();
		} else {
			RoomConnector.off();
		}

	}

	removeRoomConnectorTool(state, e) {

	let RemoveRoomConnector;

	if (!this.App.Instances.RemoveRoomConnector) {
			RemoveRoomConnector = new this.Objects.RemoveRoomConnector(this.App);
  		this.App.Instances.RemoveRoomConnector = RemoveRoomConnector;
		} else {
			RemoveRoomConnector = this.App.Instances.RemoveRoomConnector;
	}

	if (state == 'on') {
		RemoveRoomConnector.on();
	} else {
		RemoveRoomConnector.off();
	}

}

	/**
	 * Initialiser tool for the renamer Tool.
	 */
	renamerTool(state, e) {

		let Renamer;

		if (!this.App.Instances.Renamer) {
  			Renamer = new this.Objects.Renamer(this.App);
	  		this.App.Instances.Renamer = Renamer;
  		} else {
  			Renamer = this.App.Instances.Renamer;
		}

		if (state == 'on') {
			Renamer.on(e);
		} else {
			Renamer.off();
		}

	}

	/**
	 * Initialiser tool for the New Upload Tool.
	 */
	newUploadTool(state, e) {

		let uploadTool;

		if (!this.App.Instances.NewUpload) {
  			uploadTool = new this.Objects.NewUpload(this.App);
	  		this.App.Instances.NewUpload = uploadTool;
  		} else {
  			uploadTool = this.App.Instances.NewUpload;
		}

		if (state == 'on') {
			uploadTool.on(e);
		} else {
			uploadTool.off();
		}

	}

	moveObject(state) {

		let ObjectPicker;

		if (!this.App.Instances.ObjectPicker) {
  			ObjectPicker = new this.Objects.ObjectPicker(this.App);
	  		this.App.Instances.ObjectPicker = ObjectPicker;
  		} else {
  			ObjectPicker = this.App.Instances.ObjectPicker;
		}

		if (state == 'on') {
			ObjectPicker.on();
		} else {
			ObjectPicker.off();
		}
	}

	/**
	 * Method that draws from canvas. It hides the ui whilst dragging around the 3d
	 * space. It does not run if canvas isnt present.
	 */
	hideUiWhileInteracting() {

		let body = document.querySelector('body'),
			thisToPass = this;


		function hide(e) {
			if (thisToPass.App.Instances.Canvas && e.target.matches('[data-360-space] canvas')) {

				if (!e.target.matches('.room-connector-tool--active canvas')) {
					body.classList.add('interacting-with-canvas');
				}

			}
		}

		function show(e) {
			if (thisToPass.App.Instances.Canvas && e.target.matches('[data-360-space] canvas')) {
				body.classList.remove('interacting-with-canvas');
			}
		}


		body.addEventListener('mousedown', function(e) {

			hide(e);

			//console.log(thisToPass.App);

		});

		body.addEventListener('mouseup', function(e) {

			show(e);

		});

		body.addEventListener('mouseout', function(e) {

			show(e);

		});

	}


	refreshRoomName(id, name) {

		let elem = document.querySelector('[data-upload][id="' + id + '"] .uploads-sidebar__upload-name');

		elem.innerHTML = '';
		elem.innerHTML = name;

	}


	export() {

		let btnContainer = document.querySelector('.uploads-toolbar__submit');

		btnContainer.addEventListener('click', (e) => {

			if ( e.target.parentElement.matches('[data-export-button]') ||
				e.target.matches('[data-export-button]')) {

				this.showExportConfirmation();

			}

		});

	}


	showExportConfirmation() {

		let body = document.querySelector('body');

		body.classList.add('export-window-open');

		// Event listeners
		if (!this.exportEventRegistered) {

			this.exportEventRegistered = true;

			let exportWindow = document.querySelector('.export-window');

			exportWindow.addEventListener('click', (e) => {

				if (e.target.matches('[data-export-confirm]')) {

					body.classList.add('interacting-with-canvas');
					exportWindow.classList.add('exporting');

					let exportHandler = new this.App.Objects.ExportHandler(this.App);

				}

				if (e.target.matches('[data-export-back]')
					|| e.target.parentElement.matches('[data-export-back]')) {

					body.classList.remove('export-window-open');

				}

			});


		}

	}


}
