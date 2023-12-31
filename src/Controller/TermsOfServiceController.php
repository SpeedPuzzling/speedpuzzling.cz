<?php
declare(strict_types=1);

namespace SpeedPuzzling\Web\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class TermsOfServiceController extends AbstractController
{
    #[Route(path: '/terms-of-service', name: 'terms_of_service', methods: ['GET'])]
    public function __invoke(Request $request): Response
    {
        return $this->render('terms-of-service.html.twig');
    }
}
