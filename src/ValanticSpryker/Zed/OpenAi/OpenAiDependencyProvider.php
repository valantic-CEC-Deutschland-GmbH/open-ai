<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\OpenAi;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class OpenAiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_OPENAI = 'CLIENT_OPENAI';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);

        return $this->addOpenAIClient($container);
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function addOpenAIClient(Container $container): Container
    {
        $container->set(static::CLIENT_OPENAI, $container->getLocator()->openAi()->client());

        return $container;
    }
}
