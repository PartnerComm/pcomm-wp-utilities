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
        return $this;
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
            $this->registerRestFields($d);
        }
    }

    private function registerRestFields(DefinitionInterface $d)
    {
        $fields = $d->getRestFields();
        foreach($fields as $field => $definition) {
            $this->registerRestField($d, $field, $definition);
        }
    }

    private function registerRestField(DefinitionInterface $d, $field, $definition)
    {
        $update = (isset($definition['update'])) ? [$d, $definition['update']] : null;
        $get = (isset($definition['get'])) ? [$d, $definition['get']] : null;

        register_rest_field( $d->getSlug(),
            $field,
            array(
                'get_callback'    => $get,
                'update_callback' => $update,
                'schema'          => null,
            )
        );
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