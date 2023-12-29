<?php
declare(strict_types=1);

namespace SpeedPuzzling\Web\Controller;

use SpeedPuzzling\Web\Query\GetFastestPlayers;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class LadderPerPiecesController extends AbstractController
{
    public function __construct(
        readonly private GetFastestPlayers $getFastestPlayers,
    ) {
    }

    #[Route(path: '/zebricek/500-dilku/', name: 'ladder_500_pieces', methods: ['GET'])]
    #[Route(path: '/zebricek/1000-dilku/', name: 'ladder_1000_pieces', methods: ['GET'])]
    public function __invoke(Request $request): Response
    {
        /** @var string $routeName */
        $routeName = $request->attributes->get('_route');

        $piecesCount = match ($routeName) {
            'ladder_500_pieces' => 500,
            'ladder_1000_pieces' => 1000,
            default => throw $this->createNotFoundException(),
        };

        return $this->render('ladder_per_pieces.html.twig', [
            'solved_puzzle_times' => $this->getFastestPlayers->perPiecesCount($piecesCount, 100),
            'pieces_count' => $piecesCount,
        ]);
    }
}