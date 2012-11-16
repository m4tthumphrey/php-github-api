<?php

namespace Github\Model;

class DeployKey extends Key implements KeyInterface
{
    protected static $_properties = array(
        'id',
        'url',
        'title',
        'key'
    );

    public static function fromArray(Repo $repo, array $data)
    {
        $key = DeployKey::factory($repo, $data['id']);

        return $key->hydrate($data);
    }

    public function __construct(Repo $repo, $id)
    {
        $this->repo     = $repo;
        $this->id       = $id;
    }

    public function update($title, $key)
    {
        return $this->api('repo')->keys()->update(
            $this->repo->owner->name,
            $this->repo->name,
            $this->id,
            array(
                'title' => $title,
                'key'   => $key
            )
        );
    }

    public function remove()
    {
        return $this->api('repo')->keys()->remove(
            $this->repo->owner->name,
            $this->repo->name,
            $this->id
        );
    }
}
