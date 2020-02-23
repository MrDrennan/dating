<?php
/**
 * Controller for the website
 * @author Chad Drennan
 * Date Created: 1/17/2020
 */

ini_set("display_errors", 1);
error_reporting(E_ALL);

require("vendor/autoload.php");
require("model/validation.php");

session_start();

$f3 = Base::instance();



$controller = new DatingController($f3);

$f3->route('GET /', function() {
   $GLOBALS['controller']->home();
});

$f3->route('GET|POST /create-profile/personal-info', function() {
    $GLOBALS['controller']->personalInfoForm();
});

$f3->route('GET|POST /create-profile/profile', function() {
    $GLOBALS['controller']->profileForm();
});

$f3->route('GET|POST /create-profile/interests', function() {
    $GLOBALS['controller']->interestsForm();
});

$f3->route('GET /create-profile/profile-summary', function() {
    $GLOBALS['controller']->summary();
});

$f3->run();