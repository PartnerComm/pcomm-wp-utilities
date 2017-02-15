<?php
namespace PComm\WPUtils\Post;

class PostTest extends \PHPUnit\Framework\TestCase {

    public function testWPPostLoads()
    {
        $mockWP = $this->getMockWPPost();
        $post = new Post($mockWP);

        $this->assertEquals(999, $post->getPostID());
        $this->assertEquals('test title', $post->getPostTitle());
        $this->assertEquals('test excerpt', $post->getPostExcerpt());
        $this->assertEquals(0, $post->getMenuOrder());
        $this->assertEquals('test', $post->getPostType());
    }



    private function getMockWPPost()
    {
        $mockWP = $this->getMockBuilder('\WP_Post')
            ->getMock();
        $mockWP->ID = 999;
        $mockWP->post_title = 'test title';
        $mockWP->post_excerpt = 'test excerpt';
        $mockWP->post_content = 'test content';
        $mockWP->menu_order = 0;
        $mockWP->post_type = 'test';
        return $mockWP;
    }

}