<?php

namespace Admingenerator\GeneratorBundle\Generator;
use Doctrine\Common\Cache\CacheProvider;

interface CachedGeneratorInterface
{
    /**
     * Set the cache provider
     *
     * @param CacheProvider $cacheProvider
     */
    public function setCacheProvider(CacheProvider $cacheProvider, $cacheSuffix = 'default');

}
