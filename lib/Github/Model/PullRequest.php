<?php

namespace Github\Model;

class PullRequest extends Issue
{
    protected static $_properties = array(
        'repo',
        'url',
        'id',
        'html_url',
        'diff_url',
        'patch_url',
        'issue_url',
        'number',
        'state',
        'title',
        'body',
        'created_at',
        'updated_at',
        'closed_at',
        'merged',
        'merged_at',
        'merged_by',
        'mergeable',
        'mergeable_state',
        'merge_commit_sha',
        'changed_files',
        'comments',
        'review_comments',
        'assignee',
        'commits',
        'deletions',
        'milestone',
        'additions',
        'head',
        'base',
        '_links',
        'user'
    );

    public static function fromArray(Repo $repo, array $data)
    {
        $pull_request = PullRequest::factory($repo, $data['number']);

        if (isset($data['head'])) {
            $data['head'] = Ref::fromArray($data['head']);
        }

        if (isset($data['base'])) {
            $data['base'] = Ref::fromArray($data['base']);
        }

        if (isset($data['user'])) {
            $data['user'] = User::fromArray($data['user']);
        }

        if (isset($data['assignee'])) {
            $data['assignee'] = User::fromArray($data['assignee']);
        }

        if (isset($data['merged_by'])) {
            $data['merged_by'] = User::fromArray($data['merged_by']);
        }

        if (isset($data['milestone'])) {
            $data['milestone'] = Milestone::fromArray($pull_request, $data['milestone']);
        }

        return $pull_request->hydrate($data);
    }

    public function __construct(Repo $repo, $number)
    {
        $this->repo     = $repo;
        $this->number   = $number;
    }

    public function show()
    {
        $data = $this->api('pull_request')->show(
            $this->repo->owner->login,
            $this->repo->name,
            $this->number
        );

        return PullRequest::fromArray($this->repo, $data);
    }

    public function update(array $params)
    {
        $data = $this->api('pull_request')->update(
            $this->repo->owner->login,
            $this->repo->name,
            $this->number,
            $params
        );

        return PullRequest::fromArray($this->repo, $data);
    }

    public function commits()
    {
        $data = $this->api('pull_request')->commits(
            $this->repo->owner->login,
            $this->repo->name,
            $this->number
        );

        $commits = array();
        foreach ($data as $commit) {
            $commits[] = Commit::fromArray($this->repo, $commit);
        }

        return $commits;
    }

    public function files()
    {
        $data = $this->api('pull_request')->files(
            $this->repo->owner->login,
            $this->repo->name,
            $this->number
        );

        $files = array();
        foreach ($data as $file) {
            $files[] = (object) $file;
        }

        return $files;
    }

    public function merged()
    {
        return $data = $this->api('pull_request')->merged(
            $this->repo->owner->login,
            $this->repo->name,
            $this->number
        );
    }

    // TODO: Handle result better
    public function merge($message = null)
    {
        $data = $this->api('pull_request')->merged(
            $this->repo->owner->login,
            $this->repo->name,
            $this->number,
            $message
        );

        return (object) $data;
    }
}
