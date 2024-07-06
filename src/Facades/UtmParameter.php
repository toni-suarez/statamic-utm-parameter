<?php

namespace Suarez\StatamicUtmParameters\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Suarez\StatamicUtmParameters\UtmParameter boot(\Illuminate\Http\Request $request)
 * @method static array useRequestOrSession(\Illuminate\Http\Request $request)
 * @method static array all()
 * @method static string|null get(string $key)
 * @method static bool has(string $key, $value = null)
 * @method static bool contains(string $key, string $value)
 * @method static bool clear()
 * @method static array getParameter(\Illuminate\Http\Request $request)
 * @method static string ensureUtmPrefix(string $key)
 *
 * @see \Suarez\StatamicUtmParameters\UtmParameter
 */
class UtmParameter extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Suarez\StatamicUtmParameters\UtmParameter::class;
    }
}
