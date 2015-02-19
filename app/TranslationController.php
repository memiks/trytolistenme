<?php
class TranslationController extends \Phalcon\Mvc\Controller{
	protected function _getTranslation()
	{
	
		//Ask browser what is the best language
		$language = $this->request->getBestLanguage();
		
		//Check if we have a translation file for that lang
		if (file_exists(realpath(dirname(__FILE__))."/messages/".$language.".php")) {
			require "messages/".$language.".php";
		} else {
			// fallback to some default
			require "messages/en.php";
		}
		
		//Return a translation object
		return new \Phalcon\Translate\Adapter\NativeArray(array(
			"content" => $messages
		));
	
	}
	
	public function t()
	{
		return $this->_getTranslation();
	}

}