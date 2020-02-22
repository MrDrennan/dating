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
   echo Template::instance()->render('views/home.html');
});


$f3->route('GET|POST /create-profile/personal-info', function($f3) {

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        // Get data from form
        $fName = trim($_POST['f-name']);
        $lName = trim($_POST['l-name']);
        $age = trim($_POST['age']);
        $gender = $_POST['gender'];
        $phone = trim($_POST['phone']);
        $premMember = $_POST['prem-member'];

        // Add to hive
        $f3->set('fName', $fName);
        $f3->set('lName', $lName);
        $f3->set('age', $age);
        $f3->set('gender', $gender);
        $f3->set('phone', $phone);
        $f3->set('premMember', $premMember);

        if (validPersonalInfoForm($f3)) {

            // Set premium or regular member objects
            if (isset($premMember)) {
                $member = new PremiumMember($fName, $lName, $age, $gender, $phone);
            }
            else {
                $member = new Member($fName, $lName, $age, $gender, $phone);
            }

            // Add to session
            $_SESSION['member'] = $member;
            $_SESSION['isPremMember'] = $premMember;

            $f3->reroute('/create-profile/profile');
        }
    }
   echo Template::instance()->render('views/frm-personal-info.html');
});

$f3->route('GET|POST /create-profile/profile', function($f3) {

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        // Collect data
        $email = trim($_POST['email']);
        $state = $_POST['state'];
        $seekingGender = $_POST['seeking-gender'];
        $bio = trim($_POST['bio']);

        // Add to hive
        $f3->set('email', $email);
        $f3->set('state', $state);
        $f3->set('seekingGender', $seekingGender);
        $f3->set('bio', $bio);

        if (validProfileForm($f3)) {

            // Set member profile info to session
            $member = $_SESSION['member'];

            $member->setEmail($email);
            $member->setState($state);
            $member->setSeeking($seekingGender);
            $member->setBio($bio);

            $_SESSION['member'] = $member;

            // If premium member go to interests form
            if (isset($_SESSION['isPremMember'])) {
                $f3->reroute('/create-profile/interests');
            }
            else { // go to summary
                $f3->reroute('/create-profile/profile-summary');
            }
        }
    }
    echo Template::instance()->render('views/frm-profile.html');
});

$f3->route('GET|POST /create-profile/interests', function($f3) {

    $indoorInterests = ['tv', 'movies', 'cooking', 'board-games', 'puzzles', 'reading', 'playing-cards', 'video-games'];
    $outdoorInterests = ['hiking', 'biking', 'swimming', 'collecting', 'walking', 'climbing'];

    $f3->set('indoorInterests', $indoorInterests);
    $f3->set('outdoorInterests', $outdoorInterests);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        // Collect data
        $selectedIndoorInterests = isset($_POST['indoor-interests']) ? $_POST['indoor-interests'] : [];
        $selectedOutdoorInterests = isset($_POST['outdoor-interests']) ? $_POST['outdoor-interests'] : [];

        // Add to hive
        $f3->set('selectedIndoorInterests', $selectedIndoorInterests);
        $f3->set('selectedOutdoorInterests', $selectedOutdoorInterests);

        if (validInterestsForm($f3)) {

            $member = $_SESSION['member'];

            // Set member interests
            $member->setIndoorInterests($selectedIndoorInterests);
            $member->setOutdoorInterests($selectedOutdoorInterests);

            $_SESSION['member'] = $member;

            $f3->reroute('/create-profile/profile-summary');
        }
    }
    echo Template::instance()->render('views/frm-interests.html');
});


$f3->route('GET /create-profile/profile-summary', function($f3) {

    $member = $_SESSION['member'];

    // Format interests if premium member
    if (isset($_SESSION['isPremMember'])) {
        $interests = '';
        if (!empty($member->getIndoorInterests())) {
            $interests = implode(' ', $member->getIndoorInterests()) . ' ';
        }
        if (!empty($member->getOutdoorInterests())) {
            $interests .= implode(' ', $member->getOutdoorInterests());
        }

        $f3->set('interests',  str_replace('-', ' ', $interests));
    }

    // Get state name from abbr to state name map
    $state = $f3->get('states')[$member->getState()];
    $f3->set('state', $state);

    $f3->set('seekingGender', ucfirst($member->getSeeking()));
    $f3->set('member', $member);

    echo Template::instance()->render('views/summary.html');
});

$f3->run();