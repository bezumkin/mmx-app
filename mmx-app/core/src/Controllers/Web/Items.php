<?php

namespace MMX\App\Controllers\Web;

use Illuminate\Database\Eloquent\Builder;
use MMX\App\Models\Item;
use Vesp\Controllers\ModelGetController;

class Items extends ModelGetController
{
    protected string $model = Item::class;

    protected function beforeGet(Builder $c): Builder
    {
        return $c->where('active', true);
    }

    protected function beforeCount(Builder $c): Builder
    {
        return $c->where('active', true);
    }
}