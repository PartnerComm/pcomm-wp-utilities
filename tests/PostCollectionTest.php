<?php
namespace PComm\WPUtils\Post;

class PostCollectionTest extends \PHPUnit\Framework\TestCase {

    public function testCollection()
    {
        $basePosts = $this->getPosts(3);
        $collection = new PostCollection();
        foreach($basePosts as $bp) {
            $collection->addPost($bp);
        }

        $i = 0;
        foreach($collection as $p) {
            $this->assertEquals($p->getPostID(), $basePosts[$i]->getPostID());
            $i++;
        }
    }

    private function getPosts($howmany)
    {
        $howmany = (int) $howmany;
        $posts = [];
        for($i = 0; $i < $howmany; $i++) {
            $post = $this->getMockWPPost(rand(0,999));
            $posts[] = new Post($post);
        }

        return $posts;
    }

    private function getMockWPPost($id = 0)
    {
        $mockWP = $this->getMockBuilder('\WP_Post')
            ->getMock();
        $mockWP->ID = $id;
        $mockWP->post_title = 'test title';
        $mockWP->post_excerpt = 'test excerpt';
        $mockWP->post_content = 'test content';
        $mockWP->menu_order = 0;
        $mockWP->post_type = 'test';
        return $mockWP;
    }
}