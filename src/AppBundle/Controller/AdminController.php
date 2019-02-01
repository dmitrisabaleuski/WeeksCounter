<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use AppBundle\Entity\Users;
use AppBundle\Entity\UserData;
use Symfony\Component\HttpFoundation\Request;

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
            $data[ $users_data[ $i ]->taxonomy_user_id ] = [
                'birth' => $users_data[ $i ]->birth_data,
                'years' => $users_data[ $i ]->year_data
            ];
        }

        return $this->render( 'admin/admin_page.html.twig', [
            'users' => $users,
            'data'  => $data,
        ] );
    }

    /**
     * @Route("/admin/new-user", name="app_new_user", methods={"GET"})
     */

    public function newUser() {
        return $this->render( 'admin/new_user_page.html.twig', [
            'response' => '',
        ] );
    }

    /**
     * @Route("/admin/new-user", name="app_save_new_user", methods={"POST"})
     */

    public function newUserInsert( Request $request ) {
        $new_user_data = $request->request->all();
        $role          = [ $new_user_data['userRole'] ];
        if ( $new_user_data['password'] != $new_user_data['repeat_password'] ) {
            return $this->render( 'admin/new_user_page.html.twig', [
                'response' => 'User not register!',
            ] );
        }
        $em      = $this->getDoctrine()->getManager();
        $newUser = new Users();
        $newUser->setUsername( $new_user_data['name'] );
        $newUser->setEmail( $new_user_data['email'] );
        $newUser->setPassword( password_hash( $new_user_data['password'], PASSWORD_DEFAULT ) );
        $newUser->setRoles( $role );
        $em->persist( $newUser );
        $em->flush();

        return $this->render( 'admin/new_user_page.html.twig', [
            'response' => 'User register!',
        ] );
    }

    /**
     * @Route("/admin/user/edit/delete/{id}", name="app_delete_user", methods={"GET"}, requirements={"id"="\d+"})
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

        $data = ! empty( $data ) ? $data->birth_data : '';

        return $this->render( 'admin/manage_user.html.twig', [
            'user' => $user,
            'data' => $data,
            'user_role' => 'admin',
        ] );
    }

    /**
     * @Route("/admin/user/edit/{id}", name="app_update_user", methods={"POST"}, requirements={"id"="\d+"})
     */

    public function accountUpdate( Request $request, $id ) {
        $em       = $this->getDoctrine()->getManager();
        $new_data = $request->request->all();
        $this->userUpdate( $new_data, $id, $em );
        $this->userDataUpdate( $new_data, $id, $em );

        return $this->redirectToRoute( 'app_edit_user', [ 'id' => $id ] );
    }

    public function userUpdate( $new_data, $id, $em ) {

        $user_data = $this->getDoctrine()->getRepository( UserData::class )->findOneBy( [
            'taxonomy_user_id' => $id,
        ] );
        $user_data->setBirthData( $new_data['birthdate'] );
        $em->persist( $user_data );
        $em->flush();

        return;
    }

    public function userDataUpdate( $new_data, $id, $em ) {
        $user = $this->getDoctrine()->getRepository( Users::class )->find( $id );
        $user->setUsername( $new_data['name'] );
        $user->setEmail( $new_data['email'] );
        $user->setRoles( [ $new_data["userRole"] ] );
        $em->persist( $user );
        $em->flush();

        return;
    }

}
