<?php
namespace PComm\WPUtils\Post;

interface PostInterface {
    public function getPostID();
    public function getPostTitle();
    public function getPostExcerpt();
    public function getPostContent();
    public function getPostSlug();
    public function getMenuOrder();
    public function getPostType();
    public function addTerm(\PComm\WPUtils\Term\TermInterface $term);
    public function getTermsByTaxonomy($string);
    public function getCustom($field);
    public function getPostThumbnail();
}