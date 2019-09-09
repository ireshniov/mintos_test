<?php

namespace App\Service;

use FeedIo\Feed;
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
     */
    public function getFeed(string $resourceName): array
    {
        // TODO: cache logic;

        /** @var Feed $feed */
        $feed = $this->feedIo->read($this->resourceMap[$resourceName])->getFeed();

        return $feed->toArray();
    }
}