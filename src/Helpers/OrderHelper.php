<?php

namespace Cerebralfart\LaravelCRUD\Helpers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/**
 * @property-read string $orderColumn
 * @property-read string $orderDirection
 */
trait OrderHelper {
    use ViewHelper;

    protected static string $defaultOrderDirection = 'DESC';

    protected function applyOrder(Request $request, Builder $builder): Builder {
        $orderColumn = $request->get('_order') ?? $this->orderColumn;
        if ($orderColumn !== null) {
            $direction = $request->get('_direction') ?? $this->orderDirection;
            $this->exposeToView('order', $orderColumn);
            $this->exposeToView('direction', $direction);
            $builder->orderBy($orderColumn, $direction);
        }
        return $builder;
    }
}
