<?php

namespace Iocaste\Microservice\Api\Http\Requests;

/**
 * Class UpdateResourceRequest
 */
class UpdateResourceRequest extends ValidatedRequest
{
    /**
     * @inheritDoc
     */
    protected function authorize(): void
    {
        if (!$authorizer = $this->getAuthorizer()) {
            return;
        }

        $authorizer->update($this->getRecord(), $this->request);
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

    /**
     * @inheritDoc
     */
    protected function validateDocument(): void
    {
        //
    }
}
