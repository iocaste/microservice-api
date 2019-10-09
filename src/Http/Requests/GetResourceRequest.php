<?php

namespace Iocaste\Microservice\Api\Http\Requests;

/**
 * Class GetResourceRequest
 */
class GetResourceRequest extends ValidatedRequest
{
    /**
     * @inheritDoc
     */
    protected function authorize(): void
    {
        if (!$authorizer = $this->getAuthorizer()) {
            return;
        }

        $authorizer->show($this->getRecord(), $this->request);
    }

    /**
     * @inheritDoc
     */
    protected function validateQuery(): void
    {
        if (! $validators = $this->getValidators()) {
            return;
        }

        $this->passes(
            $validators->fetchQuery($this->query())
        );
    }
}
