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
    private static string $negationPrefix = '!';

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
                $this->normalizeFilterName($name) => !Str::startsWith($name, self::$negationPrefix)
            ])
            ->filter(fn($_, $filter) => method_exists($this, $this->normalizeFilterFnName($filter)))
            ->toArray();
    }

    protected function applyFilter(Request $request, Builder $query): Builder {
        if ($request->has('filter')) {
            $filters = $this->resolveFilters($request);
            $this->exposeToView('filter', $filters);
            /** @var Model $model */
            $model = $this->model::query()->newModelInstance();
            $idName = $model->getQualifiedKeyName();

            foreach ($filters as $filter => $active) {
                /** @var Builder $qb */
                $qb = $this->model::query();
                $this->{$this->normalizeFilterFnName($filter)}($qb);
                $ids = $qb->get($idName);

                $query = $active
                    ? $query->whereIn($idName, $ids)
                    : $query->whereNotIn($idName, $ids);
            }
        }
        return $query;
    }

    protected function normalizeFilterName(string $name): string {
        return ltrim($name, self::$negationPrefix);
    }

    protected function normalizeFilterFnName(string $name): string {
        return 'filter' . Str::ucfirst($this->normalizeFilterName($name));
    }
}
