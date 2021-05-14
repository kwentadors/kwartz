<?php

namespace App\Domain\ValueObjects;

class AssetsReportGroupEntry
{
    private $id;

    private $name;

    private $current_balance;

    private $previous_balance;


    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of current_balance
     */
    public function getCurrentBalance()
    {
        return $this->current_balance;
    }

    /**
     * Set the value of current_balance
     *
     * @return  self
     */
    public function setCurrentBalance($current_balance)
    {
        $this->current_balance = $current_balance;

        return $this;
    }

    /**
     * Get the value of previous_balance
     */
    public function getPreviousBalance()
    {
        return $this->previous_balance;
    }

    /**
     * Set the value of previous_balance
     *
     * @return  self
     */
    public function setPreviousBalance($previous_balance)
    {
        $this->previous_balance = $previous_balance;

        return $this;
    }

    public function getChangePercent() {
        if($this->previous_balance == 0) {
            return 0;
        }

        return ($this->current_balance - $this->previous_balance)/$this->previous_balance * 100;
    }
}
