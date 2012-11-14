<?php

namespace Github\Model;

class Owner extends AbstractModel
{
    protected static $_properties = array(
        'login',
        'id',
        'url',
        'avatar_url',
        'gravatar_id',
        'name',
        'company',
        'location',
        'email',
        'hireable',
        'bio',
        'public_repos',
        'public_gists',
        'followers',
        'following',
        'html_url',
        'created_at',
        'type'
    );

    public static function fromArray(array $data)
    {
        $owner = new Owner($data['login']);

        return $owner->hydrate($data);
    }

    public function __construct($login)
    {
        $this->login = $login;
    }

    public function repo($name)
    {
        return new Repo($this, $name);
    }

    public function createRepo($name, array $params = array())
    {
        $params = array_merge(array(
            'name'                  => $name,
            'description'           => null,
            'homepage'              => null,
            'public'                => true,
            'has_issues'            => true,
            'has_wiki'              => true,
            'has_downloads'         => true,
            'team_id'               => null,
            'auto_init'             => false,
            'gitignore_template'    => null
        ), $params);

        $data = $this->api('repo')->post(
            $this->getCreateRepoPath(),
            $params
        );

        $repo = new Repo($this, $name);
        $repo->hydrate($data);

        return $repo;
    }
}
