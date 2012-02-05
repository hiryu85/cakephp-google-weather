<?php
class GoogleWeatherComponent extends Object {
	const URL = 'http://www.google.com/ig/api';
	
   /**
	*	@var $_defaultSettings	Default settings
	*/
	var $_defaultSettings = array(
		'lang' => 'en',
		'user-agent' => 'Google chromium'
	);
	
   /**
	*	@var $settings	Component runtime settings
	*/
	var $settings = array();


   /**
    *	Component init
    */	
	public function initialize(&$controller, $settings = array()) {
		$this->settings = array_merge($this->_defaultSettings, $settings);
	}


   /**
    *	Request weather data for $city_key
    * 
    *	@param		$city_key	City to search
    * 	@example	$weather = $this->GoogleWeather->get('Rome, Italy')
    *	@return		mixed		null if response is invalid, otherwise array
    */	
	public function get($city_key = null) {
		if (is_null($city_key)) return;	
		if (!class_exists('HttpSocket')) {
			App::import('Core', 'HttpSocket');
		}
		
		$HttpSocket = new HttpSocket();
		$vars = array(
			'hl' => $this->settings['lang'],
			'weather'  => $city_key
		);
		$response = $HttpSocket->get($this::URL, $vars, array('header' => array('User-Agent' => $this->settings['user-agent'])) );
		if (empty($response)) return false;

		if (!class_exists('Xml')) {
			App::import('Core', 'Xml');
		}

		$xml = new Xml($response);
		// This converts the Xml document object to a formatted array
		$xmlAsArray = $xml->toArray();
		return $xmlAsArray;
		
	}

}
