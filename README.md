CakePHP 2.x plugin for Twitter Bootstrap 2.0 Compatible output
============


Includes:
-------

	View Helpers for CakePHP to use the correct class' for TwitterBootstrap
	Have submodule to TwitterBootstrap itself

	You can either include bootstrap.min.js in order to get support for all twitter bootstrap JS feature,
	or you can include each module as you wish to use. See below.


Setup:
-------
# Load the plugin in Config/bootstrap.php

	// Load TwitterBootstrap plugin, without loading bootstrap
	CakePlugin::load('TwitterBootstrap');

# Use the BootstrapForm and BootstrapPaginator helpers instead of the default Form and Paginator

	// Change default Form & Paginator
	public $helpers = array(
		'Form'		=> array('className' => 'TwitterBootstrap.BootstrapForm'),
		'Paginator' => array('className' => 'TwitterBootstrap.BootstrapPaginator'),
	);

# And that's it! Use as normal