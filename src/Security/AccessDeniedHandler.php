<?php

namespace App\Security;

use FOS\UserBundle\Model\User;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

/**
 * Class AccessDeniedHandler
 * @package App\Security
 */
class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
    /** @var TokenStorageInterface */
    private $tokenStorage;

    /** @var RouterInterface */
    private $router;

    /**
     * RedirectUserListener constructor.
     * @param TokenStorageInterface $tokenStorage
     * @param RouterInterface $router
     */
    public function __construct(TokenStorageInterface $tokenStorage, RouterInterface $router)
    {
        $this->tokenStorage = $tokenStorage;
        $this->router = $router;
    }

    /**
     * @param Request $request
     * @param AccessDeniedException $accessDeniedException
     * @return Response|null
     */
    public function handle(Request $request, AccessDeniedException $accessDeniedException)
    {
        if ($this->isUserLogged()) {
            $currentRoute = $request->attributes->get('_route');
            if ($this->isAuthenticatedUserOnAnonymousPage($currentRoute)) {
                return new RedirectResponse($this->router->generate('homepage'));
            }
        }

        return null;
    }

    /**
     * @return bool
     */
    private function isUserLogged(): bool
    {
        $user = $this->tokenStorage->getToken()->getUser();
        return $user instanceof User;
    }

    /**
     * @param string $currentRoute
     * @return bool
     */
    private function isAuthenticatedUserOnAnonymousPage(string $currentRoute): bool
    {
        return in_array(
            $currentRoute,
            ['fos_user_security_login', 'fos_user_registration_register']
        );
    }
}