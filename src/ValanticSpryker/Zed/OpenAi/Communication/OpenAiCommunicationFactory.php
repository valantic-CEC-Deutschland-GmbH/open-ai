<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\OpenAi\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use ValanticSpryker\Client\OpenAi\OpenAiClientInterface;
use ValanticSpryker\Zed\OpenAi\OpenAiDependencyProvider;

class OpenAiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \ValanticSpryker\Client\OpenAi\OpenAiClientInterface
     */
    public function getOpenAIClient(): OpenAiClientInterface
    {
        return $this->getProvidedDependency(OpenAiDependencyProvider::CLIENT_OPENAI);
    }
}
