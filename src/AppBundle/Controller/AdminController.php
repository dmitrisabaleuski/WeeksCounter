<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

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

        return $this->render( 'admin/admin_page.html.twig', [
            'controller_name' => 'AdminController',
        ] );
    }
}
