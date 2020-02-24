<?
/**
 * dating-controller.php processes data and shows views for routes
 * @author Chad Drennan
 * Date created: 2/21/20
 */


/**
 * Class DatingController processes data and shows views for routes
 * @author Chad Drennan
 * Date created: 2/2120
 */
class DatingController
{

    /**
     * @var object Base instance for Fat-Free Framework
     */
    private $_f3;

    /**
     * @var array state name to abbreviation map
     */
    private $_states;


    /**
     * DatingController constructor.
     * @param $f3 object Base instance for Fat-Free Framework
     */
    public function __construct($f3)
    {
        $this->_states = [
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
            'WY' => 'Wyoming'
        ];

        $f3->set('errors', []);
        $this->_f3 = $f3;
    }


    /**
     * Shows home page
     */
    public function home()
    {
        echo (new Template())->render('views/home.html');
    }


    /**
     * Shows form and processes data from personal information sign up form
     */
    public function personalInfoForm()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            // Get data from form
            $fName = trim($_POST['f-name']);
            $lName = trim($_POST['l-name']);
            $age = trim($_POST['age']);
            $gender = $_POST['gender'];
            $phone = trim($_POST['phone']);
            $premMember = $_POST['prem-member'];

            // Add to hive
            $this->_f3->set('fName', $fName);
            $this->_f3->set('lName', $lName);
            $this->_f3->set('age', $age);
            $this->_f3->set('gender', $gender);
            $this->_f3->set('phone', $phone);
            $this->_f3->set('premMember', $premMember);

            if ((new Validation($this->_f3))->validPersonalInfoForm()) {

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

                $this->_f3->reroute('/create-profile/profile');
            }
        }
        echo (new Template())->render('views/frm-personal-info.html');
    }


    /**
     * Shows form and processes data from profile information sign up form
     */
    public function profileForm()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            // Collect data
            $email = trim($_POST['email']);
            $state = $_POST['state'];
            $seekingGender = $_POST['seeking-gender'];
            $bio = trim($_POST['bio']);

            // Add to hive
            $this->_f3->set('email', $email);
            $this->_f3->set('state', $state);
            $this->_f3->set('seekingGender', $seekingGender);
            $this->_f3->set('bio', $bio);

            // User is uploading photo
            if (isset($_POST['photo-submit'])) {
                $this->uploadPhoto();
            }
            // User is submitting form
            else if ((new Validation($this->_f3))->validProfileForm()) {

                // Set member profile info to session
                $member = $_SESSION['member'];

                $member->setEmail($email);
                $member->setState($state);
                $member->setSeeking($seekingGender);
                $member->setBio($bio);

                $_SESSION['member'] = $member;

                // If premium member go to interests form
                if (isset($_SESSION['isPremMember'])) {
                    $this->_f3->reroute('/create-profile/interests');
                }
                else { // go to summary
                    $this->_f3->reroute('/create-profile/profile-summary');
                }
            }
        }
        // Add states to hive for template
        $this->_f3->set('states', $this->_states);

        echo (new Template())->render('views/frm-profile.html');
    }


    /**
     * Uploads photo chosen by user if valid
     */
    private function uploadPhoto()
    {
        $imageIn = $_FILES['photo'];
        $picPath = 'images/uploads/' . basename($imageIn["name"]);

        // Upload validated photo
        if ((new Validation($this->_f3))->validPhoto($imageIn, $picPath)) {
            if (move_uploaded_file($imageIn["tmp_name"], $picPath)) {
                $this->_f3->set('photoConfirm', 'The file ' . basename($imageIn['name']) . ' has been uploaded.');
                $_SESSION['picPath'] = $picPath;
            } else {
                $this->_f3->set('photoError', 'There was an error uploading your file.');
            }
        }
    }

    /**
     * Shows form and processes data from interests sign up form
     */
    public function interestsForm()
    {
        $indoorInterests = ['tv', 'movies', 'cooking', 'board-games', 'puzzles', 'reading', 'playing-cards', 'video-games'];
        $outdoorInterests = ['hiking', 'biking', 'swimming', 'collecting', 'walking', 'climbing'];

        $this->_f3->set('indoorInterests', $indoorInterests);
        $this->_f3->set('outdoorInterests', $outdoorInterests);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            // Collect data
            $selectedIndoorInterests = isset($_POST['indoor-interests']) ? $_POST['indoor-interests'] : [];
            $selectedOutdoorInterests = isset($_POST['outdoor-interests']) ? $_POST['outdoor-interests'] : [];

            // Add to hive
            $this->_f3->set('selectedIndoorInterests', $selectedIndoorInterests);
            $this->_f3->set('selectedOutdoorInterests', $selectedOutdoorInterests);

            if ((new Validation($this->_f3))->validInterestsForm()) {

                $member = $_SESSION['member'];

                // Set member interests
                $member->setIndoorInterests($selectedIndoorInterests);
                $member->setOutdoorInterests($selectedOutdoorInterests);

                $_SESSION['member'] = $member;

                $this->_f3->reroute('/create-profile/profile-summary');
            }
        }
        echo (new Template())->render('views/frm-interests.html');
    }


    /**
     * Shows summary of user's data after signing up
     */
    public function summary()
    {
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

            $this->_f3->set('interests',  str_replace('-', ' ', $interests));
        }

        // Get state name from abbr to state name map
        $state = $this->_states[$member->getState()];
        $this->_f3->set('state', $state);

        $this->_f3->set('seekingGender', ucfirst($member->getSeeking()));
        $this->_f3->set('member', $member);

        echo (new Template())->render('views/summary.html');

        // Destroy session after profile is created
        session_destroy();
        $_SESSION = [];
    }
}