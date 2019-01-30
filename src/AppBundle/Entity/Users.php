<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UsersRepository")
 */
class Users implements UserInterface {
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     *
     * @ORM\Column(type="string", length=64)
     */
    private $password;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=4096)
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive = true;


    public function getId(): ?int {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * @param mixed $name
     */
    public function setUsername( $username ) {
        $this->username = $username;
    }


    /**
     * @return mixed
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail( $email ) {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword( string $password ): self {
        $this->password = $password;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPlainPassword() {
        return $this->plainPassword;
    }

    /**
     * @param mixed $plainPassword
     */
    public function setPlainPassword( $plainPassword ) {
        $this->plainPassword = $plainPassword;
    }

    public function getSalt() {

        return null;
    }

    public function eraseCredentials() {

    }

    public function isAccountNonExpired() {
        return true;
    }

    public function isAccountNonLocked() {
        return true;
    }

    public function isCredentialsNonExpired() {
        return true;
    }

    public function setRoles( array $role ) {

        $this->roles = $role;
    }

    public function getRoles() {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        /*$roles[] = [
            'ROLE_USER',
            'ROLE_ADMIN',
            ];*/

        return $roles;
    }

    public function isEnabled() {
        return true;
    }

    // сериализация и десериализация должны быть обновлены - см. ниже
    public function serialize() {
        return null;
    }

    public function unserialize( $serialized ) {
        return null;
    }

}
