<?php

namespace App\Controller;

use App\Service\RssService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class IndexController
 * @package App\Controller
 */
class IndexController extends AbstractController
{
    /**
     * @param RssService $rssService
     * @return Response
     * @throws \Exception
     */
    public function index(RssService $rssService): Response
    {
        return $this->render('index/index.html.twig', [
            'feed' => $rssService->getFeed('theregister')
        ]);
    }
}