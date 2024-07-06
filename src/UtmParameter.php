<?php

namespace Suarez\StatamicUtmParameters;

use Illuminate\Http\Request;

class UtmParameter
{
    /**
     * Bag containing all UTM-Parameters.
     *
     * @var array
     */
    public array|null $parameters;

    /**
     * Utm Parameter Session Key.
     *
     * @var string
     */
    public string $sessionKey;


    public function __construct(array $parameters = [])
    {
        $this->sessionKey = config('statamic-utm-parameter.session_key');
        $this->parameters = $parameters;
    }

    /**
     * Bootstrap UtmParameter.
     *
     * @param Request $request
     *
     * @return UtmParameter
     */
    public function boot(Request $request) : self
    {
        $this->parameters = $this->useRequestOrSession($request);
        return $this;
    }

    /**
     * Check which Parameters should be used.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function useRequestOrSession(Request $request) : array
    {
        $currentRequestParameter = $this->getParameter($request);
        $sessionParameter = session($this->sessionKey);

        if (!empty($currentRequestParameter) && empty($sessionParameter)) {
            session([$this->sessionKey => $currentRequestParameter]);
            return $currentRequestParameter;
        }

        if (!empty($currentRequestParameter) && !empty($sessionParameter) && config('statamic-utm-parameter.override_utm_parameters')) {
            $mergedParameters = array_merge($sessionParameter, $currentRequestParameter);
            session([$this->sessionKey => $mergedParameters]);
            return $mergedParameters;
        }

        return $sessionParameter ?? [];
    }

    /**
     * Retrieve all statamic-utm-parameter.
     *
     * @return array
     */
    public function all(): array
    {
        return session($this->sessionKey) ?? [];
    }

    /**
     * Retrieve a UTM-Parameter by key.
     *
     * @param string $key
     *
     * @return string|null
     */
    public function get(string $key): ?string
    {
        $parameters = $this->all();
        $key = $this->ensureUtmPrefix($key);

        return $parameters[$key] ?? null;
    }

    /**
     * Determine if a UTM-Parameter exists.
     *
     * @param string $key
     * @param string|null $value
     *
     * @return bool
     */
    public function has(string $key, ?string $value = null): bool
    {
        $parameters = $this->all();
        $key = $this->ensureUtmPrefix($key);

        if (!array_key_exists($key, $parameters)) {
            return false;
        }

        return $value === null || $this->get($key) === $value;
    }

    /**
     * Determine if a value contains inside the key.
     *
     * @param string $key
     * @param string $value
     * @return bool
     */
    public function contains(string $key, ?string $value): bool
    {
        if ($value === null) {
            return false;
        }

        $parameters = $this->all();
        $key = $this->ensureUtmPrefix($key);

        if (!array_key_exists($key, $parameters) || !is_string($value)) {
            return false;
        }

        return str_contains($this->get($key), $value);
    }

    /**
     * Clear and remove utm session.
     *
     * @return bool
     */
    public function clear(): bool
    {
        session()->forget($this->sessionKey);
        $this->parameters = null;
        return true;
    }

    /**
     * Retrieve all UTM-Parameter from the URI.
     *
     * @return array
     */
    protected function getParameter(Request $request): array
    {
        $allowedKeys = config('statamic-utm-parameter.allowed_utm_parameters', [
            'utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content'
        ]);

        return collect($request->all())
            ->filter(fn ($value, $key) => substr($key, 0, 4) === 'utm_')
            ->filter(fn ($value, $key) => in_array($key, $allowedKeys))
            ->mapWithKeys(fn ($value, $key) => [
                htmlspecialchars($key, ENT_QUOTES, 'UTF-8') => htmlspecialchars($value, ENT_QUOTES, 'UTF-8')
            ])
            ->toArray();
    }

    /**
     * Ensure the key to start with 'utm_'.
     *
     * @param string $key
     * @return string
     */
    protected function ensureUtmPrefix(string $key): string
    {
        return str_starts_with($key, 'utm_') ? $key : 'utm_' . $key;
    }
}
