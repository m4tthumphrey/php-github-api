<?php

namespace Github\Model;

class Commit extends AbstractModel
{
    protected static $_properties = array(
        'repo',
        'url',
        'sha',
        'commit',
        'author',
        'committer',
        'message',
        'tree',
        'parents'
    );

    public static function factory(Repo $repo, $sha)
    {
        return new Commit($repo, $sha);
    }

    public static function fromArray(Repo $repo, array $data)
    {
        $commit = Commit::factory($repo, $data['sha']);

        if (isset($data['author'])) {
            $data['author'] = User::fromArray($data['author']);
        }

        if (isset($data['committer'])) {
            $data['committer'] = User::fromArray($data['committer']);
        }

        if (isset($data['tree'])) {

        }

        if (isset($data['commit'])) {
            // Bit hacky but oh well, consistent..
            // TODO: Improve
            $data['commit'] = json_decode(json_encode($data['commit']));
        }

        if (isset($data['parents'])) {
            $parents = array();
            foreach ($data['parents'] as $parent) {
                $parents[] = Commit::fromArray($repo, $parent);
            }

            $data['parents'] = $parents;
        }

        return $commit->hydrate($data);
    }

    public function __construct(Repo $repo, $sha)
    {
        $this->repo = $repo;
        $this->sha  = $sha;
    }

    public function show()
    {
        $data = $this->api('git_data')->commits()->show(
            $this->repo->owner->login,
            $this->repo->name,
            $this->sha
        );

        return Commit::fromArray($this->repo, $data);
    }

}
