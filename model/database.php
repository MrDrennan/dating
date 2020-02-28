<?php


class Database
{
    private $_dbh;

    function __construct()
    {
        $this->connect();
    }

    public function connect()
    {
        try {
            $this->_dbh = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        }
        catch (PDOException $ex) {
            echo $ex->getMessage();
        }
    }

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
        return $this->_dbh->lastInsertId();
    }

    function getMembers()
    {
        $sql = "SELECT * 
                FROM member 
                ORDER BY lname";

        $statement = $this->_dbh->prepare($sql);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

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

    function getInterests($memberId) {
        $sql = "SELECT * 
                FROM member 
                INNER JOIN member-interest ON member.member_id = member-interest.member_id
                INNER JOIN interest ON member-interest.interest_id = interest.interest_id
                WHERE member_id = :memberId";

        $statement = $this->_dbh->prepare($sql);
        $statement->bindParam(':memberId', $memberId);

        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);
    }
}