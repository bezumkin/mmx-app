<?php

namespace MMX\App\Controllers\Mgr;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use MMX\App\Models\Item;
use Psr\Http\Message\ResponseInterface;
use Vesp\Controllers\ModelController;

class Items extends ModelController
{
    protected string $model = Item::class;

    protected function beforeCount(Builder $c): Builder
    {
        if ($query = trim($this->getProperty('query', ''))) {
            $c->where('title', 'LIKE', "%$query%");
        }

        return $c;
    }

    protected function beforeSave(Model $record): ?ResponseInterface
    {
        /** @var Item $record */
        if ($record->newQuery()->where('title', $record->title)->where('id', '!=', $record->id)->exists()) {
            return $this->failure('errors.item.title_unique');
        }

        return null;
    }
}