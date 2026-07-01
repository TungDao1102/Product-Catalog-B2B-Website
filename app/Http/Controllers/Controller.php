<?php

namespace App\Http\Controllers;

abstract class Controller
{
    /**
     * Execute an action on the controller.
     *
     * Filters out the 'locale' route parameter injected by the {locale} prefix
     * so that controller methods only receive their intended parameters.
     */
    public function callAction($method, $parameters)
    {
        unset($parameters['locale']);

        return $this->{$method}(...array_values($parameters));
    }
}
