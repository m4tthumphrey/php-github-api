<?php

namespace Github\Model;

class Hook extends AbstractModel
{
    protected static $_properties = array(
        'repo',
        'url',
        'updated_at',
        'created_at',
        'name',
        'events',
        'active',
        'config',
        'id',
        'last_response'
    );

    public static function fromArray(Repo $repo, array $data)
    {
        $hook = Hook::factory($repo, $data['id']);

        if (isset($data['last_response'])) {
            $data['last_response'] = (object) $data['last_response'];
        }

        if (isset($data['config'])) {
            $data['config'] = (object) $data['config'];
        }

        return $hook->hydrate($data);
    }

    public function __construct(Repo $repo, $id)
    {
        $this->id   = $id;
        $this->repo = $repo;
    }

    public function show()
    {
        $data = $this->api('repo')->hooks()->show(
            $this->repo->owner->login,
            $this->repo->name,
            $this->id
        );

        return Hook::fromArray($this, $data);
    }

    public function update(array $params)
    {
        $data = $this->api('repo')->hooks()->update(
            $this->repo->owner->login,
            $this->repo->name,
            $this->id,
            $params
        );

        return Hook::fromArray($this, $data);
    }

    public function remove()
    {
        $this->api('repo')->hooks()->remove(
            $this->repo->owner->login,
            $this->repo->name,
            $this->id
        );

        return true;
    }

    public function test()
    {
        $this->api('repo')->hooks()->test(
            $this->repo->owner->login,
            $this->repo->name,
            $this->id
        );

        return true;
    }

}
