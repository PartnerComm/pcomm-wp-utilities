<?php
namespace PComm\WPUtils\Post;

interface DefinitionInterface {
    public function getSlug();
    public function getTaxonomies();
    public function getSupports();
    public function getPluralName();
    public function getSingleName();
    public function getMenuIcon();
    public function getRestSupport();
    public function getRestFields();
    public function getPublic();
    public function getHasArchive();
    public function getExcludeFromSearch();
    public function getCapabilities();
    public function getMetaFields();
}