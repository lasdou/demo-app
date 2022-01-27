<?php

declare(strict_types=1);

namespace App\Controller;

use App\Query\Locale\GuessCurrentLocaleQuery;
use App\Query\Product\FetchProductsQuery;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment as TwigEnvironment;

final class ListProductsAction
{
    public function __construct(
        private TwigEnvironment $twig,
        private GuessCurrentLocaleQuery $guessCurrentLocaleQuery,
        private FetchProductsQuery $fetchProductsQuery,
    ) {
    }

    #[Route('/products', name: 'products', methods: ['GET'])]
    public function __invoke(Request $request): Response
    {
        $locale = ($this->guessCurrentLocaleQuery)();

        return new Response(
            $this->twig->render('products.html.twig', [
                'locale' => $locale,
                'products' => ($this->fetchProductsQuery)($locale),
                'pimUrl' => $request->getSession()->get('pim_url'),
            ])
        );
    }
}
