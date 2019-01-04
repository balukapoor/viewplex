import Debug from './../360Tool/Debug.js';
import Config from './../360Tool/Config.js';
import Room from './../360Tool/Room.js';
import Canvas from './../360Tool/Canvas.js';
import { UiController } from './UiController.js';
import TourBuilder from './TourBuilder.js';

/**
 * Container class This is the global container that will "Wrap"
 * the entire application. Import all objects here.
 */
export default class AppContainer {

	constructor() {

		// Register Static Objects to be used globally
		// Make sure when making new Static models and
		// passing them through here, in the constructor() method
		// for that model, you set the App and Objects variables.
		this.Objects = {
			'Room': Room,
			'Canvas': Canvas,
			'TourBuilder': TourBuilder
		}

		// Register Global App (Pass this through to each Object).
		// Whenever defining a new instance of the above objects,
		// make sure to add it to the instances object below so other
		// Objects can make reference to it. Same for all instances of
		// "Room" objects, except they go in the "Rooms" object below.
		this.App = {
			'Config': Config,
			'Debug': new Debug(Config.debug),
			'Objects': this.Objects,
			'Instances': {},
			'Rooms': {}
		};

		this.App.Debug.info('App Started!');

		// Start the Ui Controller
		const UICONTROLLER = new UiController(this.App);
		this.App.Instances.UiController = UICONTROLLER;

	}

}