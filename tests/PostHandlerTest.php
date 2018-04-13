<?php
namespace PComm\WPUtils\Post;

$registeredPostTypes = [];
$registeredRestFields = [];
function register_post_type($type, $options) {
    global $registeredPostTypes;
    $registeredPostTypes[$type] = $options;
}
function register_rest_field($slug, $field, $signature) {
    global $registeredRestFields;
    if(empty($registeredRestFields[$slug])) {
        $registeredRestFields[$slug] = [];
    }
    if(empty($registeredRestFields[$slug][$field])) {
        $registeredRestFields[$slug][$field] = $signature;
    }    
}
function add_action($action, $place, $num, $num2) {
    #TODO : Add test for add action //thanks, chad.
}

class PostHandlerTest extends \PHPUnit\Framework\TestCase {

    public function testRegisterRestFields()
    {
        $mockDefinition = $this->getMockBuilder('\PComm\WPUtils\Post\DefaultDefinition')
            ->setMethods(['getSlug', 'getRestFields'])
            ->getMock();

        $mockDefinition->method('getSlug')->willReturn('foo');
        $mockDefinition->method('getRestFields')->willReturn([
            'field1' => ['get' => 'foo', 'update' => 'bar'],
            'field2' => ['get' => 'foo2', 'update' => 'bar2']
        ]);

        $handler = new Handler();
        $handler->addDefinition($mockDefinition);
        $handler->run();

        global $registeredRestFields;
        $this->assertTrue(!empty($registeredRestFields['foo']['field1']['get_callback']));
        $this->assertTrue(!empty($registeredRestFields['foo']['field2']['get_callback']));
    }

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