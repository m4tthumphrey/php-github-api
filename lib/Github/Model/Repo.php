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

    public static function fromArray(array $data)
    {
        if (!isset($data['owner'])) {
            throw new Exception\MissingArgumentException(array('owner'));
        }

        $data['owner'] = Owner::fromArray($data['owner']);

        if (isset($data['organization'])) {
            $data['organization'] = Org::fromArray($data['organization']);
        }

        if (isset($data['parent'])) {
            $data['parent'] = Repo::fromArray($data['parent']);
        }

        if (isset($data['source'])) {
            $data['source'] = Repo::fromArray($data['source']);
        }

        $repo = new Repo($data['owner'], $data['name']);

        return $repo->hydrate($data);
    }

    public function __construct(Owner $owner, $name)
    {
        $this->owner    = $owner;
        $this->name     = $name;
    }

    public function show()
    {
        $data = $this->api('repo')->show(
            $this->owner->login,
            $this->name
        );

        return Repo::fromArray($data);
    }

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

    public function issue($number)
    {
        return new Issue($this, $number);
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

    public function label($name)
    {
        $data = $this->api('repo')->labels()->show(
            $this->owner->login,
            $this->name,
            $name
        );

        return Label::fromArray($this, $data);
    }

    // Todo: check label doesnt exist
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

    public function updateLabel($name, $color)
    {
        $label = new Label($this, $name);

        return $label->update($name, $color);
    }

    public function removeLabel($name)
    {
        $label = new Label($this, $name);

        return $label->remove($name);
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

    public function key($id)
    {
        $data = $this->api('repo')->keys()->show(
            $this->owner->login,
            $this->name,
            $id
        );

        return DeployKey::fromArray($this, $data);
    }

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

    public function updateKey($id, $title, $key)
    {
        $deployKey = new DeployKey($this, $id);

        return $deployKey->update($title, $key);
    }

    public function removeKey($id)
    {
        $deployKey = new DeployKey($this, $id);

        return $deployKey->remove();
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

    public function event($event)
    {
        $data = $this->api('issue')->events()->show(
            $this->owner->login,
            $this->name,
            $event
        );

        return Event::fromArray($this, $data);
    }

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

    public function pullRequest($number)
    {
        $pull_request = new PullRequest($this, $number);

        return $pull_request->show();
    }

    public function updatePullRequest($number, array $params)
    {
        $pull_request = new PullRequest($this, $number);

        return $pull_request->update($params);
    }
}
