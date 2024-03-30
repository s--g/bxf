<?php

namespace BxF\Http\Response;

enum Code: int
{
    case OK = 200;
    case BadRequest = 400;
    case NotFound = 404;
    case Conflict = 409;
    case ServerError = 500;
}