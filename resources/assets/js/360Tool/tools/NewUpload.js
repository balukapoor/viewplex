/**
 * Class that shows new upload form
 */
export default class NewUpload {


	constructor(App) {

		this.App = App;
		this.Objects = this.App.Objects;

		this.eventsRegistered = false;
	}

	on(e) {

		this.App.Debug.info('Upload tool started!');

		let body = document.querySelector('body'),
			classBase = 'upload-tool',
			input = document.querySelector('[data-file-input]');


		body.classList.add(`${classBase}--active`);

		input.value = null;

		let thisToPass = this;

		if (!this.eventsRegistered) {

			document.addEventListener('click', (e) => {

				if (e.target.matches('[data-upload-tool-save]')) {

					let image = input.files;

					thisToPass.App.Instances.UploadHandler.uploadToolUpload(image);

					// thisToPass.App.Instances.UiController.flash('success', 'Room renamed');

					// thisToPass.off();

				}

			}, false);

			document.addEventListener('click', (e) => {

				if (e.target.matches('[data-upload-tool-cancel]')) {

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
			classBase = 'upload-tool';

		body.classList.remove(`${classBase}--active`);

		currentActiveToggle.classList.remove('active');

		this.App.Instances.UiController.activeTool = 'off';

	}



}

