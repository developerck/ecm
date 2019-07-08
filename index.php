<?php
/*
 * php 5.4
 */

set_time_limit(120);
ini_set('memory_limit', '128M');
require_once 'config/config.php';
$f3 = require_once $CNF->baselibdir . $CNF->DS . 'base.php';

//overwrite-error handler to overwrite handler written in fat-free framework
$exceptionHandler = new \devlib\Exception ();
$errorHandler = new \devlib\Error ();
if (!$CNF->debug) {
	$f3->set('ONERROR', function ($f3)
	{
// in case some thing happend very bad (:
		global $CNF;
		ob_start();
		include ($CNF->uidir . $CNF->DS . 'error' . $CNF->DS . 'error.html');
		ob_end_flush();
		exit();
	});
}
if ($CNF->maintainmode) {
    ob_start();
    include ($CNF->uidir . $CNF->DS . 'error' . $CNF->DS . 'maintain.html');
    ob_end_flush();
    exit();
}



try {

    $DB = new \devlib\DBWrapper($CNF->conn['dsn'], $CNF->conn['username'], $CNF->conn['password'], array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ));

    $CNF->DB = $DB;
} catch (\PDOException $pe) {

    throw new \devlib\Exception($pe, 'SERVICE');
}

/*
 * Setting route for the web
 */

$f3->route(array(
    'GET|POST / [sync]',
    'GET|POST /index.php [sync]',
    'GET|POST /@module/@controller [sync]',
    'GET|POST /@module/@controller/@action [sync]',
    'GET|POST /@module/@controller/@action/* [sync]'
), function () use($f3, $CNF)
{

    require $CNF->moduledir . $CNF->DS . 'index.php';
});

/*
 * Setting route for the ajax
 */

$f3->route(array(
    'GET|POST /@module/@controller [ajax]',
    'GET|POST /@module/@controller/@action [ajax]',
    'GET|POST /@module/@controller/@action/* [ajax]'
), function () use($f3, $CNF)
{

    require $CNF->moduledir . $CNF->DS . 'ajax.php';
});

$f3->run();
