<?php

namespace App\Domain\ValueObjects;

class AssetsReport {

    private $groups = [];

    /**
     * Get the value of groups
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * Set the value of groups
     *
     * @return  self
     */
    public function setGroups($groups)
    {
        $this->groups = $groups;

        return $this;
    }
}