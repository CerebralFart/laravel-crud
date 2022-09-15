<?php

namespace Cerebralfart\LaravelCRUD\Helpers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * @property-read string[] $filters
 */
trait FilterHelper {
    /** @var string Using the negation prefix in the _filters parameter indicates it should be inversed before use */
    public string $negationPrefix = '!';

    /**
     * @param Request $request
     * @return array<string, bool>
     */
    protected function resolveFilters(Request $request): array {
        $filters = $request->get('_filter', $this->filters);
        if (is_string($filters)) $filters = [$filters];
        if (is_null($filters)) $filters = [];

        return collect($filters)
            ->reject(fn($val) => is_null($val))
            ->mapWithKeys(fn($name) => [
                $this->normalizeFilterName($name) => !Str::startsWith($name, $this->negationPrefix)
            ])
            ->filter(fn($_, $filter) => method_exists($this, $this->normalizeFilterFnName($filter)))
            ->toArray();
    }

    protected function applyFilter(Request $request, Builder $query): Builder {
        if ($request->has('_filter') || $this->filters !== null) {
            $filters = $this->resolveFilters($request);
            $this->exposeToView('filter', $filters);
            /** @var Model $model */
            $model = $this->model::query()->newModelInstance();
            $idName = $model->getQualifiedKeyName();

            foreach ($filters as $filter => $active) {
                /** @var Builder $subQuery */
                $subQuery = $this->model::query();
                $this->{$this->normalizeFilterFnName($filter)}($subQuery);

                // Ensure we're selecting the id column, to prevent issues when the user wants to do something ""clever""
                $subQuery->select($idName);

                $query->whereRaw(
                    sprintf('%s %s (%s)',
                        $idName,
                        $active ? 'IN' : 'NOT IN',
                        $subQuery->toSql()
                    ),
                    $subQuery->getBindings()
                );
            }
        }
        return $query;
    }

    protected function normalizeFilterName(string $name): string {
        return ltrim($name, $this->negationPrefix);
    }

    protected function normalizeFilterFnName(string $name): string {
        return 'filter' . Str::ucfirst($this->normalizeFilterName($name));
    }
}
