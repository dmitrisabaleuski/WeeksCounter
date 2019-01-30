<?php
/**
 * Created by PhpStorm.
 * User: Dmitri_Sobolevski
 * Date: 23.01.19
 * Time: 14:11
 */

namespace AppBundle\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class LackyController extends Controller {
    /**
     * @Route("/", name="homepage")
     */
    public function homepage() {

        return $this->render( 'homepage.html.twig' );
    }
}
