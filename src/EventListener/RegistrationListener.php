<?php

namespace App\EventListener;

use App\Entity\User;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class RegistrationListener
 * @package App\EventListener
 */
class RegistrationListener implements EventSubscriberInterface
{
    private $router;

    public function __construct(UrlGeneratorInterface $router)
    {
        $this->router = $router;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            FOSUserEvents::REGISTRATION_SUCCESS => [
                ['onRegistrationSuccess', 30],
            ],
            FOSUserEvents::REGISTRATION_COMPLETED => [
                ['onRegistrationCompleted', 30],
            ]
        ];
    }

    /**
     * @param FormEvent $event
     */
    public function onRegistrationSuccess(FormEvent $event)
    {
        /** @var UserInterface $user */
        $user = $event->getForm()->getData();
        $user->setRoles(User::DEFAULT_USER_ROLES);

        $url = $this->router->generate('homepage');

        $event->setResponse(new RedirectResponse($url));
    }

    /**
     * @param FilterUserResponseEvent $event
     */
    public function onRegistrationCompleted(FilterUserResponseEvent $event)
    {
        $event->stopPropagation();
    }
}