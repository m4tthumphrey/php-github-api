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
        'blog',
        'location',
        'email',
        'billing_email',
        'hireable',
        'bio',
        'public_repos',
        'public_gists',
        'followers',
        'following',
        'html_url',
        'created_at',
        'total_private_repos',
        'owned_private_repos',
        'private_gists',
        'collaborators',
        'plan',
        'disk_usage',
        'type'
    );

    public static function fromArray(array $data)
    {
        return static::factory($data['login'])->hydrate($data);
    }

    public function __construct($login)
    {
        $this->login = $login;
    }

    public function repo($name)
    {
        return Repo::factory($this, $name)->show();
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

        return Repo::factory($this, $name)->hydrate($data);
    }
}
