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
    public $feelds_data;

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
    public function getFeeldsData() {
        return $this->feelds_data;
    }

    /**
     * @param mixed $feelds_data
     */
    public function setFeeldsData( $feelds_data ) {
        $this->feelds_data = $feelds_data;
    }


}
