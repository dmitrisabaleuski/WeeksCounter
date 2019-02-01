<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserDataRepository")
 */
class UserData extends Controller
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    public $taxonomy_user_id;

    /**
     * @ORM\Column(type="string")
     */
    public $birth_data;
    
    /**
     * @ORM\Column(type="string")
     */
    public $year_data;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTaxonomyUserId(): ?int
    {
        return $this->taxonomy_user_id;
    }

    public function setTaxonomyUserId(int $taxonomy_user_id): self
    {
        $this->taxonomy_user_id = $taxonomy_user_id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBirthData() {
        return $this->birth_data;
    }

    /**
     * @param mixed $feelds_data
     */
    public function setBirthData( $birth_data ) {
        $this->birth_data = $birth_data;
    }

    /**
     * @return mixed
     */
    public function getYearData() {
        return $this->year_data;
    }

    /**
     * @param mixed $year_data
     */
    public function setYearData( $year_data ) {
        $this->year_data = $year_data;
    }


}
