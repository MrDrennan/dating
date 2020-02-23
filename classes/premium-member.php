<?php

/**
 * premium-member.php represents o premium member of the site
 * @author Chad Drennan
 * Date Created: 2/21/20
 */

/**
 * Class PremiumMember represents o premium member of the site
 * @author Chad Drennan
 * Date Created: 2/21/20
 */
class PremiumMember extends Member
{
    /**
     * @var array of indoor interests
     */
    private $_indoorInterests;

    /**
     * @var array of outdoor interests
     */
    private $_outdoorInterests;


    /**
     * Gets indoor interests of member
     * @return array indoor interests
     */
    public function getIndoorInterests()
    {
        return $this->_indoorInterests;
    }


    /**
     * Sets indoor interests of member
     * @param array $indoorInterests new indoor interests to set
     */
    public function setIndoorInterests($indoorInterests)
    {
        $this->_indoorInterests = $indoorInterests;
    }


    /**
     * Gets indoor interests of member
     * @return array indoor interests
     */
    public function getOutdoorInterests()
    {
        return $this->_outdoorInterests;
    }


    /**
     * Sets outdoor interests of member
     * @param array $outdoorInterests new outdoor interests to set
     */
    public function setOutdoorInterests($outdoorInterests)
    {
        $this->_outdoorInterests = $outdoorInterests;
    }

}