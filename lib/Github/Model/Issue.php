<?php

namespace Github\Model;

class Issue extends AbstractModel
{
    public $owner;
    public $repo;
    public $number;

    public function __construct(Owner $owner, Repo $repo, $number = null)
    {
        $this->owner    = $owner;
        $this->repo     = $repo;
        $this->number   = $number;
    }

    public function show()
    {
        return $this->api('issue')->show(
            $this->owner->name,
            $this->repo->name,
            $this->number
        );
    }

    public function update(array $params)
    {
        return $this->api('issue')->update(
            $this->owner->name,
            $this->repo->name,
            $this->number,
            $params
        );
    }

    public function labels()
    {
        return $this->api('issue')->labels()->all(
            $this->owner->name,
            $this->repo->name,
            $this->number
        );
    }

    // todo: create label if not exists
    public function addLabels(array $labels)
    {
        return $this->api('issue')->labels()->add(
            $this->owner->name,
            $this->repo->name,
            $this->number,
            $labels
        );
    }

    public function removeLabel($name)
    {
        return $this->api('issue')->labels()->remove(
            $this->owner->name,
            $this->repo->name,
            $this->number,
            $name
        );
    }

    public function replaceLabels(array $labels)
    {
        return $this->api('issue')->labels()->replace(
            $this->owner->name,
            $this->repo->name,
            $this->number,
            $labels
        );
    }

    public function clearLabels()
    {
        return $this->api('issue')->labels()->clear(
            $this->owner->name,
            $this->repo->name,
            $this->number
        );
    }
}
