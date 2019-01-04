/**
 * Simple Class that allows us to turn on
 * and off console logs. Handy for when taking the app into
 * production. Setting from config file. Instanciated in container.
 */

export default class Debug {

	constructor(enabled = false) {
		this.enabled = enabled;
		this.stack = 1;

		if (!this.enabled) {
			this.off();
		}

	}

	off() {

		if (!window.console) {
			window.console = {};
		}

	    var methods = [ 'assert', 'clear', 'count', 'debug', 'dir',
						'dirxml', 'error', 'exception', 'error', 'group',
						'groupCollapsed', 'groupEnd', 'info', 'log',
						'profile', 'profileEnd', 'table', 'time',
						'timeEnd', 'timeStamp', 'trace', 'warn' ];

	    for (var i = 0; i < methods.length; i++) {
	        console[methods[i]] = () => {};
	    }
	}

	log(msg) {
		if (this.enabled) {
			console.log(this.stack + '): ' + msg);
			this.stack++;
		}
	}

	success(msg) {
		if (this.enabled) {
			console.log(`✅ ${this.stack}): ${msg}`);
		}
	}

	trace(msg) {
		if (this.enabled) {
			console.trace(`➖ ${this.stack}): ${msg}`);
			this.stack++;
		}
	}

	info(msg) {
		if (this.enabled) {
			console.log(`🚹 ${this.stack}): ${msg}`);
			this.stack++;
		}
	}

	warn(msg) {
		if (this.enabled) {
			console.warn(`${this.stack}): ${msg}`);
			this.stack++;
		}
	}

	clear() {
		if (this.enabled) {
			console.clear();
		}
	}

	disable() {
		this.enabled = false;
	}

	enable() {
		this.enabled = true;
	}

	overide(msg) {
		console.log(`👾 ${this.stack}): ${msg}`);
	}

	logObject(msg) {
		if (this.enabled) {

			var output = '';

  			for (var line in msg) {
    			output += line + ': ' + msg[line] + '\n';
  			}

  			console.log(this.stack + '): ' + output);

			this.stack++;
		}
	}
}