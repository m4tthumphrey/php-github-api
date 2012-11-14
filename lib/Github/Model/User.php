<?php

namespace Github\Model;

class User extends Owner implements OwnerInterface
{
    public function getCreateRepoPath()
    {
        return '/user/repos';
    }
}
