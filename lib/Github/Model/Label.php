<?php

namespace Github\Model;

class Label extends AbstractModel
{
    public $repo;
    public $name;

    public function __construct(Repo $repo, $name)
    {
        $this->repo = $repo;
        $this->name = $name;
    }

    public function update($name, $color)
    {
        return $this->api('repo')->labels()->update(
            $this->owner->name,
            $this->repo->name,
            $this->name,
            array(
                'name' => $name,
                'color' => $color
            )
        );
    }

    public function remove()
    {
        return $this->api('repo')->labels()->remove(
            $this->owner->name,
            $this->repo->name,
            $this->name
        );
    }

}
