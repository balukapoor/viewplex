/**
 * Class that adds functionality for the Room Connector tool.
 * It's controlled by an on and off switch by the UiController.
 * The class constructor doesnt run any functionality, the on and off methods
 * need to be called conditionally outside of this class.
 */
export default class RoomConnector {


	constructor(App) {
		this.App = App;
		this.Objects = this.App.Objects;

		this.eventsRegistered = false;
	}


	/**
	 * When method is called, it adds class to body that adjusts the cursor and
	 * allows the below event listeners to work. The listeners track whether a dbl click
	 * has been made to make a new connection and then also for cancelling and finishing
	 * the current connection.
	 */
	on() {

		this.App.Debug.info('Room connector tool started!');

		let canvasContainer = document.querySelector('[data-360-space]'),
			canvas = document.querySelector('[data-360-space] canvas'),
			classBase = 'room-connector-tool',
			body = document.querySelector('body');

		// Add the class to start tool
		canvasContainer.classList.add(classBase + '--active');

		// Add event listener for dbl click.
		let thisToPass = this;

		//set active variable to active for compatibility
		thisToPass.App.Instances.Canvas.isToolActive = true;

		this.mainRoomId = this.getCurrentRoomId();

		this.mainRoom = thisToPass.App.Rooms[this.mainRoomId];

		if (!this.eventsRegistered) {
			canvasContainer.addEventListener('dblclick', (e) => {
				//get mouse position on double click
				// Grab canvas clicked position and set it to global var so other
				// methods and event listners can use the data
				this.mousePosition = thisToPass.App.Instances.Canvas.returnMousePos(e);

				if (e.target.parentElement.matches(`.${classBase}--active`) && !e.target.parentElement.matches(`.${classBase}--connecting`)) {

					// Populate the select incase new rooms have been added or deleted
					thisToPass.populateRoomPicker();

					// Add the class to display the menu and allow event listener to work
					canvasContainer.classList.add(`${classBase}--connecting`);
				}

			}, false);

			// On click of cancel, simply remove "connecting" class.
			canvasContainer.addEventListener('click', (e) => {
				if (e.target.matches('[data-room-connector-cancel]')) {
					canvasContainer.classList.remove(`${classBase}--connecting`);
				}
			}, false);

			// On click of save:
			// ----------------------------------------------
			// 	1) remove "connecting class"
			// 	2) get room id from selected value
			// 	3) add room id and clicked canvas coordinates to object
			// 	4) Push data to Room.links object
			// 	5) Run Canvas method to add the link to the canvas
			// 	6) Flash, aaahhhhhhhaaaaaaa. Masters of the Universe!
			canvasContainer.addEventListener('click', (e) => {
				if (e.target.matches('[data-room-connector-save]')) {

					canvasContainer.classList.remove(`${classBase}--connecting`);

					let select = document.querySelector('[data-room-connector-choices]'),

						roomId = select.options[select.selectedIndex].getAttribute('value'),

						room = thisToPass.App.Rooms[roomId],

						coonectorId = this.connectRoomIdToSelection(),

						baseId = this.getCurrentRoomId(),

						baseRoom = room = thisToPass.App.Rooms[baseId],
						//create room connector object
						roomConnectorObject = thisToPass.App.Instances.Canvas.addNewRoomConnector(coonectorId),
						roomConnectorId = roomConnectorObject.id,
						roomConnectorName = roomConnectorObject.name,

						//add html
						roomConnectorText = thisToPass.App.Instances.UiController.addTextToRoomConnector(this.mainRoom),
						data = {
							'coordinates': roomConnectorObject.position,
							'roomId': roomId,
							'baseId': baseId,
							'objectId': roomConnectorId,
							'roomName': roomConnectorName,
							'htmltext': roomConnectorText
						};

					thisToPass.App.Instances.Canvas.updateRoomConnector(this.mousePosition[0],this.mousePosition[1],this.mousePosition[2]);
					this.getTextOfRoomConnector(this.mainRoomId, e);
					//push data
					baseRoom.links.push(data);

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
			classBase = 'room-connector-tool';

		//set active variable to active for compatibility
		thisToPass.App.Instances.Canvas.isToolActive = false;

		this.App.Debug.info('Room connector tool stopped.');

		canvasContainer.classList.remove(`${classBase}--active`);
		canvasContainer.classList.remove(`${classBase}--connecting`);

	}


	// Method loops over all rooms, grabs their names and ID's and
	// Populates the Select element with options filled in with the grabbed data.
	// This should be run everytime user goes to make a connection.
	populateRoomPicker(e) {

		let rooms = this.App.Rooms,
			select = document.querySelector('[data-room-connector-choices]');


		select.innerHTML = '';

		for (var i in rooms) {

			var option = document.createElement('option');

			option.innerHTML = rooms[i].name;
			option.setAttribute('value', rooms[i].id);

			select.appendChild(option);

		}

	}

	//get ID function, light easy to use
	connectRoomIdToSelection(){
		let select = document.querySelector('[data-room-connector-choices]');
		this.roomID = select.options[select.selectedIndex].getAttribute('value');
		return this.roomID;
	}

	//get current selected room ID based on sidebar
	getCurrentRoomId(){
		this.parentElement = document.querySelector('[data-upload].active');

		const upload = this.parentElement,
			  uploadId = upload.getAttribute('id');

		return uploadId;
	}

	getTextOfRoomConnector(roomid, e){
		var htmlid = document.getElementById("txt"+roomid);
		var container = document.querySelector('[data-360-space]');

        var mouse = [e.clientX, e.clientY];

		htmlid.style.top = mouse[1] + "px";
		htmlid.style.left = mouse[0] + "px";

		console.log("ID?: ", htmlid);
		console.log("Top: ", mouse[1] + "px");
		console.log("Left: ", mouse[0] + "px");
	}
}