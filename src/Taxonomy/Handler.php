<?php
namespace PComm\WPUtils\Taxonomy;

class Handler {

    /**
     * @var \PComm\WPUtils\Taxonomy\DefinitionInterface[]
     */
    private $definitions = [];

    public function addDefinition(DefinitionInterface $definition)
    {
        if( empty($definition->getSlug()) ||
            (strlen($definition->getSlug()) > 20) ||
            preg_match('/[A-Z]/', $definition->getSlug()) ||
            preg_match('/\\s/', $definition->getSlug())
        ) {
            throw new \Exception('Taxonomy must have slug and less than 20 chars');
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
            $this->registerTaxonomy($d);
        }
	}
	
	public function registerRestFields(DefinitionInterface $d)
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

    private function registerTaxonomy(DefinitionInterface $d)
    {
        $labels = array(
            'name'                       => $d->getSingleName(),
            'singular_name'              => $d->getSingleName(),
            'search_items'               => 'Search '.$d->getPluralName(),
            'popular_items'              => 'Popular '.$d->getPluralName(),
            'all_items'                  => 'All '.$d->getPluralName(),
            'edit_item'                  => 'Edit '.$d->getSingleName(),
            'update_item'                => 'Update '.$d->getSingleName(),
            'add_new_item'               => 'Add New '.$d->getSingleName(),
            'new_item_name'              => 'New '.$d->getSingleName().' Name',
            'menu_name'                  => $d->getPluralName()
        );

        $args = array(
            'hierarchical'          => $d->getIsHierarchical(),
            'labels'                => $labels,
            'show_ui'               => true,
            'show_admin_column'     => true,
            'update_count_callback' => '_update_post_term_count',
            'query_var'             => true,
			'show_in_rest'          => $d->getRestSupport(),
			'rewrite'				=> $d->getRewrite(),
        );

        register_taxonomy($d->getSlug(), $d->getPostTypes(), $args);
    }
}