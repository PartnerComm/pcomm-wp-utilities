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
                'name' => ucwords($d->getPluralName()),
                'add_new_item' => 'Add New '.$d->getSingleName(),
                'new_item' => 'New '.$d->getSingleName(),
                'edit_item' => 'Edit '.$d->getSingleName(),
                'view_item' => 'View '.$d->getSingleName(),
                'view_items' => 'View '.$d->getPluralName(),
                'all_items' => 'All '.$d->getPluralName()


            ]
        ];

        register_post_type($d->getSlug(), $options);
    }

}