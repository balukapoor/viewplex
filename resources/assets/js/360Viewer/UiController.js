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
			thisToPass.initialLoad();
			thisToPass.switchRoom();
			thisToPass.toolsToggleListener();
			thisToPass.hideUiWhileInteracting();
		}, false);

	}



	/**
	 * Initial load
	 */
	initialLoad() {
		this.showAppLoader();

		// Start the Tour Builder
		let TourBuilder = new this.App.Objects.TourBuilder(this.App);
		this.App.Instances.TourBuilder = TourBuilder;

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
			// testFunc: (state) => { this.testFunc(state); },
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

		});

		body.addEventListener('mouseup', function(e) {

			show(e);

		});

		body.addEventListener('mouseout', function(e) {

			show(e);

		});

	}


}
