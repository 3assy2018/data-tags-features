<?php

namespace M3assy\DataTags\Facades;

use Illuminate\Support\Facades\Facade;

class DataTag extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "datatag";
    }
}
