<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @MongoDB\Document
 */
class Books
{
    /**
     * @MongoDB\Id
     */
    protected $Id;
    /**
     * @MongoDB\Field(type="string")
     * @Assert\NotBlank
     */
    protected $Title;
    /**
     * @MongoDB\Field(type="integer")
     * @Assert\NotBlank
     */
    protected $Pages;
    /**
     * @MongoDB\Field(type="date")
     */
    protected $PublicationDate;
    /** @MongoDB\EmbedOne(targetDocument="\App\Document\Author") 
     * @Assert\Valid
    */
    protected $Author;

    /**
     * @MongoDB\Field(type="string")
     * @Assert\NotBlank
     */
    protected $Genre;
    /**
     * @MongoDB\Field(type="bool")
     */
    protected $Rented;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $RentedBy;





    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->Id;
    }

    /**
     * @param mixed $Id
     */
    public function setId($Id): void
    {
        $this->Id = $Id;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->Title;
    }

    /**
     * @param mixed $Title
     */
    public function setTitle($Title): void
    {
        $this->Title = $Title;
    }

    /**
     * @return mixed
     */
    public function getPages()
    {
        return $this->Pages;
    }

    /**
     * @param mixed $Pages
     */
    public function setPages($Pages): void
    {
        $this->Pages = $Pages;
    }

    /**
     * @return mixed
     */
    public function getPublicationDate()
    {
        return $this->PublicationDate;
    }

    /**
     * @param mixed $PublicationDate
     */
    public function setPublicationDate($PublicationDate): void
    {
        $this->PublicationDate = $PublicationDate;
    }

    /**
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->Author;
    }

    /**
     * @param mixed $Author
     */
    public function setAuthor($Author): void
    {
        $this->Author = $Author;
    }

    /**
     * @return mixed
     */
    public function getGenre()
    {
        return $this->Genre;
    }

    /**
     * @param mixed $Genre
     */
    public function setGenre($Genre): void
    {
        $this->Genre = $Genre;
    }
        /**
     * @return mixed
     */
    public function getRented()
    {
        return $this->Rented;
    }

    /**
     * @param mixed $Rented
     */
    public function setRented($Availabity): void
    {
        $this->Rented = $Availabity;
    }
    public function getRentedBy()
    {
        return $this->RentedBy;
    }

    /**
     * @param mixed $RentedBy
     */
    public function setRentedBy($Availabity): void
    {
        $this->RentedBy = $Availabity;
    }

}

