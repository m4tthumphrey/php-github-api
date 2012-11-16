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

    public static function factory(Repo $repo, $id)
    {
        return new DeployKey($repo, $id);
    }

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
        $data = $this->api('repo')->keys()->update(
            $this->repo->owner->name,
            $this->repo->name,
            $this->id,
            array(
                'title' => $title,
                'key'   => $key
            )
        );

        return DeployKey::fromArray($this->repo, $data);
    }

    public function remove()
    {
        $this->api('repo')->keys()->remove(
            $this->repo->owner->name,
            $this->repo->name,
            $this->id
        );

        return true;
    }
}
