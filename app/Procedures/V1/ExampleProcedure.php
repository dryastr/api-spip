<?php

namespace App\Procedures\V1;

use App\Traits\RpcResponseTransform;
use Sajya\Server\Procedure;

class ExampleProcedure extends Procedure
{
    use RpcResponseTransform;
}
