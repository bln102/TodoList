<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class ErrorController extends AbstractController
{

    #[Route('/error_role', name: 'error_role')]
    public function roleError()
    {
        return $this->render('bundles/TwigBundle/Exception/error_admin.html.twig');
    }

    #[Route('/page404', name: 'error_404')]
    public function notFound()
    {
        return $this->render('bundles/TwigBundle/Exception/error404.html.twig');
    }
}