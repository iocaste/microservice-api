<?php

namespace Iocaste\Microservice\Api\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

/**
 * Class MicroApiController
 */
class MicroApiController extends BaseController
{
    public function index()
    {
        return 'index';
    }

    public function show()
    {
        return 'show';
    }

    public function store()
    {
        return 'store';
    }

    public function update()
    {
        return 'update';
    }

    public function destroy()
    {
        return 'destroy';
    }
}
