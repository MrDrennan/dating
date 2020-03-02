<?php

/**
 * database.php performs database operations for tables associated with members
 * @author Chad Drennan
 * Date Created: 2/28/2020
 */

/* Create Table statements

    CREATE TABLE `member` (
     `member_id` int(11) NOT NULL AUTO_INCREMENT,
     `fname` varchar(30) NOT NULL,
     `lname` varchar(30) NOT NULL,
     `age` tinyint(4) NOT NULL,
     `gender` char(1) DEFAULT NULL,
     `phone` varchar(30) NOT NULL,
     `email` varchar(80) NOT NULL,
     `state` char(2) DEFAULT NULL,
     `seeking` char(1) DEFAULT NULL,
     `bio` varchar(255) DEFAULT NULL,
     `premium` tinyint(1) NOT NULL,
     `image` varchar(255) NOT NULL,
     PRIMARY KEY (`member_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8

    CREATE TABLE `interest` (
     `interest_id` int(11) NOT NULL AUTO_INCREMENT,
     `interest` varchar(50) NOT NULL,
     `type` varchar(50) NOT NULL,
     PRIMARY KEY (`interest_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4

    CREATE TABLE `member-interest` (
     `member_id` int(11) NOT NULL,
     `interest_id` int(11) NOT NULL,
     PRIMARY KEY (`member_id`,`interest_id`),
     KEY `member_id` (`member_id`),
     KEY `interest_id` (`interest_id`),
     CONSTRAINT `member-interest_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `member` (`member_id`),
     CONSTRAINT `member-interest_ibfk_2` FOREIGN KEY (`interest_id`) REFERENCES `interest` (`interest_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4

 */

require_once('/home/cdrennan/config-grc.php');


/**
 * Class Database. Performs database operations for tables associated with members
 * @author Chad Drennan
 * Date Created: 2/28/2020
 */
class Database
{
    /**
     * @var Db connection object
     */
    private $_dbh;


    /**
     * Database constructor.
     */
    function __construct()
    {
        $this->connect();
    }


    /**
     * Connects to the database
     */
    public function connect()
    {
        try {
            $this->_dbh = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        }
        catch (PDOException $ex) {
            echo $ex->getMessage();
        }
    }


    /**
     * Inserts a member into the member table. Inserts member's interests to linking table
     * @param $member object represents member and contains member data
     */
    function insertMember($member)
    {
        $sql = "INSERT INTO member (fname, lname, age, gender, phone, email, state, seeking, bio, premium, image)
                VALUES (:fname, :lname, :age, :gender, :phone, :email, :state, :seeking, :bio, :premium, :image)";

        $statement  = $this->_dbh->prepare($sql);

        $statement->bindParam(':fname', $member->getFName());
        $statement->bindParam(':lname', $member->getLName());
        $statement->bindParam(':age', $member->getAge());
        $statement->bindParam(':gender', $member->getGender());
        $statement->bindParam(':phone', $member->getPhone());
        $statement->bindParam(':email', $member->getEmail());
        $statement->bindParam(':state', $member->getState());
        $statement->bindParam(':seeking', $member->getSeeking());
        $statement->bindParam(':bio', $member->getBio());

        $isPremium = (get_class($member) == "PremiumMember") ? 1 : 0;
        $statement->bindParam(':premium', $isPremium);
        $statement->bindParam(':image', $member->getPicPath());

        $statement->execute();

        $memberId = $this->_dbh->lastInsertId();

        if ($isPremium) {
            foreach ($member->getIndoorInterests() as $currInterest) {
                $interestId = $this->getInterestId($currInterest)['interest_id'];
                $this->insertMemberInterest($memberId, $interestId);
            }

            foreach ($member->getOutdoorInterests() as $currInterest) {
                $interestId = $this->getInterestId($currInterest)['interest_id'];
                $this->insertMemberInterest($memberId, $interestId);
            }
        }
    }


    /**
     * Inserts link to interest table from member table in the member-interest table
     * @param $memberId number id of member
     * @param $interestId id of interest
     */
    function insertMemberInterest($memberId, $interestId)
    {

        $sql = "INSERT INTO `member-interest` (member_id, interest_id)
                VALUES (:memberId, :interestId)";

        $statement = $this->_dbh->prepare($sql);
        $statement->bindParam(':memberId', $memberId);
        $statement->bindParam(':interestId', $interestId);

        $statement->execute();
    }


    /**
     * Gets interest id with the interest name
     * @param $interest string name of interest
     * @return mixed id of interest
     */
    function getInterestId($interest)
    {
        $sql = "SELECT interest_id
                FROM interest
                WHERE interest = :interest";

        $statement = $this->_dbh->prepare($sql);
        $statement->bindParam(':interest', $interest);

        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);
    }


    /**
     * Retrieves all members and their data from the member table
     * @return array all members and their data from the member table
     */
    function getMembers()
    {
        $sql = "SELECT * 
                FROM member 
                ORDER BY lname";

        $statement = $this->_dbh->prepare($sql);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * Returns all member data from member table with passed in member id
     * @param $memberId number iD of member to get data from
     * @return mixed member data
     */
    function getMember($memberId)
    {
        $sql = "SELECT * 
                FROM member 
                WHERE member_id = :memberId";

        $statement = $this->_dbh->prepare($sql);
        $statement->bindParam(':memberId', $memberId);

        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);
    }


    /**
     * Retrieves a member's interests
     * @param $memberId number Id of member to get interests
     * @return array interests query result
     */
    function getInterests($memberId)
    {
        $sql = "SELECT interest.interest 
                FROM member 
                INNER JOIN `member-interest` ON member.member_id = `member-interest`.member_id
                INNER JOIN interest ON `member-interest`.interest_id = interest.interest_id
                WHERE member.member_id = :memberId";

        $statement = $this->_dbh->prepare($sql);
        $statement->bindParam(':memberId', $memberId);

        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}