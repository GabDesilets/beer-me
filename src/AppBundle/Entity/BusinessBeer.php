<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BusinessBeer
 *
 * @ORM\Table(name="business_beers")
 * @ORM\Entity
 */
class BusinessBeer
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="notes", type="text")
     */
    private $notes;

    /**
     * @var BusinessBeerCategory
     *
     * @ORM\ManyToOne(targetEntity="Business")
     * @ORM\JoinColumn(name="business_id", referencedColumnName="id")
     */
    private $business;

    /**
     * @var BusinessBeerCategory
     *
     * @ORM\ManyToOne(targetEntity="BusinessBeerCategory")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category;

    /**
     * BusinessBeer constructor.
     * @param BusinessBeerCategory $category
     * @param string $name
     * @param string $notes
     */
    public function __construct(BusinessBeerCategory $category, $name = '', $notes = '')
    {
        $this->setCategory($category);
        $this->name = $name;
        $this->notes = $notes;
    }

    /**
     * Get Id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get Business beer category
     *
     * @return BusinessBeerCategory
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set Business beer category
     *
     * @param BusinessBeerCategory $category
     */
    public function setCategory(BusinessBeerCategory $category)
    {
        $this->category = $category;
        $this->business = $category->getBusiness();
    }

    /**
     * Get Business
     *
     * @return Business
     */
    public function getBusiness()
    {
        return $this->business;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get notes
     *
     * @return string
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * Set notes
     *
     * @param string $notes
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;
    }
}

