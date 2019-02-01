<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;
use AppBundle\Entity\Users;
use AppBundle\Entity\UserData;

/**
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */
class AccountController extends BaseController {

    /**
     * @Route("/account", name="app_account", methods={"GET"})
     */
    public function index( LoggerInterface $logger ) {
        $logger->debug( 'Checking account page for ' . $this->getUser()->getEmail() );
        $userID = $this->getUser()->getId();

        $user_data = $this->checkUserData( $userID );

        $birth_data = $user_data['birth_data'] ? $user_data['birth_data'] : '';
        $year_data = $user_data['year_data'] ? $user_data['year_data'] : '';

        return $this->render( 'account/index.html.twig', [
            'birth_data' => $birth_data,
            'year_data' => $year_data,
        ] );
    }

    /**
     * @Route("/account", name="app_calculate", methods={"POST"});
     */
    public function calculate( Request $request ) {
        $data      = $request->request->all();
        $userID    = $this->getUser()->getId();
        $weeksController = new WeeksController();

        $datas = $weeksController->viewGenerate( $data );;

        $render_template = $this->render( 'account/calculator.html.twig', [
            'allWeeks'   => $datas['allWeeks'],
            'livedWeeks' => $datas['livedWeeks']
        ] );

        if ( key_exists( 'data_save', $data ) === true ) {
            $this->dataSave(  $data['date'], $data['years'], $userID );
        }

        return $render_template;
    }

    public function dataSave( $birthDate, $years, $userID ) {

        $em = $this->getDoctrine()->getManager();

        $user_data_check = $this->getDoctrine()->getRepository( UserData::class )->findOneBy( [
            'taxonomy_user_id' => $userID,
        ] );

        $feelds_data      = [
            'birthDate'   => $birthDate,
            'years_count' => $years,

        ];

        $weeks_data = $user_data_check ? $user_data_check : new UserData();
        $weeks_data->setTaxonomyUserId( $userID );
        $weeks_data->setBirthData( $birthDate );
        $weeks_data->setYearData( $years );

        $em->persist( $weeks_data );

        $em->flush();

        return;
    }

    public function checkUserData( $userID ) {
        $user_data = $this->getDoctrine()
                          ->getRepository( UserData::class )
                          ->findOneBy( [
                              'taxonomy_user_id' => $userID,
                          ] );

        return ! empty( $user_data ) ? $user_data : null;
    }

    /**
     * @Route("/account/user/edit/{id}", name="app_user_self_edit", methods={"GET"}, requirements={"id"="\d+"})
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
            'user_role' => 'account',
        ] );
    }
}
