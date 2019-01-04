/**
 * This script adds a class to any element clicked on with data attr
 * of "[data-open-menu]". It assumes the menu is inside of the element as it adds
 * the class to the parent.
 */
function dropDownMenu() {

	function openMenu(menu) {

		var menuName = menu.data('menu-name');

		$('body').data('open-menu', menuName);

		menu.addClass('menu-open');

		$('body').addClass('menu-open');
	}

	function closeMenu() {

		var openMenu = $('body').data('open-menu');

		$('[data-menu-name="' + openMenu + '"]').removeClass('menu-open');

		$('body').removeClass('menu-open');

		$('body').data('open-menu', 'none');
	}

	function isTouchDevice() {
        try {
            document.createEvent("TouchEvent");
            return true;
        } catch (e) {
			return false;
        }
    }

    var eventType;
    if (isTouchDevice()) {
    	eventType = 'touchend';
    } else {
    	eventType = 'click';
    }


    var scrolling = false;
    $(window).scroll(function () {
    	scrolling = true;

    	clearTimeout($.data(this, 'scrollTimer'));
	    $.data(this, 'scrollTimer', setTimeout(function() {
	    	scrolling = false;
	    }, 100));

    });


	$(document).on(eventType, '[data-open-menu]', function (e) {

		openMenu($(this));
	});

	$(document).on(eventType, '[data-close-menu]', function () {

		if (!scrolling) {
			closeMenu();
		}

	});

}




// Init
dropDownMenu();