<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use AppBundle\Entity\Users;
use AppBundle\Entity\UserData;

/**
 * Require ROLE_ADMIN for *every* controller method in this class.
 *
 * @IsGranted("ROLE_ADMIN")
 */
class AdminController extends Controller {
    /**
     * @Route("/admin", name="admin")
     * @IsGranted("ROLE_ADMIN")
     */

    public function index() {
        $this->denyAccessUnlessGranted( 'ROLE_ADMIN', null, 'Unable to access this page!' );

        $users = $this->getDoctrine()
                      ->getRepository( Users::class )
                      ->findAll();

        $users_data = $this->getDoctrine()
                           ->getRepository( UserData::class )
                           ->findAll();

        return $this->render( 'admin/admin_page.html.twig', [
            'controller_name' => 'AdminController',
            'users'           => $users,
            'data'            => $users_data,
        ] );
    }

}
