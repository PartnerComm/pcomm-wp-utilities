<?php
namespace PComm\WPUtils\Taxonomy;

class DefaultDefinition implements DefinitionInterface {

    protected $slug = '';
    protected $postType = [];
    protected $plural = '';
    protected $single = '';
    protected $public = true;
    protected $rest = false;
    protected $hierarchical = true;

    public function getSlug()
    {
        return $this->slug;
    }

    public function getPostTypes()
    {
        return $this->postType;
    }

    public function getPluralName()
    {
        return $this->plural;
    }

    public function getSingleName()
    {
        return $this->single;
    }

    public function getIsPublic()
    {
        return $this->public;
    }

    public function getRestSupport()
    {
        return $this->rest;
    }

    public function getIsHierarchical()
    {
        return $this->hierarchical;
    }
}