<?php

namespace Github\Model;

class Org extends Owner
{
    public function getRepoPath()
    {
        return 'orgs/'.$this->name.'/repos';
    }
}
