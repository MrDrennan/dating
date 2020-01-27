<?php
/**
 * Controller for the home page
 * @author Chad Drennan
 * Date Created: 1/17/2020
 */

ini_set("display_errors", 1);
error_reporting(E_ALL);

session_start();


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

$f3->route('POST /create-profile/profile', function() {
    $_SESSION['fName'] = trim($_POST['f-name']);
    $_SESSION['lName'] = trim($_POST['l-name']);
    $_SESSION['age'] = trim($_POST['age']);
    $_SESSION['gender'] = trim($_POST['age']);
    $_SESSION['phone'] = trim($_POST['phone']);

    $view = new Template();
    echo $view->render('views/frm-profile.html');
});

$f3->route('POST /create-profile/interests', function() {
    $_SESSION['email'] = trim($_POST['email']);
    $_SESSION['state'] = trim($_POST['state']);
    $_SESSION['seekingGender'] = trim($_POST['seeking-gender']);
    $_SESSION['bio'] = trim($_POST['bio']);

    $view = new Template();
    echo $view->render('views/frm-interests.html');
});

//TODO try to make route work without /create-profile part
$f3->route('POST /create-profile/profile-summary', function() {

    $interests = '';
    if (isset($_POST['indoor-interests'])) {
        $interests = implode(' ', $_POST['indoor-interests']) . ' ';
    }
    if (isset($_POST['outdoor-interests'])) {
        $interests .= implode(' ', $_POST['outdoor-interests']);
    }

    $_SESSION['interests'] = str_replace('-', ' ', $interests);

    $view = new Template();
    echo $view->render('views/summary.html');
});

$f3->run();