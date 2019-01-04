/**
 * Class that adds functionality for the object Picker tool.
 * It's controlled by an on and off switch by the UiController.
 * The class constructor doesnt run any functionality, the on and off methods
 * need to be called conditionally outside of this class.
 */
export default class ObjectPicker {


	constructor(App) {
		this.App = App;
		this.Objects = this.App.Objects;

		this.eventsRegistered = false;
	}


	/**
	 */
	on() {

		this.App.Debug.info('object picker tool started!');

		let canvasContainer = document.querySelector('[data-360-space]'),
			canvas = document.querySelector('[data-360-space] canvas'),
			classBase = 'object-picker-tool',
			body = document.querySelector('body');

		// Add the class to start tool
		canvasContainer.classList.add(classBase + '--active');

		// Add event listener for dbl click.
		let thisToPass = this;

		//variable for ease of use
		this.mesh;

		//set active variable to active for compatibility
		thisToPass.App.Instances.Canvas.isToolActive = true;

		if (!this.eventsRegistered) {
			canvasContainer.addEventListener('dblclick', (e) => {
				if (e.target.parentElement.matches(`.${classBase}--active`)){
					//set active object on double click this returns the object
					this.mesh = thisToPass.App.Instances.Canvas.activateObject(e);

				// Add the class to display the menu and allow event listener to work
					canvasContainer.classList.add(`${classBase}--selected`);
				}

			}, false);

			canvasContainer.addEventListener('click', (e) => {
				if (canvasContainer.classList.contains(`${classBase}--selected`)) {
					//onclick get mouse position and move the selected object in it's place
					this.mousePosition = thisToPass.App.Instances.Canvas.returnMousePos(e);
					thisToPass.App.Instances.Canvas.moveObject(this.mousePosition[0],this.mousePosition[1],this.mousePosition[2], this.mesh);

					let roomId = this.mesh.name,
						room = thisToPass.App.Rooms[roomId],
						data = {
							'coordinates': this.mousePosition
						};
						room.links.push(data);
					 console.log("Moving Object: ", this.mesh);
					 console.log("links list: ", room.links);
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
			classBase = 'object-picker-tool';


		//set variable to false 
		thisToPass.App.Instances.Canvas.isToolActive = false;

		this.App.Debug.info('object picker tool stopped.');

		canvasContainer.classList.remove(`${classBase}--active`);
		canvasContainer.classList.remove(`${classBase}--selected`);

	}

}