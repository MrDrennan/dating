<?php

/**
 * Validates submitted data from forms
 * @author Chad Drennan
 * Date Created: 2/7/2020
 */

define('MIN_VALID_AGE', 18);
define('MAX_VALID_AGE', 118);


/**
 * Validates submitted data for the personal information form.
 * @param $f3 object Base class instance for Fat-Free
 * @return bool true if all submitted data for personal information form is valid
 */
function validPersonalInfoForm($f3) {
    $isValid = true;

    $allRequired = ['fName', 'lName', 'age', 'phone'];

    foreach ($allRequired as $currRequired) {

        if (empty($f3->get($currRequired))) {
            $isValid = false;
            $f3->push('errors', $currRequired . 'Required');
        }
    }

    $allNames = ['fName', 'lName'];

    foreach ($allNames as $currName) {

        if (!validName($f3->get($currName))) {
            $isValid = false;
            $f3->push('errors', $currName . 'Invalid');
        }
    }

    if (!validAge($f3->get('age'))) {
        $isValid = false;
        $f3->push('errors', 'ageInvalid');
    }

    if (!validPhone($f3->get('phone'))) {
        $isValid = false;
        $f3->push('errors', 'phoneInvalid');
    }
    return $isValid;
}


/**
 * Validates submitted data for the profile form.
 * @param $f3 object Base class instance for Fat-Free
 * @return bool true if all submitted data for profile form is valid
 */
function validProfileForm($f3) {
    $isValid = true;

    if (empty($f3->get('email'))) {
        $isValid = false;
        $f3->push('errors', 'emailRequired');
    }

    if (!validEmail($f3->get('email'))) {
        $isValid = false;
        $f3->push('errors', 'emailInvalid');
    }
    return $isValid;
}


/**
 * Validates submitted data for the interests form.
 * @param $f3 object Base class instance for Fat-Free
 * @return bool true if all submitted data for interests form is valid
 */
function validInterestsForm($f3) {
    $isValid = true;

    if (!validIndoor($f3)) {
        $isValid = false;
        // Not showing error for spoofed form
    }

    if (!validOutdoor($f3)) {
        $isValid = false;
        // Not showing error for spoofed form
    }

    return $isValid;
}


/**
 * Checks that a name if not empty contains only alphabetical characters
 * @param $name string of name to check
 * @return bool true if all alphabetical characters or empty
 */
function validName($name) {
    return empty($name) || ctype_alpha($name);
}


/**
 * Checks that string, if not empty, is a number and between 18 and 118 inclusive.
 * @param $age string to check age of
 * @return bool true if all characters are numbers and age between 18 and 118 inclusive or empty string
 */
function validAge($age) {
    return empty($age) || ctype_digit($age) && MIN_VALID_AGE <= $age && $age <= MAX_VALID_AGE;
}


/**
 * Checks that a phone number, if not empty, follows the correct format.
 * @param $phone string to check format of
 * @return bool true if follows correct format or empty
 */
function validPhone($phone) {
    return empty($phone)
        || preg_match("/^(\d[\s-]?)?[\(\[\s-]{0,2}?\d{3}[\)\]\s-]{0,2}?\d{3}[\s-]?\d{4}$/i", $phone);
}


/**
 * Checks that an email, if not empty, follows the correct format.
 * @param $email string to check format of
 * @return bool true if follows correct format or empty
 */
function validEmail($email) {
    return empty($email) || filter_var($email, FILTER_VALIDATE_EMAIL);
}


/**
 * Checks that each selected interest is an acceptable value of available outdoor interests
 * @param $f3 object Base class instance
 * @return bool true if all valid indoor interests
 */
function validOutdoor($f3) {
    return validSelections($f3->get('selectedOutdoorInterests'), $f3->get('outdoorInterests'));
}


/**
 * Checks that each selected interest is an acceptable value of available indoor interests
 * @param $f3 object Base class instance
 * @return bool true if all valid indoor interests
 */
function validIndoor($f3) {
    return validSelections($f3->get('selectedIndoorInterests'), $f3->get('indoorInterests'));
}

/**
 * Checks that each selected option is an acceptable value of available options
 * @param $selectedOptions string[] selected options to check for validity
 * @param $validOptions string[] valid options to check in
 * @return bool true if selections are all valid options
 */
function validSelections($selectedOptions, $validOptions) {
    foreach ($selectedOptions as $currOption) {

        // Selection not a valid valid option
        if (!in_array($currOption, $validOptions)) {
            return false;
        }
    }
    return true;
}




