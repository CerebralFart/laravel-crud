<?php

namespace Cerebralfart\LaravelCRUD\Helpers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/**
 * @property-read string[] $searchColumns
 * @property-read string $searchMode
 */
trait SearchHelper {
    protected array $defaultSearchColumns = [];
    protected string $defaultSearchMode = 'ILIKE';

    protected function applySearch(Request $request, Builder $builder): Builder {
        if ($request->has('_search')) {
            $search = $request->get('_search');
            $this->exposeToView('search', $search);

            $term = '%' . $search . '%';
            $builder->where(function (Builder $builder) use ($term) {
                foreach ($this->searchColumns as $column) {
                    $builder->orWhere($column, $this->searchMode, $term);
                }
            });
        }

        return $builder;
    }
}
