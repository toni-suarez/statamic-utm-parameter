# Statamic UTM-Parameters

[![Latest Version on Packagist](https://img.shields.io/packagist/v/suarez/statamic-utm-parameter.svg?style=flat-square)](https://packagist.org/packages/suarez/statamic-utm-parameter)
[![StyleCI](https://github.styleci.io/repos/448347178/shield?branch=main)](https://github.styleci.io/repos/816752437?branch=main)
[![Test PHP 8.x](https://github.com/toni-suarez/statamic-utm-parameter/actions/workflows/tests-php8.yml/badge.svg?branch=main)](https://github.com/toni-suarez/statamic-utm-parameter/actions/workflows/tests-php8.yml)
[![Packagist Downloads](https://img.shields.io/packagist/dt/suarez/statamic-utm-parameter?style=flat-square)](https://packagist.org/packages/suarez/statamic-utm-parameter)
[![Statamic Addon](https://img.shields.io/badge/https%3A%2F%2Fstatamic.com%2Faddons%2Ftoni-suarez%2Futm-parameter?style=flat-square&logo=statamic&logoColor=rgb(255%2C%2038%2C%20158)&label=Statamic&link=https%3A%2F%2Fstatamic.com%2Faddons%2Ftoni-suarez%2Futm-parameter)](https://statamic.com/addons/toni-suarez/utm-parameter)

A helper to store and handle UTM parameters session-based on statamic websites.

```antlers
{{ if { utm:has type="source" value="google" } }}
    <span>{{ utm:get type="medium" }}</span>
{{ /if }}
```

---

## Installation

You can search for this addon in the `Tools > Addons` section of the Statamic control panel and click **install**, or run the following command from your project root:

``` bash
composer require suarez/statamic-utm-parameter
```

Optionally, you can publish the config file of this package with this command:
```bash
php artisan vendor:publish --tag="statamic-utm-parameter-config""
```

**Note**: The UTM parameters are stored **session-based**, meaning they are only available during the user's current browsing session and will be cleared when the user closes their browser or navigates away from your website. This addon leverages the [built-in Laravel session management](https://laravel.com/docs/session#configuration) system for storage.

## Configuration

The configuration file `config/statamic-utm-parameter.php` allows you to control the behavior of the UTM parameters handling.

```php
<?php

return [
  /*
   * Control Overwriting UTM Parameters (default: false)
   *
   * This setting determines how UTM parameters are handled within a user's session.
   *
   * - Enabled (true): New UTM parameters will overwrite existing ones during the session.
   * - Disabled (false): The initial UTM parameters will persist throughout the session.
   */
  'override_utm_parameters' => false,

  /*
   * Session Key for UTM Parameters (default: 'utm')
   *
   * This key specifies the name used to access and store UTM parameters within the session data.
   *
   * If you're already using 'utm' for another purpose in your application,
   * you can customize this key to avoid conflicts.
   * Simply provide your preferred key name as a string value.
   */
    'session_key' => 'utm',

  /*
    * Allowed UTM Parameters (default: ['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content', 'utm_campaign_id'])
    *
    * This setting defines the UTM parameters that are allowed within your application.
    *
    * In this array, you can specify a list of allowed UTM parameter names. Each parameter should be listed as a string.
    * Only parameters from this list will be stored and processed in the session.
    * and any parameter without the 'utm_' prefix will be ignored regardless of its inclusion in this list.
    *
    * Example: To only allow the basic UTM parameters (source, medium, and campaign), you could update the array like this:
    *
    * 'allowed_utm_parameters' => [
    *     'utm_source',
    *     'utm_medium',
    *     'utm_campaign',
    * ],
    */
    'allowed_utm_parameters' => [
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_term',
        'utm_content',
        'utm_campaign_id'
    ],
];
```


## Usage

This addon provides convenient methods to retrieve, check, and use UTM parameters within your Statamic templates.

### Antlers Templating

#### Retrieve All UTM Parameters

Use `{{ utm:all }}` to get an array of all UTM parameters.

```antlers
{{ utm:all }}
```

#### Retrieve a Specific UTM Parameter

Use `{{ utm:get }}` to get the value of a specific UTM parameter.

```antlers
{{ utm:get type="term" }}
```

#### Check if a Specific UTM Parameter Exists
Use {{ utm:has }} or {{ utm:is }} to check if a specific UTM parameter exists.

```antlers
{{ if { utm:has type="medium" value="newsletter" } }}
    <span>Subscribe to our podcast</span>
{{ /if }}
```

#### Check if a Specific UTM Parameter contains a value
Use {{ utm:contains }} to check if a specific UTM parameter contains a specific value.

```antlers
{{ if { utm:contains type="campaign" value="summer" } }}
    <p>Summer-Deals</p>
{{ /if }}
```

#### Clear UTM Parameters

Use the clear method to remove and reset the UTM parameters from the session.

```php
UtmParameter::clear(); // true
```

### Parameter Types
The following UTM parameter types are supported:

- `source`: The source of the traffic (e.g., google, newsletter).
- `medium`: The medium of the traffic (e.g., cpc, email).
- `campaign`: The campaign name (e.g., spring_sale).
- `term`: The search term used (e.g., running+shoes).
- `content`: The specific content (e.g., ad_variation_1).

## Examples

### Displaying All UTM Parameters

```antlers
    <ul>
        {{ foreach array="{ utm:all }"}}
            <li>{{ key }}: {{ value }}</li>
        {{ /foreach }}
    </ul>
```

### Displaying a Specific UTM Parameter
To display the UTM term parameter:

```antlers
{{ utm:get type="term" }}
```

### Conditional Display Based on UTM Parameters

To display the UTM medium parameter if the source is `google`:

```antlers
{{ if { utm:has type="medium" value="newsletter" } }}
    <span>Subscribe to our podcast</span>
{{ /if }}

{{ if { utm:contains type="campaign" value="summer" } }}
    <p>Summer-Deals</p>
{{ /if }}
```

### Full Example
Here's a full example combining all the functionalities:

```antlers
<ul>
    <li>Source: {{ utm:get type="source" }}</li>
    <li>Medium: {{ utm:get type="medium" }}</li>
    <li>Campaign: {{ utm:get type="campaign" }}</li>
    <li>Term: {{ utm:get type="term" }}</li>
    <li>Content: {{ utm:get type="content" }}</li>
</ul>

{{ if { utm:has type="source" value="ecosia" } }}
    <div>Thank you for visiting from Ecosia!</div>
{{ elseif { utm:has type="source" value="newsletter" } }}
    <p>Welcome back, newsletter subscriber!</p>
{{ else }}
    <p>Subscribe to our newsletter!</p>
{{ /if }}
```

## Extending the Middleware

You can extend the middleware to customize the behavior of accepting UTM parameters. For example, you can override the `shouldAcceptUtmParameter` method.

First, create a new middleware using Artisan:

```bash
php artisan make:middleware CustomMiddleware
```

Then, update the new middleware to extend UtmParameters and override the `shouldAcceptUtmParameter` method:

```php
<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Suarez\StatamicUtmParameters\Http\Middleware\CheckUtmParameter;

class CustomMiddleware extends CheckUtmParameter
{
    /**
     * Determines whether the given request/response pair should accept UTM-Parameters.
     *
     * @param \Illuminate\Http\Request  $request
     *
     * @return bool
     */
    protected function shouldAcceptUtmParameter(Request $request)
    {
        return $request->isMethod('GET') || $request->isMethod('POST');
    }
}
```

Finally, update your `bootstrap/app.php` to use the CustomMiddleware:

```php
# bootstrap/app.php
use App\Http\Middleware\CustomMiddleware;

->withMiddleware(function (Middleware $middleware) {
    $middleware->web(append: [
        CustomMiddleware::class,
        // other middleware...
    ]);
})
```

## License
The Statamic UTM-Parameters addon is open-sourced software licensed under the MIT license.
