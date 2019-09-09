<?php

namespace App\Service;

use Exception;
use FeedIo\Feed;
use FeedIo\FeedInterface;
use FeedIo\FeedIo;

/**
 * Class RssService
 * @package App\Service
 */
class RssService
{
    /** @var FeedIo */
    private $feedIo;

    /** @var array */
    private $resourceMap;

    /**
     * RssService constructor.
     * @param FeedIo $feedIo
     * @param array $resourceMap
     */
    public function __construct(
        FeedIo $feedIo,
        array $resourceMap
    ) {
        $this->feedIo = $feedIo;
        $this->resourceMap = $resourceMap;
    }

    /**
     * @return array
     * @throws Exception
     */
    public function getFeeds(): array
    {
        $feeds = [];

        foreach ($this->resourceMap as $resourceName => $resource) {
            $feeds[$resourceName] = $this->getFeed($resourceName);
        }

        return $feeds;
    }

    /**
     * @param string $resourceName
     * @return array
     * @throws Exception
     */
    public function getFeed(string $resourceName): array
    {
        //TODO add cache logic;

        $feed = $this->fetchFeed($resourceName)->toArray();

        return $feed;
    }

    /**
     * @param string $resourceName
     * @return FeedInterface|Feed
     */
    private function fetchFeed(string $resourceName): FeedInterface
    {
        $result = $this->feedIo->read($this->resourceMap[$resourceName]);

        return $result->getFeed();
    }
}