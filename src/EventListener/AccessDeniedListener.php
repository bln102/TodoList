<?php 
// src/EventListener/AccessDeniedListener.php
namespace App\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AccessDeniedListener implements EventSubscriberInterface
{
    /*
    * @var RouterInterface
    */
   private $router;

   /**
    * @var RouterInterface $router
    */
   public function __construct(RouterInterface $router)
   {
       $this->router = $router;
   }

    public static function getSubscribedEvents(): array
    {
        return [
            // the priority must be greater than the Security HTTP
            // ExceptionListener, to make sure it's called before
            // the default exception listener
            KernelEvents::EXCEPTION => ['onKernelException', 2],
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        if (!$exception instanceof AccessDeniedException) {
            return;
        }

        // ... perform some action (e.g. logging)
        // Create redirect response with url for the home page
        $response = new RedirectResponse($this->router->generate('error_role'));
        // optionally set the custom response
        $event->setResponse($response);

        // or stop propagation (prevents the next exception listeners from being called)
        //$event->stopPropagation();
    }
}