<?php
/**
 * Created by PhpStorm.
 * User: Dmitri_Sobolevski
 * Date: 27.01.19
 * Time: 22:24
 */

namespace AppBundle\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use AppBundle\Repository\UsersRepository;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Http\Util\TargetPathTrait;


class LoginFormAuthenticator extends AbstractFormLoginAuthenticator {

    use TargetPathTrait;

    private $userRepository;
    private $router;
    private $csrfTokenManager;

    public function __construct( UsersRepository $userRepository, RouterInterface $router, CsrfTokenManagerInterface $csrfTokenManager ) {
        $this->router           = $router;
        $this->userRepository   = $userRepository;
        $this->csrfTokenManager = $csrfTokenManager;
    }

    public function supports( Request $request ) {
        return $request->attributes->get( '_route' ) === 'userAuthorization' && $request->isMethod( 'POST' );
    }

    public function getCredentials( Request $request ) {
        $credentials = [
            'email'      => $request->request->get( 'email' ),
            'password'   => $request->request->get( 'password' ),
            'csrf_token' => $request->request->get( '_csrf_token' ),
        ];
        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['email']
        );

        return $credentials;
    }

    public function getUser( $credentials, UserProviderInterface $userProvider ) {
        $token = new CsrfToken( 'authenticate', $credentials['csrf_token'] );
        if ( ! $this->csrfTokenManager->isTokenValid( $token ) ) {
            throw new InvalidCsrfTokenException();
        }

        return $this->userRepository->findOneBy( [ 'email' => $credentials['email'] ] );
    }

    public function checkCredentials( $credentials, UserInterface $user ) {
        return true;
    }

    public function onAuthenticationSuccess( Request $request, TokenInterface $token, $providerKey ) {
        if ( $targetPath = $this->getTargetPath( $request->getSession(), $providerKey ) ) {
            return new RedirectResponse( $targetPath );
        }

        return new RedirectResponse( $this->router->generate( 'homepage' ) );
    }

    protected function getLoginUrl() {
        return $this->router->generate( 'userAuthorization' );
    }

}