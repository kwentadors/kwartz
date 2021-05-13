<?php

namespace App\Domain\ValueObjects;

class AssetsReportGroup {

    private $id;

    private $name;

    private $entries = [];


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

    public function addAllEntries($entries) {
        foreach($entries as $entry) {
            $this->entries[] = $entry;
        }
    }
	/**
	 *
	 * @return mixed
	 */
	function getEntries() {
		return $this->entries;
	}

	/**
	 *
	 * @param mixed $entries
	 * @return AssetsReportGroup
	 */
	function setEntries($entries): self {
		$this->entries = $entries;
		return $this;
	}
}