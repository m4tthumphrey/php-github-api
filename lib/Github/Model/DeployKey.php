<?php

namespace Github\Model;

class DeployKey extends Key
{
    public $repo;
    public $id;

    public function __construct(Repo $repo, $id)
    {
        $this->repo = $repo;
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
