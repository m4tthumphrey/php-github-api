<?php

namespace Github\Model;

class User extends Owner implements OwnerInterface
{
    public static function factory($login)
    {
        return new User($login);
    }
}
