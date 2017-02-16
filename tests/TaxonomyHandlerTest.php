<?php
namespace PComm\WPUtils\Taxonomy;

$registeredTaxonomies = [];

function register_taxonomy($type, $posts, $options) {
    global $registeredTaxonomies;
    $registeredTaxonomies[$type] = $options;
}

class TaxonomyHandlerTest extends \PHPUnit\Framework\TestCase {

    /**
     * @expectedException \Exception
     */
    public function testErrorOnBlankSlug() {
        $mockDefinition = $this->getMockBuilder('\PComm\WPUtils\Taxonomy\DefaultDefinition')
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
        $mockDefinition = $this->getMockBuilder('\PComm\WPUtils\Taxonomy\DefaultDefinition')
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
        $mockDefinition = $this->getMockBuilder('\PComm\WPUtils\Taxonomy\DefaultDefinition')
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
        $mockDefinition = $this->getMockBuilder('\PComm\WPUtils\Taxonomy\DefaultDefinition')
            ->setMethods(['getSlug'])
            ->getMock();

        $mockDefinition->method('getSlug')->willReturn('abc12 abv');

        $handler = new Handler();
        $handler->addDefinition($mockDefinition);
    }

    public function testAllGood() {
        $mockDefinition = $this->getMockBuilder('\PComm\WPUtils\Taxonomy\DefaultDefinition')
            ->setMethods(['getSlug', 'getPostTypes'])
            ->getMock();

        $mockDefinition->method('getSlug')->willReturn('abc123');
        $mockDefinition->method('getPostTypes')->willReturn(['post']);

        $mockDefinition2 = $this->getMockBuilder('\PComm\WPUtils\Taxonomy\DefaultDefinition')
            ->setMethods(['getSlug', 'getPostTypes'])
            ->getMock();

        $mockDefinition2->method('getSlug')->willReturn('abc1234');
        $mockDefinition2->method('getPostTypes')->willReturn(['post']);

        $handler = new Handler();
        $handler->addDefinition($mockDefinition);
        $handler->addDefinition($mockDefinition2);
        $handler->run();

        global $registeredTaxonomies;
        $this->assertTrue(!empty($registeredTaxonomies['abc123']));
        $this->assertTrue(!empty($registeredTaxonomies['abc1234']));
    }
}