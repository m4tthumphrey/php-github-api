<?php

namespace Github\Model;

class Issue extends AbstractModel
{
    public $repo;
    public $number;

    public function __construct(Repo $repo, $number = null)
    {
        $this->repo     = $repo;
        $this->number   = $number;
    }

    public function show()
    {
        return $this->api('issue')->show(
            $this->repo->owner->name,
            $this->repo->name,
            $this->number
        );
    }

    public function update(array $params)
    {
        return $this->api('issue')->update(
            $this->repo->owner->name,
            $this->repo->name,
            $this->number,
            $params
        );
    }

    public function labels()
    {
        return $this->api('issue')->labels()->all(
            $this->repo->owner->name,
            $this->repo->name,
            $this->number
        );
    }

    // todo: create label if not exists
    public function addLabels(array $labels)
    {
        return $this->api('issue')->labels()->add(
            $this->repo->owner->name,
            $this->repo->name,
            $this->number,
            $labels
        );
    }

    public function removeLabel($name)
    {
        return $this->api('issue')->labels()->remove(
            $this->repo->owner->name,
            $this->repo->name,
            $this->number,
            $name
        );
    }

    public function replaceLabels(array $labels)
    {
        return $this->api('issue')->labels()->replace(
            $this->repo->owner->name,
            $this->repo->name,
            $this->number,
            $labels
        );
    }

    public function clearLabels()
    {
        return $this->api('issue')->labels()->clear(
            $this->repo->owner->name,
            $this->repo->name,
            $this->number
        );
    }
}
