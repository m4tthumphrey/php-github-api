<?php

namespace Github\Model;

class ReviewComment extends AbstractModel implements CommentInterface
{
    protected static $_properties = array(
        'pull_request',
        'id',
        'url',
        'body',
        'path',
        'position',
        'commit_id',
        'user',
        'created_at',
        'updated_at',
        '_links'
    );

    public static function fromArray(PullRequest $pull_request, array $data)
    {
        $comment = ReviewComment::factory($pull_request, $data['id']);

        if (isset($data['user'])) {
            $data['user'] = User::fromArray($data['user']);
        }

        return $comment->hydrate($data);
    }

    public function __construct(PullRequest $pull_request, $id)
    {
        $this->id           = $id;
        $this->pull_request = $pull_request;
    }

    public function show()
    {
        $data = $this->api('pull_request')->comments()->show(
            $this->pull_request->repo->owner->login,
            $this->pull_request->repo->name,
            $this->id
        );

        return ReviewComment::fromArray($this->pull_request, $data);
    }

    public function update($body)
    {
        $data = $this->api('pull_request')->comments()->update(
            $this->pull_request->repo->owner->login,
            $this->pull_request->repo->name,
            $this->id,
            array('body' => $body)
        );

        return ReviewComment::fromArray($this->pull_request, $data);
    }

    public function remove()
    {
        $this->api('pull_request')->comments()->remove(
            $this->pull_request->repo->owner->login,
            $this->pull_request->repo->name,
            $this->id
        );

        return true;
    }
}
