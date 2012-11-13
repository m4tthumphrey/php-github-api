<?php

namespace Github\Model;

class User extends Owner
{
    public function getRepoPath()
    {
        return '/user/repos';
    }
}
