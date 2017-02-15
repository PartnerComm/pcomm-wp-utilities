<?php
namespace PComm\WPUtils\Post;

class Post implements PostInterface {

    /**
     * @var \WP_Post
     */
    protected $original_post;

    /**
     * @var \PComm\WPUtils\Term\TermInterface[]
     */
    protected $terms = [];

    public function __construct(\WP_Post $post)
    {
        $this->original_post = $post;
    }

    public function getPostID()
    {
        return $this->original_post->ID;
    }

    public function getPostTitle()
    {
        return $this->original_post->post_title;
    }

    public function getPostExcerpt()
    {
        return $this->original_post->post_excerpt;
    }

    public function getPostContent()
    {
        return str_replace(']]>',']]&gt>', apply_filters('the_content', $this->original_post->post_content));
    }

    public function getPostSlug()
    {
        return $this->original_post->post_name;
    }

    public function getMenuOrder()
    {
        return $this->original_post->menu_order;
    }

    public function getPostType()
    {
        return $this->original_post->post_type;
    }

    public function addTerm(\PComm\WPUtils\Term\TermInterface $term)
    {
        $this->terms[$term->getTermID()] = $term;
    }

    /**
     * @param $string
     * @return \PComm\WPUtils\Term\TermInterface[]
     */
    public function getTermsByTaxonomy($string)
    {
        $taxonomyTerms = [];
        foreach($this->terms as $term) {
            if($term->getTermTaxonomy() == $string) {
                $taxonomyTerms[] = $term;
            }
        }

        return $taxonomyTerms;
    }

    /**
     * @param $field
     * @return array|null\
     */
    public function getCustom($field) {
        return get_post_custom_values($field, $this->original_post->ID);
    }

    /**
     * @return bool
     */
    public function getPostThumbnail()
    {
        $thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id($this->original_post->ID), 'full' );
        return (!empty($thumbnail[0])) ? $thumbnail[0] : FALSE;
    }
}