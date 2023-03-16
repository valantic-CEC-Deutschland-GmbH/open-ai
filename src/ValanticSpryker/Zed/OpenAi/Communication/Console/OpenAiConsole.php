<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\OpenAi\Communication\Console;

use Exception;
use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \ValanticSpryker\Zed\OpenAi\Communication\OpenAiCommunicationFactory getFactory()
 */
class OpenAiConsole extends Console
{
    protected const COMMAND_NAME = 'openai:completions:create';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this
            ->setName(static::COMMAND_NAME)
            ->setDescription('https://github.com/openai-php/client');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $isSuccess = static::CODE_SUCCESS;
        try {
            $openAiCreateResponseTransfer = $this->getFactory()->getOpenAIClient()->completionsCreate([
                'model' => 'text-davinci-003',
                'prompt' => 'PHP is',
            ]);

            $texts = array_map(static fn (array $choice) => $choice['text'], $openAiCreateResponseTransfer->getChoices());

            $output->write($texts);
        } catch (Exception $e) {
            $isSuccess = static::CODE_ERROR;
        }

        return $isSuccess;
    }
}
