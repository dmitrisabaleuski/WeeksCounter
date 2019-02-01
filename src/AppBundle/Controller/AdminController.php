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
        for ( $i = 0; $i < count( $users_data ); $i ++ ) {
            $birth                                       = unserialize( $users_data[ $i ]->feelds_data );
            $data[ $users_data[ $i ]->taxonomy_user_id ] = $birth['birthDate'];
        }

        return $this->render( 'admin/admin_page.html.twig', [
            'users' => $users,
            'data'  => $data,
        ] );
    }

    /**
     * @Route("/admin/user/delete/{id}", name="app_delete_user", methods={"DELETE"}, requirements={"id"="\d+"})
     */

    public function user_delete( $id ) {
        $usersTable = $this->getDoctrine()->getManager();
        $user       = $usersTable->getRepository( Users::class )->find( $id );
        $usersTable->remove( $user );
        $usersTable->flush();

        return $this->redirectToRoute( 'admin' );

    }

    /**
     * @Route("/admin/user/edit/{id}", name="app_edit_user", methods={"GET"}, requirements={"id"="\d+"})
     */

    public function userEdit( $id ) {
        $user = $this->getDoctrine()
                     ->getRepository( Users::class )
                     ->find( $id );

        $data = $this->getDoctrine()
                     ->getRepository( UserData::class )
                     ->findOneBy( [ 'taxonomy_user_id' => $id ] );

        $data = !empty($data) ? unserialize($data->feelds_data)['birthDate']  : '';

        return $this->render( 'admin/manage_user.html.twig', [
            'user' => $user,
            'data' => $data,
        ] );
    }

}
