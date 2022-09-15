<?php

namespace Cerebralfart\LaravelCRUD\Helpers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait OrderHelper {
    use ViewHelper;

    /** @var ?string The column by which the index view should be ordered, or null if results should not be explicitly ordered */
    public ?string $orderColumn = null;
    /** @var string The direction to sort the data by, most databases only support ASC or DESC */
    public string $orderDirection = 'ASC';

    public function applyOrder(Request $request, Builder $builder): Builder {
        $orderColumn = $request->get('_order', $this->orderColumn);
        if ($orderColumn !== null) {
            $direction = $request->get('_direction', $this->orderDirection);
            $this->exposeToView('order', $orderColumn);
            $this->exposeToView('direction', $direction);
            $builder->orderBy($orderColumn, $direction);
        }
        return $builder;
    }
}
