<?php
/**
 * member.php represents basic member for the site
 * @author Chad Drennan
 * 2/21/20
 */


/**
 * Class Member represents a basic member for the dating site
 * @author Chad Drennan
 * 2/21/20
 */
class Member
{
    /**
     * @var string First name of member
     */
    private $_fName;

    /**
     * @var string Last name of member
     */
    private $_lName;

    /**
     * @var int Age of member
     */
    private $_age;

    /**
     * @var string Gender of member
     */
    private $_gender;

    /**
     * @var string Phone number of member
     */
    private $_phone;

    /**
     * @var string Email address of member
     */
    private $_email;

    /**
     * @var string State abbreviation of member's address
     */
    private $_state;

    /**
     * @var string Gender the member is seeking
     */
    private $_seeking;

    /**
     * @var string Biography of member
     */
    private $_bio;

    /**
     * Member constructor.
     * @param $fName string  member's first name
     * @param $lName string member's last name
     * @param $age int member's age
     * @param $gender string member's gender
     * @param $phone string member's phone number
     */
    public function __construct($fName, $lName, $age, $gender, $phone) {
        $this->_fName = $fName;
        $this->_lName = $lName;
        $this->_age = $age;
        $this->_gender = $gender;
        $this->_phone = $phone;
    }

    /**
     * Gets member's first name
     * @return string first name
     */
    public function getFName()
    {
        return $this->_fName;
    }

    /**
     * Sets member's first name
     * @param string $fName new first name
     */
    public function setFName($fName)
    {
        $this->_fName = $fName;
    }

    /**
     * Gets member's last name
     * @return string last name
     */
    public function getLName()
    {
        return $this->_lName;
    }

    /**
     * Sets member's last name
     * @param string $lName new last name
     */
    public function setLName($lName)
    {
        $this->_lName = $lName;
    }

    /**
     * Gets member's age
     * @return int member's age
     */
    public function getAge()
    {
        return $this->_age;
    }

    /**
     * Sets member's age
     * @param int $age member's new age
     */
    public function setAge($age)
    {
        $this->_age = $age;
    }

    /**
     * Gets member's gender
     * @return string member's gender
     */
    public function getGender()
    {
        return $this->_gender;
    }

    /**
     * Sets member's gender
     * @param string $gender member's new gender
     */
    public function setGender($gender)
    {
        $this->_gender = $gender;
    }

    /**
     * Gets member's phone number
     * @return string phone number
     */
    public function getPhone()
    {
        return $this->_phone;
    }

    /**
     * Sets member's phone number
     * @param string $phone new phone number
     */
    public function setPhone($phone)
    {
        $this->_phone = $phone;
    }

    /**
     * Gets member's email
     * @return string email
     */
    public function getEmail()
    {
        return $this->_email;
    }

    /**
     * Sets member's email
     * @param string $email new email
     */
    public function setEmail($email)
    {
        $this->_email = $email;
    }

    /**
     * Gets member's state of US states
     * @return string member's state
     */
    public function getState()
    {
        return $this->_state;
    }

    /**
     * Sets member's state of US states
     * @param string $state member's new state
     */
    public function setState($state)
    {
        $this->_state = $state;
    }

    /**
     * Gets gender member is seeking
     * @return string gender member is seeking
     */
    public function getSeeking()
    {
        return $this->_seeking;
    }

    /**
     * Sets gender that member is seeking
     * @param string $seeking gender member is seeking
     */
    public function setSeeking($seeking)
    {
        $this->_seeking = $seeking;
    }

    /**
     * Gets member's biography
     * @return string biography
     */
    public function getBio()
    {
        return $this->_bio;
    }

    /**
     * Sets member's biography
     * @param string $bio new biography
     */
    public function setBio($bio)
    {
        $this->_bio = $bio;
    }
}