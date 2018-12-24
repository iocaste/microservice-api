<?php

namespace Iocaste\Microservice\Api\Http\Requests;

/**
 * Class GetResourcesRequest
 */
class GetResourcesRequest extends ValidatedRequest
{
    /**
     * @inheritDoc
     */
    protected function authorize(): void
    {
        if (! $authorizer = $this->getAuthorizer()) {
            return;
        }

        $authorizer->index($this->getResourceNamespace(), $this->request);
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
            $validators->fetchManyQuery($this->query())
        );
    }
}
