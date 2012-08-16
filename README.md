CakePHP 2.x plugin for Twitter Bootstrap 2.0 Compatible output
============

Updates the default CakePHP form and pagination helpers to output html that is compatible with the Twitter Bootstrap CSS framework.

Setup:
-------
1. Load the plugin in Config/bootstrap.php

		// Load TwitterBootstrap plugin, without loading bootstrap
		CakePlugin::load('TwitterBootstrap');

2. Use the BootstrapForm and BootstrapPaginator helpers instead of the default Form and Paginator

		// Change default Form & Paginator
		public $helpers = array(
			'Form'		=> array('className' => 'TwitterBootstrap.BootstrapForm'),
			'Paginator' => array('className' => 'TwitterBootstrap.BootstrapPaginator'),
		);

3. And that's it! Use as normal