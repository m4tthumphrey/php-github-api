<?php

namespace Github\Model;

use Github\Client;

abstract class AbstractModel
{
    protected static $_client;

    public static function client(Client $client = null)
    {
        if (null !== $client) {
            static::$_client = $client;
        }

        return static::$_client;
    }

    public function api($api)
    {
        return static::client()->api($api);
    }

}
