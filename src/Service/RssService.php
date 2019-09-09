<?php

namespace App\Service;

use DateTime;
use Exception;
use FeedIo\Feed;
use FeedIo\FeedIo;
use Psr\Cache;
use Psr\Log\LoggerInterface;

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

    /** @var Cache\CacheItemPoolInterface */
    private $cache;

    /** @var LoggerInterface */
    private $logger;

    /** @var string|null */
    private $namespace;

    /**
     * RssService constructor.
     * @param FeedIo $feedIo
     * @param array $resourceMap
     * @param Cache\CacheItemPoolInterface $cache
     * @param LoggerInterface $logger
     * @param string|null $namespace
     */
    public function __construct(
        FeedIo $feedIo,
        array $resourceMap,
        Cache\CacheItemPoolInterface $cache,
        LoggerInterface $logger,
        ?string $namespace = 'mintos_test'
    ) {
        $this->feedIo = $feedIo;
        $this->resourceMap = $resourceMap;
        $this->cache = $cache;
        $this->logger = $logger;
        $this->namespace = $namespace;
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
        $cacheKey = $this->getCacheKey($resourceName);

        try {
            $cacheItem = $this->cache->getItem($cacheKey);
        } catch (Cache\InvalidArgumentException $exception) {
            $this->logger->critical(sprintf("Key \"%\" string is not a legal value", $cacheKey), ['rss-service']);

            return $this->fetchFeed($resourceName);
        }

        if ($cacheItem->isHit()) {
            return $cacheItem->get();
        }

        $feed = $this->fetchFeed($resourceName);

        $cacheItem
            ->set($feed)
            ->expiresAt(
                (new DateTime())->modify('+1 day')
            );

        return $feed;
    }

    /**
     * @param string $resourceName
     * @return array
     */
    private function fetchFeed(string $resourceName): array
    {
        $result = $this->feedIo->read($this->resourceMap[$resourceName]);

        /** @var Feed $feed */
        $feed = $result->getFeed();

        return $feed->toArray();
    }

    /**
     * @param string $resourceName
     * @return string
     */
    private function getCacheKey(string $resourceName): string
    {
        return md5(serialize([$resourceName, $this->resourceMap[$resourceName]]) . $this->namespace);
    }
}