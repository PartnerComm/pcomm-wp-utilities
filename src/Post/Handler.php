<?php
namespace PComm\WPUtils\Post;

class Handler {

    /**
     * @var \PComm\WPUtils\Post\DefinitionInterface[]
     */
    protected $definitions = [];

    public function addDefinition(DefinitionInterface $definition)
    {
        if( empty($definition->getSlug()) ||
            (strlen($definition->getSlug()) > 20) ||
            preg_match('/[A-Z]/', $definition->getSlug()) ||
            preg_match('/\\s/', $definition->getSlug())
        ) {
            throw new \Exception('Post must have slug and less than 20 chars');
        }

        $this->definitions[] = $definition;
    }

    /**
     * @return \PComm\WPUtils\Post\DefinitionInterface[]
     */
    public function getDefinitions() {
        return $this->definitions;
    }

    public function run()
    {
        foreach($this->definitions as $d) {
            $this->registerPostType($d);
        }
    }

    private function registerPostType(DefinitionInterface $d)
    {
        $options = [
            'public' => $d->getPublic(),
            'has_archive' => $d->getHasArchive(),
            'menu_icon' => $d->getMenuIcon(),
            'supports' => $d->getSupports(),
            'show_in_rest' => $d->getRestSupport(),
            'taxonomies' => $d->getTaxonomies(),
            'exclude_from_search' => $d->getExcludeFromSearch(),
            'capabilities' => $d->getCapabilities(),
            'labels' => [
                'singular_name' => ucwords($d->getSingleName()),
                'plural_name' => ucwords($d->getPluralName())
            ]
        ];

        add_action('init', function() use ($d, $options) {
            register_post_type($d->getSlug(), $options);
        });
    }

}