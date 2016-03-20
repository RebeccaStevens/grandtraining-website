<?php

class HomePage extends Page {

}

class HomePage_Controller extends Page_Controller {

	/**
	 * This is the current section's route.
	 */
	public function PageTitle() {
		return SiteConfig::current_site_config()->Title;
	}

}
