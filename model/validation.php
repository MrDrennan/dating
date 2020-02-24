<?php

/**
 * validation.php validates submitted data from forms
 * @author Chad Drennan
 * Date Created: 2/7/2020
 */

/**
 * Class Validation validates submitted data from forms
 * @author Chad Drennan
 * Date Created 2/22/20
 */
class Validation
{
    // Note: must be using PHP 7.1+ for private class constants
    const MIN_VALID_AGE = 18;
    const MAX_VALID_AGE = 118;

    private $_f3;


    /**
     * Validation constructor.
     * @param $f3 object Base class instance for Fat-Free
     */
    public function __construct($f3)
    {
        $this->_f3 = $f3;
    }


    /**
     * Validates submitted data for the personal information form.
     * @return bool true if all submitted data for personal information form is valid
     */
    public function validPersonalInfoForm()
    {
        $isValid = true;

        $allRequired = ['fName', 'lName', 'age', 'phone'];

        foreach ($allRequired as $currRequired) {

            if (empty($this->_f3->get($currRequired))) {
                $isValid = false;
                $this->_f3->push('errors', $currRequired . 'Required');
            }
        }

        $allNames = ['fName', 'lName'];

        foreach ($allNames as $currName) {

            if (!$this->validName($this->_f3->get($currName))) {
                $isValid = false;
                $this->_f3->push('errors', $currName . 'Invalid');
            }
        }

        if (!$this->validAge($this->_f3->get('age'))) {
            $isValid = false;
            $this->_f3->push('errors', 'ageInvalid');
        }

        if (!$this->validPhone($this->_f3->get('phone'))) {
            $isValid = false;
            $this->_f3->push('errors', 'phoneInvalid');
        }
        return $isValid;
    }


    /**
     * Validates submitted data for the profile form.
     * @return bool true if all submitted data for profile form is valid
     */
    public function validProfileForm()
    {
        $isValid = true;

        if (empty($this->_f3->get('email'))) {
            $isValid = false;
            $this->_f3->push('errors', 'emailRequired');
        }

        if (!$this->validEmail($this->_f3->get('email'))) {
            $isValid = false;
            $this->_f3->push('errors', 'emailInvalid');
        }
        return $isValid;
    }


    /**
     * Validates submitted data for the interests form.
     * @return bool true if all submitted data for interests form is valid
     */
    public function validInterestsForm()
    {
        $isValid = true;

        if (!$this->validIndoor()) {
            $isValid = false;
            // Not showing error for spoofed form
        }

        if (!$this->validOutdoor()) {
            $isValid = false;
            // Not showing error for spoofed form
        }

        return $isValid;
    }


    /**
     * Checks that a photo is valid
     * @param $imageIn array image chosen by user to upload
     * @param $picPath string location
     * @return bool true if photo is valid
     */
    public function validPhoto($imageIn, $picPath) {

        if (empty($imageIn['tmp_name'])) {
            $this->_f3->set('photoError', "No photo chosen");
            return false;
        }

        // Check if image file is a actual image
        if (isset($_POST["photo-submit"]) && !getimagesize($imageIn["tmp_name"])) {
            $this->_f3->set('photoError', "Error: File is not an image. File was not uploaded");
            return false;
        }

        // Check if file already exists
        if (file_exists($picPath)) {
            $this->_f3->set('photoError', "Error: File already exists. File was not uploaded");
            return false;
        }

        // Check file size
        if ($imageIn["size"] > 500000) {
            $this->_f3->set('photoError', "Error: File is too large. File was not uploaded");
            return false;
        }

        $imageFileType = strtolower(pathinfo($picPath,PATHINFO_EXTENSION));

        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                && $imageFileType != "gif" ) {
            $this->_f3->set('photoError', "Error: Only JPG, JPEG, PNG & GIF files are allowed. File was not uploaded");
            return false;
        }
        return true;
    }


    /**
     * Checks that a name if not empty contains only alphabetical characters
     * @param $name string of name to check
     * @return bool true if all alphabetical characters or empty
     */
    private function validName($name)
    {
        return empty($name) || ctype_alpha($name);
    }


    /**
     * Checks that string, if not empty, is a number and between 18 and 118 inclusive.
     * @param $age string to check age of
     * @return bool true if all characters are numbers and age between 18 and 118 inclusive or empty string
     */
    private function validAge($age)
    {
        return empty($age) || ctype_digit($age) && self::MIN_VALID_AGE <= $age && $age <= self::MAX_VALID_AGE;
    }


    /**
     * Checks that a phone number, if not empty, follows the correct format.
     * @param $phone string to check format of
     * @return bool true if follows correct format or empty
     */
    private function validPhone($phone)
    {
        return empty($phone)
            || preg_match("/^(\d[\s-]?)?[\(\[\s-]{0,2}?\d{3}[\)\]\s-]{0,2}?\d{3}[\s-]?\d{4}$/i", $phone);
    }


    /**
     * Checks that an email, if not empty, follows the correct format.
     * @param $email string to check format of
     * @return bool true if follows correct format or empty
     */
    private function validEmail($email)
    {
        return empty($email) || filter_var($email, FILTER_VALIDATE_EMAIL);
    }


    /**
     * Checks that each selected interest is an acceptable value of available outdoor interests
     * @return bool true if all valid indoor interests
     */
    private function validOutdoor()
    {
        return $this->validSelections($this->_f3->get('selectedOutdoorInterests'), $this->_f3->get('outdoorInterests'));
    }


    /**
     * Checks that each selected interest is an acceptable value of available indoor interests
     * @return bool true if all valid indoor interests
     */
    private function validIndoor()
    {
        return $this->validSelections($this->_f3->get('selectedIndoorInterests'), $this->_f3->get('indoorInterests'));
    }


    /**
     * Checks that each selected option is an acceptable value of available options
     * @param $selectedOptions string[] selected options to check for validity
     * @param $validOptions string[] valid options to check in
     * @return bool true if selections are all valid options
     */
    private function validSelections($selectedOptions, $validOptions)
    {
        foreach ($selectedOptions as $currOption) {

            // Selection not a valid valid option
            if (!in_array($currOption, $validOptions)) {
                return false;
            }
        }
        return true;
    }
}

