<?php

namespace App\Controller;

use App\Service\RssService;
use App\Service\WordFrequencyService;
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
     * @param WordFrequencyService $wordFrequencyService
     * @return Response
     */
    public function index(RssService $rssService, WordFrequencyService $wordFrequencyService): Response
    {
        $feed = $rssService->getFeed('theregister');

        return $this->render('index/index.html.twig', [
            'feed' => $feed,
            'words' => $wordFrequencyService->getWordFrequency($feed)
        ]);
    }
}