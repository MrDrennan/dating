<?php

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

function validInterestsForm($f3) {
    $isValid = true;

    if (!validIndoor($f3->get('selectedIndoorInterests'))) {
        $isValid = false;
        // Not showing error for spoofed form
    }

    if (!validOutdoor($f3->get('selectedOutdoorInterests'))) {
        $isValid = false;
        // Not showing error for spoofed form
    }

    return $isValid;
}

function validName($name) {
    return empty($name) || ctype_alpha($name);
}

function validAge($age) {
    return empty($age) || ctype_digit($age) && 18 <= $age && $age <= 118;
}

function validPhone($phone) {
    return empty($phone)
        || preg_match("/^(\d[\s-]?)?[\(\[\s-]{0,2}?\d{3}[\)\]\s-]{0,2}?\d{3}[\s-]?\d{4}$/i", $phone);
}

function validEmail($email) {
    return empty($email) || filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validOutdoor($selectedInterests) {
    $outdoorInterests = ['hiking', 'biking', 'swimming', 'collecting', 'walking', 'climbing'];

    foreach ($selectedInterests as $currInterest) {

        if (!in_array($currInterest, $outdoorInterests)) {
            return false;
        }
    }
    return true;
}

function validIndoor($selectedInterests) {
    $indoorInterests = ['tv', 'movies', 'cooking', 'board-games', 'puzzles', 'reading', 'playing-cards', 'video-games'];

    foreach ($selectedInterests as $currInterest) {

        if (!in_array($currInterest, $indoorInterests)) {
            return false;
        }
    }
    return true;
}




