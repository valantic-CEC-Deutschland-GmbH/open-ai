<?php

declare(strict_types = 1);

namespace ValanticSpryker\Zed\OpenAi\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \ValanticSpryker\Zed\OpenAi\Communication\OpenAiCommunicationFactory getFactory()
 * @method \ValanticSpryker\Zed\OpenAi\Business\OpenAiFacadeInterface getFacade()
 * @method \ValanticSpryker\Zed\OpenAi\Persistence\OpenAiRepositoryInterface getRepository()
 */
class IndexController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function indexAction(Request $request): array
    {
        $openAis = $this->getFactory()->createOpenAiTable()
            ->render();

        return $this->viewResponse([
            'OpenAis' => $openAis,
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableAction(): JsonResponse
    {
        $table = $this->getFactory()
            ->createOpenAiTable();

        return $this->jsonResponse(
            $table->fetchData(),
        );
    }
}
