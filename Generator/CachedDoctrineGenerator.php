<?php
namespace Admingenerator\GeneratorBundle\Generator;
use Doctrine\Common\Cache\CacheProvider;

class CachedDoctrineGenerator extends DoctrineGenerator implements CachedGeneratorInterface
{
    /**
     * (non-PHPdoc)
     * @see \Admingenerator\GeneratorBundle\Generator\CachedGeneratorInterface::setCacheProvider()
     */
    public function setCacheProvider(CacheProvider $cacheProvider, $cacheSuffix = 'default')
    {
        $this->cacheProvider = $cacheProvider;
        $this->cacheSuffix = $cacheSuffix;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \Admingenerator\GeneratorBundle\Generator\DoctrineGenerator::build()
     */
    public function build()
    {
        if ($this->cacheProvider->fetch($this->getCacheKey())) {
            return;
        }

        parent::build();
        $this->cacheProvider->save($this->getCacheKey(), true);
    }

    /**
     * @return string
     */
    protected function getCacheKey()
    {
        return sprintf('admingen_isbuilt_%s_%s', $this->getBaseGeneratorName(), $this->cacheSuffix);
    }
}
