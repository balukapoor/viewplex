/**
 * Class that allows renaming of each room.
 */
export default class Renamer {


	constructor(App) {

		this.App = App;
		this.Objects = this.App.Objects;

		this.eventsRegistered = false;
	}

	on(e) {

		this.App.Debug.info('Renamer tool started!');

		let body = document.querySelector('body'),
			classBase = 'renamer-tool',
			input = document.querySelector('[data-name-input]');

		this.parentElement = e.target.parentElement;
		this.roomId = this.parentElement.getAttribute('id');

		input.value = e.target.innerHTML;

		body.classList.add(`${classBase}--active`);

		input.focus();

		let thisToPass = this;

		if (!this.eventsRegistered) {

			document.addEventListener('click', (e) => {

				if (e.target.matches('[data-renamer-tool-save]')) {

					let inputVal = input.value;
					let room = this.App.Rooms[this.roomId];

					room.name = inputVal;

					thisToPass.App.Instances.UiController.refreshRoomName(this.roomId, inputVal);

					thisToPass.App.Instances.UiController.flash('success', 'Room renamed');

					thisToPass.off();

				}

			}, false);

			document.addEventListener('click', (e) => {

				if (e.target.matches('[data-renamer-tool-cancel]')) {

					thisToPass.off();

					thisToPass.App.Instances.UiController.activeTool = 'off';

				}

			});

			this.eventsRegistered = true;

		}

	}


	off() {

		let currentActiveToggle = document.querySelector('[data-tool-toggle].active'),
			body = document.querySelector('body'),
			classBase = 'renamer-tool';

		body.classList.remove(`${classBase}--active`);

		currentActiveToggle.classList.remove('active');

		this.App.Instances.UiController.activeTool = 'off';

	}



}

