<?php

namespace BxF\Http\Response\JsonResponse;

enum Status: string
{
    case Success = 'success';
    case Error = 'error';
}
