<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Entity\Users;

/**
 * @method Users|null getUsers()
 */
abstract class BaseController extends AbstractController {

    protected function getUser(): Users {
        return parent::getUser();
    }
}
