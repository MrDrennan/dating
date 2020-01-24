<?php
/**
 * Controller for the home page
 * @author Chad Drennan
 * Date Created: 1/17/2020
 */

ini_set("display_errors", 1);
error_reporting(E_ALL);


require("vendor/autoload.php");

$f3 = Base::instance();

$f3->route('GET /', function() {
   $view = new Template();
   echo $view->render('views/home.html');
});

$f3->route('GET /create-profile/personal-info', function() {
   $view = new Template();
   echo $view->render('views/frm-personal-info.html');
});

$f3->run();