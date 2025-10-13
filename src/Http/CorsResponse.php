<?php

namespace BxF\Http;

use BxF\Controller;
use BxF\Http\Response\Body;
use BxF\Http\Response\JsonBody;

class CorsResponse
    extends Controller
{
    public function handle(): Body
    {
        return new JsonBody;
    }
}