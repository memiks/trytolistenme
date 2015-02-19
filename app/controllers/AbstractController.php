<?php
	class AbstractController extends \Phalcon\Mvc\Controller{
        public function initialize()
        {
    		//Javascripts in the header
    		$this->assets->collection('headerJS')
    		    ->addJs('js/jquery-1.11.2.min.js')
    		    ->addJs('js/openpgp.js')
    		    ->addJs('js/trytolisten.js')
    
    		    //The name of the final output
    		    //->setTargetPath('static/final.js')
    		
    		    //The script tag is generated with this URI
    		    //->setTargetUri('static/final.js')
    		
    		    //Join all the resources in a single file
    		    //->join(true)
    		
    		    //Use the built-in Jsmin filter
    		    //->addFilter(new Phalcon\Assets\Filters\Jsmin())
    		;
    		    
    		//CSS in the header
    		$this->assets->collection('headerCSS')
    		    ->addCss('css/normalize.css')
    		    ->addCss('css/skeleton.css')
    		    ->addCss('css/style.css')
    
    		    //The name of the final output
    		    ->setTargetPath('static/final.css')
    		
    		    //The script tag is generated with this URI
    		    ->setTargetUri('static/final.css')
    		
    		    //Join all the resources in a single file
    		    ->join(true)
    		
    		    //Use the built-in Jsmin filter
    		    ->addFilter(new Phalcon\Assets\Filters\Cssmin())
    		    ;
        }
	}