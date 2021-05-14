<?php

namespace App\Domain\ValueObjects;

class AssetsReport
{
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

    public function getBalance()
    {
        return array_reduce($this->groups, function ($sum, $group) {
            return $sum + $group->getBalance();
        });
    }

    public function getPreviousBalance()
    {
        return array_reduce($this->groups, function ($sum, $group) {
            return $sum + $group->getPreviousBalance();
        });
    }


    public function getChangePercent()
    {
        if ($this->getPreviousBalance() == 0) {
            return null;
        }

        return ($this->getBalance() - $this->getPreviousBalance())/$this->getPreviousBalance() * 100;
    }
}
