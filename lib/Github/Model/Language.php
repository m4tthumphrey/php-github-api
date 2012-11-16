<?php

namespace Github\Model;

class Language extends AbstractModel
{
    protected static $_properties = array(
        'name',
        'size'
    );

    public static function factory(array $data)
    {
        return new Language($data);
    }

    public static function fromArray(array $data)
    {
        return Language::factory($data);
    }

    public function __construct(array $data)
    {
        $this->hydrate($data);
    }
}
