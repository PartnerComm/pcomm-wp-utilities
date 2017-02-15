<?php
namespace PComm\WPUtils\Post;

class PostCollection implements \Countable, \Iterator {
    /**
     * @var int
     */
    protected $position = 0;

    /**
     * @var \PComm\WPUtils\Post\PostInterface[]
     */
    protected $posts = [];

    /**
     * @param \PComm\WPUtils\Post\PostInterface $post
     */
    public function addPost(\PComm\WPUtils\Post\PostInterface $post)
    {
        $this->posts[] = $post;
    }

    /**
     * @return \PComm\WPUtils\Post\PostInterface[]
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->posts);
    }

    /**
     * @return \PComm\WPUtils\Post\PostInterface
     */
    public function current()
    {
        return $this->posts[$this->position];
    }

    /**
     * @return void
     */
    public function next()
    {
        ++$this->position;
    }

    /**
     * @return int
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return isset($this->posts[$this->position]);
    }

    /**
     * @return void
     */
    public function rewind()
    {
        $this->position = 0;
    }
}