<?php

namespace Github\Model;

class Milestone extends AbstractModel
{
    protected static $_properties = array(
        'issue',
        'id',
        'url',
        'number',
        'state',
        'title',
        'description',
        'creator',
        'open_issues',
        'closed_issues',
        'created_at',
        'due_on'
    );

    public static function factory(Issue $issue, $number)
    {
        return new Milestone($issue, $number);
    }

    public static function fromArray($issue, array $data)
    {
        $milestone = Milestone::factory($issue, $data['number']);

        if (isset($data['creator'])) {
            $data['creator'] = User::fromArray($data['creator']);
        }

        return $milestone->hydrate($data);
    }

    public function __construct(Issue $issue, $number)
    {
        $this->issue = $issue;
        $this->number = $number;
    }

    public function show()
    {
        $data = $this->api('issue')->milestones()->show(
            $this->issue->repo->owner->login,
            $this->issue->repo->name,
            $this->number
        );

        return Milestone::fromArray($this->issue, $data);
    }

    public function update(array $data)
    {
        $data = $this->api('issue')->milestones()->update(
            $this->issue->repo->owner->login,
            $this->issue->repo->name,
            $this->number,
            $data
        );

        return Milestone::fromArray($this->issue, $data);
    }

    public function remove()
    {
        $this->api('issue')->milestones()->remove(
            $this->issue->repo->owner->login,
            $this->issue->repo->name,
            $this->number
        );

        return true;
    }

    public function labels()
    {
        $data = $this->api('issue')->milestones()->labels(
            $this->issue->repo->owner->login,
            $this->issue->repo->name,
            $this->number
        );

        $labels = array();
        foreach ($data as $label) {
            $labels[] = Label::fromArray($this->issue->repo, $label);
        }

        return $labels;
    }
}
