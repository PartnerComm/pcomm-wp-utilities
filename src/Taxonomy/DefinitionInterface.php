<?php
namespace PComm\WPUtils\Taxonomy;

interface DefinitionInterface {
    public function getSlug();
    public function getPostTypes();
    public function getPluralName();
    public function getSingleName();
    public function getIsPublic();
    public function getRestSupport();
	public function getIsHierarchical();
	public function getRewrite();
}