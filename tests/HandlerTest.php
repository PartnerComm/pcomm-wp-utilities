<?php
namespace PComm\WPUtils\Post;

$registeredPostTypes = [];

function register_post_type($type, $options) {
    global $registeredPostTypes;
    $registeredPostTypes[$type] = $options;
}
function add_action($when, $what) {
    call_user_func($what);
}

class HandlerTest extends \PHPUnit\Framework\TestCase {

    /**
     * @expectedException \Exception
     */
    public function testErrorOnBlankSlug() {
        $mockDefinition = $this->getMockBuilder('\PComm\WPUtils\Post\DefaultDefinition')
                            ->setMethods(['getSlug'])
                            ->getMock();

        $mockDefinition->method('getSlug')->willReturn('');

        $handler = new Handler();
        $handler->addDefinition($mockDefinition);
    }

    /**
     * @expectedException \Exception
     */
    public function testSlugLength() {
        $mockDefinition = $this->getMockBuilder('\PComm\WPUtils\Post\DefaultDefinition')
            ->setMethods(['getSlug'])
            ->getMock();

        $mockDefinition->method('getSlug')->willReturn('helloworldthisistoolongpleasefailme');

        $handler = new Handler();
        $handler->addDefinition($mockDefinition);
    }

    /**
     * @expectedException \Exception
     */
    public function testSlugIsAllLower() {
        $mockDefinition = $this->getMockBuilder('\PComm\WPUtils\Post\DefaultDefinition')
            ->setMethods(['getSlug'])
            ->getMock();

        $mockDefinition->method('getSlug')->willReturn('abc123ABC');

        $handler = new Handler();
        $handler->addDefinition($mockDefinition);
    }

    /**
     * @expectedException \Exception
     */
    public function testSlugNoSpaces() {
        $mockDefinition = $this->getMockBuilder('\PComm\WPUtils\Post\DefaultDefinition')
            ->setMethods(['getSlug'])
            ->getMock();

        $mockDefinition->method('getSlug')->willReturn('abc12 abv');

        $handler = new Handler();
        $handler->addDefinition($mockDefinition);
    }

    public function testAllGood() {
        $mockDefinition = $this->getMockBuilder('\PComm\WPUtils\Post\DefaultDefinition')
            ->setMethods(['getSlug'])
            ->getMock();

        $mockDefinition->method('getSlug')->willReturn('abc123');

        $mockDefinition2 = $this->getMockBuilder('\PComm\WPUtils\Post\DefaultDefinition')
            ->setMethods(['getSlug'])
            ->getMock();

        $mockDefinition2->method('getSlug')->willReturn('abc1234');

        $handler = new Handler();
        $handler->addDefinition($mockDefinition);
        $handler->addDefinition($mockDefinition2);
        $handler->run();

        global $registeredPostTypes;
        $this->assertTrue(!empty($registeredPostTypes['abc123']));
        $this->assertTrue(!empty($registeredPostTypes['abc1234']));
    }
}