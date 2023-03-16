<?php

declare(strict_types = 1);

namespace ValanticSpryker\Client\OpenAi;

use OpenAI;
use OpenAI\Client;
use Spryker\Client\Kernel\AbstractFactory;
use ValanticSpryker\Client\OpenAi\Decorator\OpenAiClientDecorator;

/**
 * @method \ValanticSpryker\Client\OpenAi\OpenAiConfig getConfig()
 */
class OpenAiFactory extends AbstractFactory
{
    /**
     * https://github.com/openai-php/client
     *
     * @return \OpenAI\Client
     */
    private function createOpenAiClient(): Client
    {
        return OpenAI::client($this->getConfig()->getOpenAiApiKey());
    }

    /**
     * @return \ValanticSpryker\Client\OpenAi\Decorator\OpenAiClientDecorator
     */
    public function createOpenAiClientDecorator(): OpenAiClientDecorator
    {
        return new OpenAiClientDecorator($this->createOpenAiClient());
    }
}
