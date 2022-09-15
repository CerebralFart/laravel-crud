<?php

namespace Cerebralfart\LaravelCRUD\Helpers;

use Exception;
use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\View\View;

trait ViewHelper {
    /** @var ?string The folder to search views in, using the standard blade dot-notation */
    public ?string $views = null;
    /** @var array<string, string[]> A list of op view names to check for a given route */
    public array $viewMap = [
        'index' => ['index', 'list'],
        'create' => ['create', 'upsert'],
        'show' => ['show'],
        'edit' => ['edit', 'update', 'upsert'],
    ];

    private array $exposed = [];
    private array $shared = [];

    public function exposeToView(string $name, mixed $data): void {
        $this->exposed = array_merge_recursive(
            $this->exposed,
            [$name => $data]
        );
    }

    public function shareToView(string $name, mixed $data): void {
        $this->shared = array_merge_recursive(
            $this->shared,
            [$name => $data]
        );
    }

    public function viewHasExposed(string $name): bool {
        return array_key_exists($name, $this->exposed);
    }

    public function viewHasShared(string $name): bool {
        return array_key_exists($name, $this->shared);
    }

    public function view(string $name, array $data = []): View {
        if ($this->hasErrors()) {
            ViewFacade::share('errors', $this->getErrors());
        }
        ViewFacade::share($this->shared);
        $chain = $this->viewMap[$name];
        foreach ($chain as $option) {
            $view = $this->views . '.' . $option;
            if (ViewFacade::exists($view)) return ViewFacade::make(
                $view,
                array_merge($this->exposed, $data)
            );
        }
        throw new Exception("Could not find a view for $name");
    }
}
