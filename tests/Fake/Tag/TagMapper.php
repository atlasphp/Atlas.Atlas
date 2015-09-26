<?php
namespace Atlas\Fake\Tag;

use Atlas\Fake\Thread2Tag\Thread2TagMapper;
use Atlas\Fake\Thread\ThreadMapper;
use Atlas\Mapper\Mapper;

class TagMapper extends Mapper
{
    protected function setRelations()
    {
        $this->relations->oneToMany('threads2tags', Thread2TagMapper::CLASS);
        $this->relations->manyToMany('threads', ThreadMapper::CLASS, 'threads2tags');
    }
}
