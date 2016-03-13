<?php

class BookingAdmin extends ModelAdmin {

    private static $menu_title = 'Bookings Received';

    private static $url_segment = 'bookings';

    private static $managed_models = array (
		'Booking'
    );

	private static $menu_icon = 'mysite/icons/bookings.png';
}
