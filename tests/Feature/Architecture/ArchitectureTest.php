<?php

arch('fails when model not extend main model')
    ->expect('App\Models')
    ->toBeClasses()
    ->toExtend('App\Models\Main')
    ->ignoring('App\Models\User');

arch('fails when filters not extend base filter')
    ->expect('App\Filters')
    ->toBeClasses()
    ->toExtend('App\Filters\V1\QueryFilter')
    ->ignoring('App\Filters\V1\QueryFilter');

arch('app')
    ->expect('App\Enums')
    ->toBeEnums();

arch('traits')
    ->expect('App\Traits')
    ->toBeTraits();
