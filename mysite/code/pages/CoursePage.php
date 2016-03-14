<?php

class CoursePage extends Page {

	private static $db = array(
		'Teaser' => 'Text'
	);

	private static $has_one = array(
		'HubImage' => 'Image',
		'PrimaryImage' => 'Image'
	);

	private static $has_many = array (
        'HolidayCourses' => 'HolidayCourse',
		'AfterSchoolCourses' => 'AfterSchoolCourse',
		'SaturdayCourses' => 'SaturdayCourse'
    );

	public function getCMSFields() {
	    $fields = parent::getCMSFields();

	    $fields->addFieldToTab('Root.Main', TextareaField::create('Teaser'), 'Content');

		$fields->addFieldToTab('Root.Courses', GridField::create(
			'HolidayCourses',
			'Holiday Courses',
			$this->HolidayCourses(),
			GridFieldConfig_RecordEditor::create()
		));

		$fields->addFieldToTab('Root.Courses', GridField::create(
            'AfterSchoolCourses',
            'After School Courses',
            $this->AfterSchoolCourses(),
            GridFieldConfig_RecordEditor::create()
        ));

		$fields->addFieldToTab('Root.Courses', GridField::create(
            'SaturdayCourses',
            'Saturday Courses',
            $this->SaturdayCourses(),
            GridFieldConfig_RecordEditor::create()
        ));

		$fields->addFieldToTab('Root.Attachments', $hubImage = UploadField::create('HubImage', 'Hub Image'));
		$fields->addFieldToTab('Root.Attachments', $primaryImage = UploadField::create('PrimaryImage'));

		$hubImage->getValidator()->setAllowedExtensions(array('png', 'jpg', 'jpeg'));
		$primaryImage->getValidator()->setAllowedExtensions(array('png', 'jpg', 'jpeg'));
		$hubImage->setFolderName('course-images');
		$primaryImage->setFolderName('course-images');

	    return $fields;
	}

}

class CoursePage_Controller extends Page_Controller {

}
