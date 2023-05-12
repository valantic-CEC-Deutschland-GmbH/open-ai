<?php

declare(strict_types = 1);

use Spryker\Shared\Kernel\KernelConstants;
use ValanticSpryker\Shared\OpenAi\OpenAiConstants;

$config[OpenAiConstants::OPENAI_API_KEY] = 'xxxxxx';

$config[KernelConstants::CORE_NAMESPACES] = [
    'ValanticSpryker',
    'SprykerEco',
    'Spryker',
    'SprykerSdk',
    'SprykerMiddleware',
];