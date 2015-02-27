<?php
use Phalcon\Mvc\View,
    Phalcon\Mvc\View\Engine\Volt,
    Phalcon\Config\Adapter\Ini;
    
include('../app/Response.php');
include('../app/TranslationController.php');

try {
	$config = new Ini("config.ini");

    //Register an autoloader
    $loader = new \Phalcon\Loader();
    $loader->registerDirs(array(
        $config->phalcon->controllersDir,
        $config->phalcon->modelsDir
    ))->register();

    //Create a DI
    $di = new Phalcon\DI\FactoryDefault();
    
    //Register Volt as a service
    $di->set('voltService', function($view, $di) {
		$config = new Ini("config.ini");
    
        $volt = new Volt($view, $di);
        $volt->setOptions(array(
            "compiledPath" => $config->volt->compiledPath,
            "compiledExtension" => $config->volt->compiledExtension
        ));
    
        return $volt;
    });

    //Registering Volt as template engine
    $di->set('view', function($di) {
		$config = new Ini("config.ini");

        $view = new \Phalcon\Mvc\View();
        $view->setViewsDir($config->phalcon->viewsDir);
        $view->registerEngines(array(
            $config->volt->extension => 'voltService',
        ));
    
        return $view;
    });


	//Register a controller as a service
	$di->set('translate', function() {
	    $component = new TranslationController();
	    return $component->t();
	});

    //Set the database service
    $di->set('db', function(){
		$config = new Ini("config.ini");
        return new \Phalcon\Db\Adapter\Pdo\Mysql(array(
            "host" => $config->database->host,
            "username" => $config->database->username,
            "password" => $config->database->password,
            "dbname" => $config->database->dbname
        ));
    });

    $di->set('url', function () {
        $url = new Phalcon\Mvc\Url();
        $url->setBaseUri("/");
        return $url;
    }, true);

    //Start the session the first time when some component request the session service
    $di->setShared('session', function() {
        $session = new Phalcon\Session\Adapter\Files();
        $session->start();
        return $session;
    });

	$di->set('response',function(){
	    return new \Core\Http\Response();
	});


    //Set up the flash service
    $di->set('flash', function (){
      $flash = new \Phalcon\Flash\Session(array(
        'error' => 'error',
        'success' => 'success',
        'notice' => 'info',
    ));
      return $flash;
    });

    //Handle the request
    $application = new \Phalcon\Mvc\Application($di);

    echo $application->handle()->getContent();

} catch(\Phalcon\Exception $e) {
     echo "PhalconException: ", $e->getMessage();
}
