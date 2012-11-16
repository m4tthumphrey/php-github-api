<?php

namespace Github\Model;

use Github\Exception;

class Repo extends AbstractModel
{
    protected static $_properties = array(
        'id',
        'owner',
        'name',
        'full_name',
        'description',
        'private',
        'fork',
        'url',
        'html_url',
        'clone_url',
        'git_url',
        'ssh_url',
        'svn_url',
        'mirror_url',
        'homepage',
        'language',
        'forks',
        'forks_count',
        'watchers',
        'watchers_count',
        'network_count',
        'size',
        'master_branch',
        'open_issues',
        'open_issues_count',
        'has_issues',
        'has_wiki',
        'has_downloads',
        'pushed_at',
        'created_at',
        'updated_at',
        'organization',
        'parent',
        'source',
        'permissions'
    );

    public static function factory(Owner $owner, $name)
    {
        return new Repo($owner, $name);
    }

    /**
     * @return Repo
     */
    public static function fromArray(array $data)
    {
        if (!isset($data['owner'])) {
            throw new Exception\MissingArgumentException('owner');
        }

        $data['owner'] = Owner::fromArray($data['owner']);

        $repo = Repo::factory($data['owner'], $data['name']);

        if (isset($data['organization'])) {
            $data['organization'] = Org::fromArray($data['organization']);
        }

        if (isset($data['parent'])) {
            $data['parent'] = Repo::fromArray($data['parent']);
        }

        if (isset($data['source'])) {
            $data['source'] = Repo::fromArray($data['source']);
        }

        return $repo->hydrate($data);
    }

    public function __construct(Owner $owner, $name)
    {
        $this->owner    = $owner;
        $this->name     = $name;
    }

    /**
     * @return Repo
     */
    public function show()
    {
        $data = $this->api('repo')->show(
            $this->owner->login,
            $this->name
        );

        return Repo::fromArray($data);
    }


    /**
     * @return Repo
     */
    public function update(array $params)
    {
        $data = $this->api('repo')->update(
            $this->owner->login,
            $this->name,
            $params
        );

        return Repo::fromArray($data);
    }

    public function remove()
    {
        $this->api('repo')->remove(
            $this->owner->login,
            $this->name
        );

        return true;
    }

    /**
     * @return Issue
     */
    public function createIssue($title, array $params)
    {
        $params['title'] = $title;

        $issue = $this->api('issue')->create(
            $this->owner->login,
            $this->name,
            $params
        );

        return $this->getIssue($issue['number']);
    }

    public function issues(array $params = array())
    {
        $data = $this->api('issue')->all(
            $this->owner->login,
            $this->name,
            $params
        );

        $issues = array();
        foreach ($data as $issue) {
            $issues[] = Issue::fromArray($this, $issue);
        }

        return $issues;
    }

    /**
     * @return Issue
     */
    public function issue($number)
    {
        return Issue::factory($this, $number)->show();
    }

    public function labels()
    {
        $data = $this->api('repo')->labels()->all(
            $this->owner->login,
            $this->name
        );

        $labels = array();
        foreach ($data as $label) {
            $labels[] = Label::fromArray($this, $label);
        }

        return $labels;
    }

    /**
     * @return Issue
     */
    public function label($name)
    {
        $data = $this->api('repo')->labels()->show(
            $this->owner->login,
            $this->name,
            $name
        );

        return Label::fromArray($this, $data);
    }

    /**
     * @todo: Check label doesn't exist
     * @return Label
     */
    public function addLabel($name, $color)
    {
        $data = $this->api('repo')->labels()->create(
            $this->owner->login,
            $this->name,
            array(
                'name' => $name,
                'color' => $color
            )
        );

        return Label::fromArray($this, $data);
    }

    /**
     * @return Label
     */
    public function updateLabel($name, $color)
    {
        return Label::factory($this, $name)->update($name, $color);
    }

    public function removeLabel($name)
    {
        return Label::factory($this, $name)->remove($name);
    }

    public function removeLabels()
    {
        foreach ($this->labels() as $label) {
            $label->remove();
        }

        return true;
    }

    public function keys()
    {
        $data = $this->api('repo')->keys()->all(
            $this->owner->login,
            $this->name
        );

        $keys = array();
        foreach ($data as $key) {
            $keys[] = DeployKey::fromArray($this, $key);
        }

        return $keys;
    }

    /**
     * @return DeployKey
     */
    public function key($id)
    {
        $data = $this->api('repo')->keys()->show(
            $this->owner->login,
            $this->name,
            $id
        );

        return DeployKey::fromArray($this, $data);
    }

    /**
     * @return DeployKey
     */
    public function addKey($title, $key)
    {
        $data = $this->api('repo')->keys()->create(
            $this->owner->login,
            $this->name,
            array(
                'title' => $title,
                'key' => $key
            )
        );

        return DeployKey::fromArray($this, $data);
    }

    /**
     * @return DeployKey
     */
    public function updateKey($id, $title, $key)
    {
        return DeployKey::factory($this, $id)->update($title, $key);
    }

    public function removeKey($id)
    {
        return DeployKey::factory($this, $id)->remove();
    }

    public function events()
    {
        $data = $this->api('issue')->events()->all(
            $this->owner->login,
            $this->name
        );

        $events = array();
        foreach ($data as $event) {
            $events[] = Event::fromArray($this, $event);
        }

        return $events;
    }

    /**
     * @return Event
     */
    public function event($event)
    {
        $data = $this->api('issue')->events()->show(
            $this->owner->login,
            $this->name,
            $event
        );

        return Event::fromArray($this, $data);
    }

    /**
     * @return PullRequest
     */
    public function createPullRequest(array $params)
    {
        $data = $this->api('pull_request')->create(
            $this->owner->login,
            $this->name,
            $params
        );

        return PullRequest::fromArray($this, $data);
    }

    public function pullRequests($state = null)
    {
        $data = $this->api('pull_request')->all(
            $this->owner->login,
            $this->name,
            $state
        );

        $pull_requests = array();
        foreach ($data as $pull_request) {
            $pull_requests[] = PullRequest::fromArray($this, $pull_request);
        }

        return $pull_requests;
    }

    /**
     * @return PullRequest
     */
    public function pullRequest($number)
    {
        return PullRequest::factory($this, $number)->show();
    }

    /**
     * @return PullRequest
     */
    public function updatePullRequest($number, array $params)
    {
        return PullRequest::factory($this, $number)->update($params);
    }

    public function languages()
    {
        $data = $this->api('repos')->languages(
            $this->owner->login,
            $this->name
        );

        $languages = array();
        foreach ($data as $language => $id) {
            $languages[] = Language::fromArray(array(
                'name' => $language,
                'size' => $id
            ));
        }

        return $languages;
    }

    public function teams()
    {
        $data = $this->api('repos')->teams()->all(
            $this->owner->login,
            $this->name
        );

        $teams = array();
        foreach ($data as $team) {
            $teams[] = Team::fromArray($this->owner, $team);
        }

        return $teams;
    }

    /**
     * @return Hook
     */
    public function addHook($name, array $config, array $events = null, $active = true)
    {
        $data = $this->api('repo')->hooks()->create(
            $this->owner->login,
            $this->name,
            array(
                'name' => $name,
                'config' => $config,
                'events' => $events,
                'active' => $active
            )
        );

        return Hook::fromArray($this, $data);
    }

    /**
     * @return Hook
     */
    public function hook($id)
    {
        return Hook::factory($this, $id)->show();
    }

    public function hooks()
    {
        $data = $this->api('repo')->hooks()->all(
            $this->owner->login,
            $this->name
        );

        $hooks = array();
        foreach ($data as $hook) {
            $hooks[] = Hook::fromArray($this, $hook);
        }

        return $hooks;
    }

    /**
     * @return Hook
     */
    public function updateHook($id, array $params)
    {
        return Hook::factory($this, $id)->update($params);
    }

    public function removeHook($id)
    {
        return Hook::factory($this, $id)->remove();
    }

    /**
     * @return Repo
     */
    public function fork($org = null)
    {
        $data = $this->api('repo')->forks()->create(
            $this->owner->login,
            $this->name,
            array(
                'org' => $org
            )
        );

        return Repo::fromArray($data);
    }

    public function forks($sort = null)
    {
        $data = $this->api('repo')->forks()->all(
            $this->owner->login,
            $this->name,
            array(
                'sort' => $sort
            )
        );

        $forks = array();
        foreach ($data as $fork) {
            $forks[] = Repo::fromArray($fork);
        }

        return $forks;
    }
}
