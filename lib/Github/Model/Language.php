<?php

namespace Github\Model;

class Language extends AbstractModel
{
    protected static $_properties = array(
        'name',
        'size'
    );

    public static function fromArray(array $data)
    {
        return Language::factory($data);
    }

    public function __construct(array $data)
    {
        $this->hydrate($data);
    }
}
