<?php

namespace Github\Model;

class Team extends AbstractModel
{
    protected static $_properties = array(
        'id',
        'org',
        'url',
        'name',
        'permission',
        'members_count',
        'repos_count'
    );

    public static function fromArray(Org $org, array $data)
    {
        $team = Team::factory($org, $data['id']);

        return $team->hydrate($data);
    }

    public function __construct(Org $org, $id)
    {
        $this->org  = $org;
        $this->id   = $id;
    }

    public function show()
    {
        $data = $this->api('organization')->teams()->show(
            $this->id
        );

        return Team::fromArray($this->org, $data);
    }

    public function update(array $params)
    {
        $data = $this-api('organization')->teams()->update(
            $this->id,
            $params
        );

        return Team::fromArray($this->org, $data);
    }

    public function remove()
    {
        return $this->api('organization')->teams()->remove(
            $this->id
        );
    }

    public function members()
    {
        $data = $this->api('organization')->teams()->members(
            $this->id
        );

        $members = array();
        foreach ($data as $member) {
            $members[] = User::fromArray($member);
        }

        return $member;
    }

    public function check($login)
    {
        return $this->api('organization')->teams()->check(
            $this->id,
            $login
        );
    }

    public function addMember($login)
    {
        return $this->api('organization')->teams()->addMember(
            $this->id,
            $login
        );
    }

    public function removeMember($login)
    {
        return $this->api('organization')->teams()->removeMember(
            $this->id,
            $login
        );
    }

    public function repositories()
    {
        $data = $this->api('organization')->teams()->repositories(
            $this->id
        );

        $repos = array();
        foreach ($data as $repo) {
            $repos[] = Repo::fromArray($repo);
        }

        return $repos;
    }

    public function repository($login, $repository)
    {
        $data = $this->api('organization')->teams()->repository(
            $this->id,
            $login,
            $repository
        );

        return Repo::fromArray($data);
    }

    public function addRepository($login, $repository)
    {
        $data = $this->api('organization')->teams()->addRepository(
            $this->id,
            $login,
            $repository
        );

        return Repo::fromArray($data);
    }

    public function removeRepository($login, $repository)
    {
        return $this->api('organization')->teams()->removeRepository(
            $this->id,
            $login,
            $repository
        );
    }

}
