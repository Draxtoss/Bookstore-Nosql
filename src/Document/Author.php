<?php

namespace App\Document;

/** @MongoDB\EmbeddedDocument */
class Author
{
    /** @MongoDB\Field(type="string") */
    private $Name;
    /** @MongoDB\Field(type="string") */
    private $Sexe;
    /** @MongoDB\Field(type="date") */
    private $BirthDate;
    /** @MongoDB\Field(type="string") */
    private $Nationality;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->Name;
    }

    /**
     * @param mixed $Name
     */
    public function setName($Name): void
    {
        $this->Name = $Name;
    }

    /**
     * @return mixed
     */
    public function getSexe()
    {
        return $this->Sexe;
    }

    /**
     * @param mixed $Sexe
     */
    public function setSexe($Sexe): void
    {
        $this->Sexe = $Sexe;
    }

    /**
     * @return mixed
     */
    public function getBirthDate()
    {
        return $this->BirthDate;
    }

    /**
     * @param mixed $BirthDate
     */
    public function setBirthDate($BirthDate): void
    {
        $this->BirthDate = $BirthDate;
    }

    /**
     * @return mixed
     */
    public function getNationality()
    {
        return $this->Nationality;
    }

    /**
     * @param mixed $Nationality
     */
    public function setNationality($Nationality): void
    {
        $this->Nationality = $Nationality;
    }


}