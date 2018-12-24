<?php

namespace Iocaste\Microservice\Api\Http\Controllers;

use Illuminate\Http\Request;
use Iocaste\Microservice\Api\Contracts\Resource\ResourceInterface;
use Iocaste\Microservice\Api\Http\CreatesResponse;
use Laravel\Lumen\Routing\Controller as BaseController;

/**
 * Class MicroApiController
 */
class MicroApiController extends BaseController
{
    use CreatesResponse;

    /**
     * @param ResourceInterface $resource
     *
     * @return mixed
     */
    public function index(ResourceInterface $resource)
    {
        $records = $resource->getList('comments');

        return $this->respond()->content($records);
    }

    /**
     * @param ResourceInterface $resource
     * @param Request $request
     *
     * @return mixed
     */
    public function show(ResourceInterface $resource, Request $request)
    {
        $record = $resource->getSingle('comments', 1);

        if (! $record) {
            return $this->respond()->content(null);
        }

        return $this->respond()->content($record);
    }

    public function store(Request $request)
    {
        dd($request->route());

        return 'end reached. store';
    }

    public function update()
    {
        return 'end reached. update';
    }

    public function destroy()
    {
        return 'end reached. destroy';
    }
}
