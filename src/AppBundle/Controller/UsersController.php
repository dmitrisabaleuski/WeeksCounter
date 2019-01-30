<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Entity\Users;
use Symfony\Component\HttpFoundation\Response;

class UsersController extends Controller {
    /**
     * @Route("/main/user/admin", name="Adminusers")
     */
    public function index() {
        $em = $this->getDoctrine()->getManager();

        $user = new Users();
        $user->setUsername('MainAdmin');
        $user->setEmail( 'admin@gmail.com' );
        $user->setPassword( 12345 );
        $user->setRoles( ['ROLE_ADMIN'] );

        $em->persist( $user );

        $em->flush();

        return new Response( 'Saved new user with id ' . $user->getId() );
    }
}
