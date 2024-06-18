<?php
namespace Suarez\StatamicUtmParameters\Tags;

use Statamic\Tags\Tags;
use Suarez\StatamicUtmParameters\UtmParameter;

class Utm extends Tags
{
    /**
     * Default index.
     *
     * @return array
     */
    public function index()
    {
        return $this->get();
    }

    public function all()
    {
        return UtmParameter::all();
    }

    /**
     * Retrieve the UTM Parameters.
     *
     * @return string
     */
    public function get()
    {
        $type = $this->params->get('type', 'source');

        return UtmParameter::get($type);
    }

    /**
     * Determin if there is a certain UTM Parameter.
     *
     * @return bool
     */
    public function has()
    {
        $type = $this->params->get('type', 'source');

        if ($this->params->has('contains')) {
            $value = $this->params->get('contains');
            return UtmParameter::contains($type, $value);
        }

        $value = $this->params->get('value', null);
        return UtmParameter::has($type, $value);
    }
}
