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

    public static function factory($login)
    {
        return new Owner($login);
    }

    public static function fromArray(array $data)
    {
        return static::factory($data['login'])->hydrate($data);
    }

    public function __construct($login)
    {
        $this->login = $login;
    }

    /**
     * @return Repo
     */
    public function repo($name)
    {
        return Repo::factory($this, $name)->show();
    }

    /**
     * @return Repo
     */
    public function createRepo($name, array $params = array())
    {
        $params = array_merge(array(
            'description'           => null,
            'homepage'              => null,
            'public'                => true,
            'organization'          => null
        ), $params);

        $data = $this->api('repo')->create(
            $name,
            $params['description'],
            $params['homepage'],
            $params['public'],
            $params['organization']
        );

        return Repo::fromArray($data);
    }

    /**
     * @return Repo
     */
    public function updateRepo($name, array $params)
    {
        return Repo::factory($this, $name)->update($params);
    }

    /**
     * @return Repo
     */
    public function removeRepo($name)
    {
        return Repo::factory($this, $name)->remove();
    }
}
