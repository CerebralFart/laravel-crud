<?php

namespace Cerebralfart\LaravelCRUD;

use Cerebralfart\LaravelCRUD\Actions\CreateAction;
use Cerebralfart\LaravelCRUD\Actions\DestroyAction;
use Cerebralfart\LaravelCRUD\Actions\EditAction;
use Cerebralfart\LaravelCRUD\Actions\IndexAction;
use Cerebralfart\LaravelCRUD\Actions\ShowAction;
use Cerebralfart\LaravelCRUD\Actions\StoreAction;
use Cerebralfart\LaravelCRUD\Actions\UpdateAction;
use Cerebralfart\LaravelCRUD\Helpers\AuthHelper;
use Cerebralfart\LaravelCRUD\Helpers\FilterHelper;
use Cerebralfart\LaravelCRUD\Helpers\ModelHelper;
use Cerebralfart\LaravelCRUD\Helpers\OrderHelper;
use Cerebralfart\LaravelCRUD\Helpers\PropHelper;
use Cerebralfart\LaravelCRUD\Helpers\RouteHelper;
use Cerebralfart\LaravelCRUD\Helpers\SearchHelper;
use Cerebralfart\LaravelCRUD\Helpers\ViewHelper;
use Illuminate\Routing\Controller;

abstract class CRUDController extends Controller {
    use CreateAction,
        DestroyAction,
        EditAction,
        IndexAction,
        ShowAction,
        StoreAction,
        UpdateAction,
        AuthHelper,
        FilterHelper,
        ModelHelper,
        OrderHelper,
        PropHelper,
        RouteHelper,
        SearchHelper,
        ViewHelper;
}
