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
        $issue = new Issue($repo, $data['number']);

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
            $data['milestone'] = Milestone::fromArray($issue, $data['milestone']);
        }

        if (isset($data['pull_request'])) {

        }

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

    public function milestones(array $params = array())
    {
        $data = $this->api('issue')->milestones()->all(
            $this->repo->owner->login,
            $this->repo->name,
            $params
        );

        $milestones = array();
        foreach ($data as $milestone) {
            $milestones[] = Milestone::fromArray($this, $milestone);
        }

        return $milestones;
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

    public function addComment($body)
    {
        $data = $this->api('issue')->comments()->create(
            $this->repo->owner->login,
            $this->repo->name,
            $this->number,
            array('body' => $body)
        );

        return Comment::fromArray($this, $data);
    }

    public function updateComment($id, $body)
    {
        $comment = new Comment($this, $id);

        return $comment->update($body);
    }

    public function removeComment($id)
    {
        $comment = new Comment($this, $id);

        return $comment->remove($id);
    }

    public function events()
    {
        $data = $this->api('issue')->events()->all(
            $this->repo->owner->login,
            $this->repo->name,
            $this->number
        );

        $events = array();
        foreach ($data as $event) {
            $events[] = Event::fromArray($this->repo, $event, $this);
        }

        return $events;
    }
}
