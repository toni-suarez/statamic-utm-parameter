# Statamic UTM-Parameters

> A helper to store and handle UTM parameters on Statamic websites.

## Installation

You can search for this addon in the `Tools > Addons` section of the Statamic control panel and click **install**, or run the following command from your project root:

``` bash
composer require suarez/statamic-utm-parameter
```

## Usage

This addon provides convenient methods to retrieve, check, and use UTM parameters within your Statamic templates.

### Antlers Templating

**Retrieve All UTM Parameters**

Use `{{ utm:all }}` to get an array of all UTM parameters.

```antlers
{{ utm:all }}
```

**Retrieve a Specific UTM Parameter**

Use `{{ utm:get }}` to get the value of a specific UTM parameter.

```antlers
{{ utm:get type="term" }}
```

**Check if a Specific UTM Parameter Exists**
Use {{ utm:has }} to check if a specific UTM parameter exists.

```antlers
{{ if { utm:has type="source" value="google" } }}
    <span>{{ utm:get type="medium" }}</span>
{{ /if }}
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
{{ if { utm:has type="source" value="google" } }}
    <span>{{ utm:get type="medium" }}</span>
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

{{ if { utm:has type="source" value="google" } }}
    <div>Thank you for visiting from Google! Your traffic medium is {{ utm:get type="medium" }}.</div>
{{ /if }}
```

