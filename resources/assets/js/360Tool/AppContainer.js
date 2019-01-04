import Debug from './Debug.js';
import Config from './Config.js';
import UploadHandler from './UploadHandler.js';
import ExportHandler from './ExportHandler.js';
import Room from './Room.js';
import Canvas from './Canvas.js';
import RoomConnector from './tools/RoomConnector.js';
import RemoveRoomConnector from './tools/RemoveRoomConnector.js';
import Renamer from './tools/Renamer.js';
import NewUpload from './tools/NewUpload.js';
import ObjectPicker from './tools/ObjectPicker.js';
import { UiController } from './UiController.js';
import { ViewierController } from './ViewerController.js';

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
			'UploadHandler': UploadHandler,
			'ExportHandler': ExportHandler,
			'Room': Room,
			'Canvas': Canvas,
			'RoomConnector': RoomConnector,
			'RemoveRoomConnector': RemoveRoomConnector,
			'Renamer': Renamer,
			'NewUpload': NewUpload,
			'ObjectPicker': ObjectPicker
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