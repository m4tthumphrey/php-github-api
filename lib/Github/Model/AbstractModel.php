<?php

namespace Github\Model;

use Github\Client;
use Github\Exception;

abstract class AbstractModel
{
    protected static $_properties;
    protected static $_client;

    public static function client(Client $client = null)
    {
        if (null !== $client) {
            static::$_client = $client;
        }

        return static::$_client;
    }

    public static function factory()
    {
        $args = func_get_args();
        $last = end(array_values($args));

        $obj = new \ReflectionClass(get_called_class());

        if ($last instanceof Client) {
            $last = array_pop($args);
            static::client($last);
        }

        return $obj->newInstanceArgs($args);
    }

    protected $_data = array();

    public function api($api)
    {
        return static::client()->api($api);
    }

    public function hydrate(array $data = array())
    {
        if (!empty($data)) {
            foreach ($data as $k => $v) {
                $this->$k = $v;
            }
        }

        return $this;
    }

    public function __set($property, $value)
    {
        if (!in_array($property, static::$_properties)) {
            throw new Exception\RuntimeException(sprintf('Property "%s" does not exist for %s object', $property, get_called_class()));
        }

        $this->_data[$property] = $value;
    }

    public function __get($property)
    {
        if (!in_array($property, static::$_properties)) {
            throw new Exception\RuntimeException(sprintf('Property "%s" does not exist for %s object', $property, get_called_class()));
        }

        if (isset($this->_data[$property])) {
            return $this->_data[$property];
        }

        return null;
    }

}
