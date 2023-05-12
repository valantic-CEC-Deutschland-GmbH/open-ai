<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\OpenAi\Business\Model;

use Generated\Shared\Transfer\OpenAiCreateResponseTransfer;
use Generated\Shared\Transfer\OpenAiExecuteRequestTransfer;
use ValanticSpryker\Client\OpenAi\OpenAiClientInterface;
use ValanticSpryker\Zed\OpenAi\Persistence\OpenAiRepository;

class OpenAiPromptExecuter
{
    /**
     * @param \ValanticSpryker\Client\OpenAi\OpenAiClientInterface $openAiClient
     * @param \ValanticSpryker\Zed\OpenAi\Persistence\OpenAiRepository $openAiRepository
     */
    public function __construct(private OpenAiClientInterface $openAiClient, private OpenAiRepository $openAiRepository)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\OpenAiExecuteRequestTransfer $executeRequestTransfer
     *
     * @return \Generated\Shared\Transfer\OpenAiCreateResponseTransfer
     */
    public function executePrompt(OpenAiExecuteRequestTransfer $executeRequestTransfer): OpenAiCreateResponseTransfer
    {
        $eventToPromptEntity = $this->openAiRepository->queryOpenAiPromptToEvent()->filterByEvent($executeRequestTransfer->getEventOrFail())->findOne();

        $prompt = $eventToPromptEntity->getVsyOpenaiPrompt();

        $preparedPrompt = $prompt->getPrompt();

        foreach ($executeRequestTransfer->getContext() as $promptKey => $promptParameter) {
            $preparedPrompt = str_replace('%' . $promptKey . '%', $promptParameter, $preparedPrompt);
        }

        return $this->openAiClient->completionsCreate(
            [
                'model' => $prompt->getModel(),
                'prompt' => $preparedPrompt,
                'suffix' => $prompt->getSuffix(),
                'max_tokens' => $prompt->getMaxTokens(),
                'temperature' => $prompt->getTemperature(),
                'top_p' => $prompt->getTopP(),
                'n' => $prompt->getNCompletions(),
                'stream' => $prompt->getStream(),
                'logprobs' => $prompt->getLogprobs(),
                'echo' => $prompt->getEcho(),
                'stop' => $prompt->getStop(),
                'presence_penalty' => $prompt->getPresencePenalty(),
                'frequency_penalty' => $prompt->getFrequencyPenalty(),
                'best_of' => $prompt->getBestOf(),
                'user' => $prompt->getUser(),
            ],
        );
    }
}
