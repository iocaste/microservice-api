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
        return 'end reached. index';
    }

    public function show()
    {
        return 'end reached. show';
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
