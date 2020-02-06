<?php
/**
 * Controller for the home page
 * @author Chad Drennan
 * Date Created: 1/17/2020
 */

ini_set("display_errors", 1);
error_reporting(E_ALL);

require("vendor/autoload.php");
require("model/validation.php");

session_start();

$f3 = Base::instance();

$states = [
    'AL' => 'Alabama',
    'AK' => 'Alaska',
    'AZ' => 'Arizona',
    'AR' => 'Arkansas',
    'CA' => 'California',
    'CO' => 'Colorado',
    'CT' => 'Connecticut',
    'DE' => 'Delaware',
    'DC' => 'District Of Columbia',
    'FL' => 'Florida',
    'GA' => 'Georgia',
    'HI' => 'Hawaii',
    'ID' => 'Idaho',
    'IL' => 'Illinois',
    'IN' => 'Indiana',
    'IA' => 'Iowa',
    'KS' => 'Kansas',
    'KY' => 'Kentucky',
    'LA' => 'Louisiana',
    'ME' => 'Maine',
    'MD' => 'Maryland',
    'MA' => 'Massachusetts',
    'MI' => 'Michigan',
    'MN' => 'Minnesota',
    'MS' => 'Mississippi',
    'MO' => 'Missouri',
    'MT' => 'Montana',
    'NE' => 'Nebraska',
    'NV' => 'Nevada',
    'NH' => 'New Hampshire',
    'NJ' => 'New Jersey',
    'NM' => 'New Mexico',
    'NY' => 'New York',
    'NC' => 'North Carolina',
    'ND' => 'North Dakota',
    'OH' => 'Ohio',
    'OK' => 'Oklahoma',
    'OR' => 'Oregon',
    'PA' => 'Pennsylvania',
    'RI' => 'Rhode Island',
    'SC' => 'South Carolina',
    'SD' => 'South Dakota',
    'TN' => 'Tennessee',
    'TX' => 'Texas',
    'UT' => 'Utah',
    'VT' => 'Vermont',
    'VA' => 'Virginia',
    'WA' => 'Washington',
    'WV' => 'West Virginia',
    'WI' => 'Wisconsin',
    'WY' => 'Wyoming',
];

$f3->set('states', $states);
$f3->set('errors', []);

$f3->route('GET /', function() {
   $view = new Template();
   echo $view->render('views/home.html');
});


$f3->route('GET|POST /create-profile/personal-info', function($f3) {

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        // Get data from form
        $fName = trim($_POST['f-name']);
        $lName = trim($_POST['l-name']);
        $age = trim($_POST['age']);
        $gender = trim($_POST['gender']);
        $phone = trim($_POST['phone']);

        // Add to hive
        $f3->set('fName', $fName);
        $f3->set('lName', $lName);
        $f3->set('age', $age);
        $f3->set('gender', $gender);
        $f3->set('phone', $phone);

        if (validPersonalInfoForm($f3)) {

            // Add to session
            $_SESSION['fName'] = $fName;
            $_SESSION['lName'] = $lName;
            $_SESSION['age'] = $age;
            $_SESSION['gender'] = $gender;
            $_SESSION['phone'] = $phone;

            $f3->reroute('/create-profile/profile');
        }
    }

   $view = new Template();
   echo $view->render('views/frm-personal-info.html');
});

$f3->route('GET|POST /create-profile/profile', function($f3) {
    $_SESSION['fName'] = trim($_POST['f-name']);
    $_SESSION['lName'] = trim($_POST['l-name']);
    $_SESSION['age'] = trim($_POST['age']);
    $_SESSION['gender'] = ucfirst($_POST['gender']);
    $_SESSION['phone'] = trim($_POST['phone']);



    $view = new Template();
    echo $view->render('views/frm-profile.html');
});

$f3->route('POST /create-profile/interests', function($f3) {

    $indoorInterests = ['tv', 'movies', 'cooking', 'board-games', 'puzzles', 'reading', 'playing-cards', 'video-games'];
    $outdoorInterests = ['hiking', 'biking', 'swimming', 'collecting', 'walking', 'climbing'];

    $f3->set('indoorInterests', $indoorInterests);
    $f3->set('outdoorInterests', $outdoorInterests);

    $_SESSION['email'] = trim($_POST['email']);
    $_SESSION['state'] = $f3->get('states')[$_POST['state']];
    $_SESSION['seekingGender'] = ucfirst($_POST['seeking-gender']);
    $_SESSION['bio'] = trim($_POST['bio']);

    $view = new Template();
    echo $view->render('views/frm-interests.html');
});


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