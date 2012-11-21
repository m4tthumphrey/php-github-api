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
        'repos_count',
        'repositories_url',
        'members_url'
    );

    public static function factory(Owner $org, $id)
    {
        return new Team($org, $id);
    }

    public static function fromArray(Owner $org, array $data)
    {
        $team = Team::factory($org, $data['id']);

        return $team->hydrate($data);
    }

    public function __construct(Owner $org, $id)
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
        $this->api('organization')->teams()->remove(
            $this->id
        );

        return true;
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
        try {
            $this->api('organization')->teams()->check(
                $this->id,
                $login
            );

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function addMember($login)
    {
        $this->api('organization')->teams()->addMember(
            $this->id,
            $login
        );

        return true;
    }

    public function removeMember($login)
    {
        $this->api('organization')->teams()->removeMember(
            $this->id,
            $login
        );

        return true;
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

    public function repository($repository)
    {
        if ($repository instanceof Repo) {
            $repository = $repository->name;
        }

        $data = $this->api('organization')->teams()->repository(
            $this->id,
            $this->org->login,
            $repository
        );

        return Repo::fromArray($data);
    }

    public function addRepository($repository)
    {
        if ($repository instanceof Repo) {
            $repository = $repository->name;
        }

        $this->api('organization')->teams()->addRepository(
            $this->id,
            $this->org->login,
            $repository
        );

        return true;
    }

    public function removeRepository($repository)
    {
        if ($repository instanceof Repo) {
            $repository = $repository->name;
        }

        $this->api('organization')->teams()->removeRepository(
            $this->id,
            $this->org->login,
            $repository
        );

        return true;
    }

}
