<?php

namespace Github\Model;

class Ref extends AbstractModel
{
    protected static $_properties = array(
        'label',
        'ref',
        'sha',
        'user',
        'repo'
    );

    public static function fromArray(array $data)
    {
        if (isset($data['repo'])) {
            $data['repo'] = Repo::fromArray($data['repo']);
        }

        if (isset($data['user'])) {
            $data['user'] = User::fromArray($data['user']);
        }

        $ref = new Ref();

        return $ref->hydrate($data);
    }
}
