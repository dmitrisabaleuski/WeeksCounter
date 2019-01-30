<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;
use AppBundle\Entity\UserData;

/**
 * @IsGranted("ROLE_USER")
 */
class AccountController extends BaseController {

    /**
     * @Route("/account", name="app_account", methods={"GET"})
     */
    public function index( LoggerInterface $logger ) {
        $logger->debug( 'Checking account page for ' . $this->getUser()->getEmail() );
        $userID = $this->getUser()->getId();

        $user_data = $this->checkUserData( $userID );
        $user_data = ! empty( $user_data->feelds_data ) ? unserialize( $user_data->feelds_data ) : '';

        return $this->render( 'account/index.html.twig', [
            'user_data' => $user_data,
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
        $weeks_data->setFeeldsData( serialize( $feelds_data ) );

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
}
