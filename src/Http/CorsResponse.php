<?php

namespace BxF\Http;

use BxF\Controller;
use BxF\Http\Response\Code;
use BxF\Http\Response\JsonResponse;

class CorsResponse
    extends Controller
{
    public function handle(): Response
    {
        return new JsonResponse(Code::OK);
    }
}