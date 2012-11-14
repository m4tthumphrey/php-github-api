<?php

namespace Github\Model;

class Issue extends AbstractModel
{
    protected static $_properties = array(
        'id',
        'repo',
        'url',
        'html_url',
        'number',
        'state',
        'title',
        'body',
        'user',
        'labels',
        'assignee',
        'milestone',
        'comments',
        'pull_request',
        'closed_at',
        'closed_by',
        'created_at',
        'updated_at'
    );

    public static function fromArray(Repo $repo, array $data)
    {
        if (isset($data['user'])) {
            $data['user'] = User::fromArray($data['user']);
        }

        if (isset($data['labels'])) {
            $labels = array();
            foreach ($data['labels'] as $label) {
                $labels[] = Label::fromArray($repo, $label);
            }

            $data['labels'] = $labels;
        }

        if (isset($data['assignee'])) {
            $data['assignee'] = User::fromArray($data['assignee']);
        }

        if (isset($data['milestone'])) {

        }

        if (isset($data['pull_request'])) {

        }

        $issue = new Issue($repo, $data['number']);

        return $issue->hydrate($data);
    }

    public function __construct(Repo $repo, $number = null)
    {
        $this->repo     = $repo;
        $this->number   = $number;
    }

    public function show()
    {
        $data = $this->api('issue')->show(
            $this->repo->owner->login,
            $this->repo->name,
            $this->number
        );

        return Issue::fromArray($this->repo, $data);
    }

    public function update(array $params)
    {
        $data = $this->api('issue')->update(
            $this->repo->owner->login,
            $this->repo->name,
            $this->number,
            $params
        );

        return Issue::fromArray($this->repo, $data);
    }

    public function labels()
    {
        $data = $this->api('issue')->labels()->all(
            $this->repo->owner->login,
            $this->repo->name,
            $this->number
        );

        $labels = array();
        foreach ($data as $label) {
            $labels[] = Label::fromArray($this->repo, $label);
        }

        return $labels;
    }

    // todo: create label if not exists
    public function addLabels(array $labels)
    {
        $data = $this->api('issue')->labels()->add(
            $this->repo->owner->login,
            $this->repo->name,
            $this->number,
            $labels
        );

        $labels = array();
        foreach ($data as $label) {
            $labels[] = Label::fromArray($this->repo, $label);
        }

        return $labels;
    }

    public function addLabel($name)
    {
        return $this->addLabels(array($name));
    }

    public function removeLabel($name)
    {
        $data = $this->api('issue')->labels()->remove(
            $this->repo->owner->login,
            $this->repo->name,
            $this->number,
            $name
        );

        return Label::fromArray($this->repo, $data);
    }

    public function replaceLabels(array $labels)
    {
        $data = $this->api('issue')->labels()->replace(
            $this->repo->owner->login,
            $this->repo->name,
            $this->number,
            $labels
        );

        $labels = array();
        foreach ($data as $label) {
            $labels[] = Label::fromArray($this->repo, $label);
        }

        return $labels;
    }

    public function clearLabels()
    {
        $this->api('issue')->labels()->clear(
            $this->repo->owner->login,
            $this->repo->name,
            $this->number
        );

        return true;
    }

    public function createMilestone($title, array $params = array())
    {
        $params['title'] = $title;

        $data = $this->api('issue')->milestones()->create(
            $this->repo->owner->login,
            $this->repo->name,
            $params
        );

        return Milestone::fromArray($this, $data);
    }

    public function updateMilestone($number, array $data)
    {
        $milestone = new Milestone($this, $number);

        return $milestone->update($data);
    }

    public function removeMilestone($number)
    {
        $milestone = new Milestone($this, $number);

        return $milestone->remove();
    }
}
