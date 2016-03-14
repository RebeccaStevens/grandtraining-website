<?php

class SiteConfigExtension extends DataExtension {

    private static $db = array (
        'FacebookLink' => 'Varchar',
        'TwitterLink' => 'Varchar',
        'GoogleLink' => 'Varchar',
        'YouTubeLink' => 'Varchar'
    );

    public function updateCMSFields(FieldList $fields) {
        $fields->addFieldsToTab('Root.Social', array (
            TextField::create('FacebookLink', 'Facebook'),
            TextField::create('TwitterLink', 'Twitter'),
            TextField::create('GoogleLink', 'Google'),
            TextField::create('YouTubeLink', 'YouTube')
        ));
    }

    public function SocialLinks() {
        return
            $this->owner->FacebookLink ||
            $this->owner->TwitterLink ||
            $this->owner->GoogleLink ||
            $this->owner->YouTubeLink;
    }
}
