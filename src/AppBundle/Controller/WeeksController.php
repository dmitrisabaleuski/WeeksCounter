<?php
/**
 * Created by PhpStorm.
 * User: Dmitri_Sobolevski
 * Date: 25.01.19
 * Time: 22:47
 */

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class WeeksController extends Controller {

    /**
     * @var int
     */

    public $weeksCount;

    /**
     * @param integer $weeksCount
     *
     * @return integer
     */

    function __construct() {
        $this->weeksCount = 365 / 7;
    }

    public function viewGenerate( $data ) {
        $now                 = new \DateTime(); // Date today
        $birthDate           = new \DateTime( $data['date'] );
        $interval            = $now->diff( $birthDate );
        $livedWeeks          = round( $interval->days / 7 );
        $allWeeks            = round( $this->weeksCount * $data['years'] );

        return [ 'livedWeeks' =>$livedWeeks, 'allWeeks' =>$allWeeks];
    }
}