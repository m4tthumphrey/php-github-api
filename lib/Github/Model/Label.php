<?php

namespace Github\Model;

class Label extends AbstractModel
{
    protected static $_properties = array(
        'repo',
        'url',
        'name',
        'color'
    );

    public static function fromArray(Repo $repo, array $data)
    {
        $label = Label::factory($repo, $data['name']);

        return $label->hydrate($data);
    }

    public function __construct(Repo $repo, $name)
    {
        $this->repo = $repo;
        $this->name = $name;
    }

    public function update($name, $color)
    {
        $data = $this->api('repo')->labels()->update(
            $this->repo->owner->login,
            $this->repo->name,
            $this->name,
            array(
                'name' => $name,
                'color' => $color
            )
        );

        return Label::fromArray($this->repo, $data);
    }

    public function remove()
    {
        $this->api('repo')->labels()->remove(
            $this->repo->owner->login,
            $this->repo->name,
            $this->name
        );

        return true;
    }

}
