/**
 * Class that adds functionality for the object Picker tool.
 * It's controlled by an on and off switch by the UiController.
 * The class constructor doesnt run any functionality, the on and off methods
 * need to be called conditionally outside of this class.
 */
export default class RemoveRoomConnector {


	constructor(App) {
		this.App = App;
		this.Objects = this.App.Objects;

		this.eventsRegistered = false;
	}


	/**
	 */
	on() {

		this.App.Debug.info('remove room connector tool started!');

		let canvasContainer = document.querySelector('[data-360-space]'),
			canvas = document.querySelector('[data-360-space] canvas'),
			classBase = 'remove-connector-tool',
			body = document.querySelector('body'),
			delRC = document.getElementById('removeConnector'),
			tlbar = document.getElementById('contextTool');


		tlbar.style.opacity = '1';
		tlbar.style.visibility = 'visible';

		// Add the class to start tool
		canvasContainer.classList.add(classBase + '--active');

		// Add event listener for dbl click.
		let thisToPass = this;

		//variable for ease of use
		this.mesh;

		//set active variable to active for compatibility
		thisToPass.App.Instances.Canvas.isToolActive = true;

		if (!this.eventsRegistered) {
			canvasContainer.addEventListener('click', (e) => {
				if (e.target.parentElement.matches(`.${classBase}--active`)){
					//set active object on double click this returns the object

					// Add the class to display the menu and allow event listener to work
					canvasContainer.classList.add(`${classBase}--selected`);
					this.mesh = thisToPass.App.Instances.Canvas.activateObject(e);
				}

			}, false);

			//add event listener to side toolbar to delete room connector

			delRC.addEventListener('click', (e) => {
				if (canvasContainer.classList.contains(`${classBase}--selected`)) {
					//get id of mesh
					const objId = this.mesh.id;
					const roomId = this.getCurrentRoomId();

					//select currently active room
					let select = document.querySelector('[data-room-connector-choices]'),
						room = thisToPass.App.Rooms[roomId];

					for (var i = room.links.length - 1; i >= 0; i--) {
						if (room.links[i].roomName == this.mesh.name) {
							var a  = room.links.indexOf(room.links[i]);
							room.links.splice(a, 1);
							thisToPass.App.Instances.Canvas.removeRoomConnectors(objId);

							//remove html
							//thisToPass.App.Instances.UiController.removeTextToRoomConnector(room);

						}

						console.log("Room Link to compare: ",room.links[i].roomName);
						console.log("Room Link to compare: ",this.mesh.name);
					}


					//keep track of movenent through class name
					canvasContainer.classList.remove(`${classBase}--selected`);

				}
			}, false);

			this.eventsRegistered = true;

		}

	}


	// Off switch. Simply remove all classes to do with tool.
	off() {

		let thisToPass = this;

		let canvas = document.querySelector('[data-360-space] canvas'),
			canvasContainer = document.querySelector('[data-360-space]'),
			classBase = 'remove-connector-tool',
			tlbar = document.getElementById('contextTool');


		//set variable to false 
		thisToPass.App.Instances.Canvas.isToolActive = false;

		this.App.Debug.info('remove connector tool stopped.');

		canvasContainer.classList.remove(`${classBase}--active`);
		canvasContainer.classList.remove(`${classBase}--selected`);

		tlbar.style.opacity = '0';
		tlbar.style.visibility = 'hidden';

	}

	//get current selected room ID based on sidebar
	getCurrentRoomId(){
		this.parentElement = document.querySelector('[data-upload].active');

		const upload = this.parentElement,
			  uploadId = upload.getAttribute('id');

		return uploadId;
	}
}