<?php

namespace Cerebralfart\LaravelCRUD\Helpers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait SearchHelper {
    /** @var string[] The list of columns to search in */
    public array $searchColumns = [];
    /** @var string The search mode to use, for example `name ILIKE %CerebralFart% */
    public string $searchMode = 'ILIKE';
    /** @var string A string to format the term by, accepts anything sprintf would */
    public string $searchFormat = '%%%s%%';

    public function applySearch(Request $request, Builder $builder): Builder {
        if ($request->has('_search')) {
            $search = $request->get('_search');
            $this->exposeToView('search', $search);

            $term = sprintf($this->searchFormat, $search);
            $builder->where(function (Builder $builder) use ($term) {
                foreach ($this->searchColumns as $column) {
                    $builder->orWhere($column, $this->searchMode, $term);
                }
            });
        }

        return $builder;
    }
}
