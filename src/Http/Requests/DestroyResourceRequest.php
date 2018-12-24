<?php

namespace Iocaste\Microservice\Api\Http\Requests;

/**
 * Class DestroyResourceRequest
 */
class DestroyResourceRequest extends ValidatedRequest
{
    /**
     * @inheritDoc
     */
    protected function authorize(): void
    {
        if (!$authorizer = $this->getAuthorizer()) {
            return;
        }

        $authorizer->destroy($this->getRecord(), $this->request);
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
            $validators->modifyQuery($this->query())
        );
    }
}
