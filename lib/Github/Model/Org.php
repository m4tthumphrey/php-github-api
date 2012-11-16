<?php

namespace Github\Model;

class Org extends Owner implements OwnerInterface
{
    public static function factory($login)
    {
        return new Org($login);
    }

    /**
     * @return Org
     */
    public function show()
    {
        $data = $this->api('organization')->show(
            $this->login
        );

        return Org::fromArray($data);
    }

    /**
     * @return Org
     */
    public function update(array $params)
    {
        $data = $this->api('organization')->update(
            $this->login,
            $params
        );

        return Org::fromArray($data);
    }

    /**
     * @return Repo
     */
    public function createRepo($name, array $params = array())
    {
        $params['organization'] = $this->login;

        return parent::createRepo($name, $params);
    }

    public function members($type = null)
    {
        $data = $this->api('organization')->members()->all(
            $this->login,
            $type
        );

        $members = array();
        foreach ($data as $member) {
            $members[] = User::fromArray($member);
        }

        return $members;
    }

    public function publicMembers()
    {
        return $this->members('public');
    }

    public function checkMembership($login)
    {
        return $this->api('organization')->members()->show(
            $this->login,
            $login
        );
    }

    public function checkPublicMembership($login)
    {
        return $this->api('organization')->members()->check(
            $this->login,
            $login
        );
    }

    public function publicizeMember($login)
    {
        return $this->api('organization')->members()->publicize(
            $this->login,
            $login
        );
    }

    public function concealMember($login)
    {
        return $this->api('organization')->members()->conceal(
            $this->login,
            $login
        );
    }

    public function removeMember($login)
    {
        return $this->api('organization')->members()->remove(
            $this->login,
            $login
        );
    }

    /**
     * @return Team
     */
    public function createTeam($name, array $params = array())
    {
        $params['name'] = $name;

        $data = $this->api('organization')->teams()->create(
            $this->login,
            $params
        );

        return Team::fromArray($this, $data);
    }

    public function teams()
    {
        $data = $this->api('organization')->teams()->all(
            $this->login
        );

        $teams = array();
        foreach ($data as $team) {
            $teams[] = Team::fromArray($this, $team);
        }

        return $teams;
    }

    /**
     * @return Team
     */
    public function team($id)
    {
        return Team::factory($this, $id)->show();
    }

    public function removeTeam($id)
    {
        return Team::factory($this, $id)->remove();
    }
}
