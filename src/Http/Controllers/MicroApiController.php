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

    public function index()
    {
        return 'end reached. index';
    }

    public function show(ResourceInterface $resource, Request $request)
    {
        $record = $resource->getSingle('comments', 1);

        if (! $record) {
            return $this->respond()->content(null);
            // return 'no_result';
        }

        // dd($record);

        return $this->respond()->content($record);

        // dd($request->route());
        // return 'end reached. show';
    }

    public function store()
    {
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
