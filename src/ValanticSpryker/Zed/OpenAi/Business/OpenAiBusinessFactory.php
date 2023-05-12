<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\OpenAi\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use ValanticSpryker\Client\OpenAi\OpenAiClientInterface;
use ValanticSpryker\Zed\OpenAi\Business\Model\OpenAiPromptExecuter;
use ValanticSpryker\Zed\OpenAi\OpenAiDependencyProvider;

/**
 * @method \ValanticSpryker\Zed\OpenAi\Persistence\OpenAiEntityManager getEntityManager()
 * @method \ValanticSpryker\Zed\OpenAi\Persistence\OpenAiRepository getRepository()
 */
class OpenAiBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \ValanticSpryker\Client\OpenAi\OpenAiClientInterface
     */
    public function getOpenAiClient(): OpenAiClientInterface
    {
        return $this->getProvidedDependency(OpenAiDependencyProvider::CLIENT_OPENAI);
    }

    /**
     * @return \ValanticSpryker\Zed\OpenAi\Business\Model\OpenAiPromptExecuter
     */
    public function createOpenAiPromptExecuter(): OpenAiPromptExecuter
    {
        return new OpenAiPromptExecuter($this->getOpenAiClient(), $this->getRepository());
    }
}
