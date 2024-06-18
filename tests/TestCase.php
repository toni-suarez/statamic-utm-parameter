<?php

namespace Suarez\StatamicUtmParameters\Tests;

use Statamic\Testing\AddonTestCase;
use Suarez\StatamicUtmParameters\ServiceProvider;

abstract class TestCase extends AddonTestCase
{
    protected string $addonServiceProvider = ServiceProvider::class;
}
