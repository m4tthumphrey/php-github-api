<?php

namespace Github\Model;

abstract class Owner extends AbstractModel
{
    public $name;

    public function __construct($name)
    {
        $this->name = $name;
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
        ));

        return $this->api('repo')->post($this->getRepoPath(), $params);
    }

    abstract protected function getRepoPath();
}
