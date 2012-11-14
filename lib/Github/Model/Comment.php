<?php

namespace Github\Model;

class Comment extends AbstractModel
{
    protected static $_properties = array(
        'issue',
        'id',
        'url',
        'body',
        'user',
        'created_at',
        'updated_at'
    );

    public static function fromArray(Issue $issue, array $data)
    {
        if (isset($data['user'])) {
            $data['user'] = User::fromArray($data['user']);
        }

        $comment = new Comment($issue, $data['id']);

        return $comment->hydrate($data);
    }

    public function __construct(Issue $issue, $id)
    {
        $this->id       = $id;
        $this->issue    = $issue;
    }

    public function update($body)
    {
        $data = $this->api('issue')->comments()->update(
            $this->issue->repo->owner->login,
            $this->issue->repo->name,
            $this->id,
            array('body' => $body)
        );

        return Comment::fromArray($this->issue, $data);
    }

    public function remove()
    {
        $this->api('issue')->comments()->remove(
            $this->issue->repo->owner->login,
            $this->issue->repo->name,
            $this->id
        );

        return true;
    }
}
