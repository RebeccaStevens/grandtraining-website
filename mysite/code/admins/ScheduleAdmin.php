<?php

class ScheduleAdmin extends ModelAdmin {

    private static $menu_title = 'Booking Dates';

    private static $url_segment = 'schedule';

    private static $managed_models = array (
		'ScheduledCourse'
    );

	private static $menu_icon = 'mysite/icons/schedule.png';
}
