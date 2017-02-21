<?php
namespace PComm\WPUtils\Post;

class DefaultDefinition implements DefinitionInterface {

    protected $slug = '';
    protected $single = '';
    protected $plural = '';

    protected $rest = false;

    /**
     * @var array [field => [update => '', get => '']]
     */
    protected $restFields = [];

    protected $icon = 'dashicons-admin-post';
    protected $taxonomies = ['category', 'post_tag'];
    protected $supports = ['title', 'editor', 'revisions', 'thumbnail', 'excerpt', 'page-attributes', 'custom-fields'];

    protected $capabilities = [];
    protected $excludeSearch = false;
    protected $archive = true;
    protected $public = true;

    public function getSlug()
    {
        return $this->slug;
    }

    public function getTaxonomies()
    {
        return $this->taxonomies;
    }

    public function getSupports()
    {
        return $this->supports;
    }

    public function getPluralName()
    {
        return $this->plural;
    }

    public function getSingleName()
    {
        return $this->single;
    }

    public function getMenuIcon()
    {
        return $this->icon;
    }

    public function getRestSupport()
    {
        return $this->rest;
    }

    public function getRestFields()
    {
        return $this->restFields;
    }

    public function getPublic()
    {
        return $this->public;
    }

    public function getHasArchive()
    {
        return $this->archive;
    }

    public function getExcludeFromSearch()
    {
        return $this->excludeSearch;
    }

    public function getCapabilities()
    {
        return $this->capabilities;
    }

}