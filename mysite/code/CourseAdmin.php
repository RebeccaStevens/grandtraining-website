<?php

class CourseAdmin extends ModelAdmin {

    private static $menu_title = 'Courses';

    private static $url_segment = 'courses';

    private static $managed_models = array (
        'HolidayCourse',
		'AfterSchoolCourse',
		'SaturdayCourse'
    );

	private static $menu_icon = 'mysite/icons/courses.png';
}
