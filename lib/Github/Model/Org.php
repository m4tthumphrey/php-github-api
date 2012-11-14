<?php

namespace Github\Model;

class Org extends Owner implements OwnerInterface
{
    public function getCreateRepoPath()
    {
        return 'orgs/'.$this->name.'/repos';
    }
}
