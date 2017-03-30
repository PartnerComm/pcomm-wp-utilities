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
            //$this->registerMetaSave($d);
        }
    }

    public function initMetaBoxes()
    {
        foreach($this->definitions as $d) {
            $this->registerMetaBoxes($d);
        }
    }

    public function initMetaSave($post_id, $post, $update)
    {
        $this->saveMetaBoxes($post_id, $post, $update);
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

    private function registerMetaBoxes(DefinitionInterface $d)
    {
        $meta = $d->getMetaFields();

        foreach($meta as $box) {
            add_meta_box($box['slug'], $box['title'], [$this, 'getHTML'], $d->getSlug(), 'normal', 'high', [$box]);
        }
    }

    public function getHTML($post, $box)
    {
        $box = $box['args'][0];
        $prefix = $box['slug'];

        // Use nonce for verification
        echo '<input type="hidden" name="post_'.$box['slug'].'_meta_box_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';

        echo '<ul>';

        foreach ($box['fields'] as $field) {

            // get value of this field if it exists for this post
            $meta = get_post_meta($post->ID, $field['id'], true);
            $desc = '';
            if ($field['desc'] != '') {
                $desc = '<span style="font-style: italic; color: #999;">' . $field['desc'] . '</span>';
            }
            if ($field['inputtype'] == 'select') {
                echo '<li class="'. $prefix . 'repeatable"><label style="display:block; font-weight: bold; margin-top: 1em;" for="' . $field['id'] . '">' . $field['label'] . '</label>';
                echo '<select name="'.$field['id'].'" id="'.$field['id'].'">';
                foreach ($field['options'] as $option) {
                    echo '<option', $meta == $option['value'] ? ' selected="selected"' : '', ' value="'.$option['value'].'">'.$option['label'].'</option>';
                }
                echo '</select>' . $desc .'</li>';
            }
            else if ($field['inputtype'] == 'textarea') {
                echo '<li class="'. $prefix . 'repeatable"><label style="display:block; font-weight: bold; margin-top: 1em;" for="' . $field['id'] . '">' . $field['label'] . '</label>';
                echo '<textarea rows="3" cols="40" name="'.$field['id'].'" id="'.$field['id'].'">' . $meta . '</textarea>';
            }
            else if ($meta && is_array($meta)) {
                foreach($meta as $row) {
                    echo '<li class="'. $prefix . 'repeatable"><label style="display:block; font-weight: bold; margin-top: 1em;" for="' . $field['id'] . '">' . $field['label'] . '</label>';
                    echo '<input type="'.$field['inputtype'].'" name="'.$field['id'].'" id="'.$field['id'].'" value="' . $row . '" size="30" />' . $desc . '</li>';
                }
            }
            else {
                echo '<li class="'. $prefix . 'repeatable"><label style="display:block; font-weight: bold; margin-top: 1em;" for="' . $field['id'] . '">' . $field['label'] . '</label>';
                echo '<input type="'.$field['inputtype'].'" name="'.$field['id'].'" id="'.$field['id'].'" value="' . $meta . '" size="30" />' . $desc . '</li>';
            }

        }

        echo '<input type="hidden" name="boxes[]" value="'.base64_encode(serialize($box)).'" />';

        echo '</ul>';
    }

    public function registerMetaSave(DefinitionInterface $d)
    {
        add_action('save_post', [$this, 'saveMetaBoxes'], 10, 3);
    }

    public function saveMetaBoxes($post_id, $post, $update)
    {
        $boxes = $_POST['boxes'];

        foreach($boxes as $box) {
            $box = unserialize(base64_decode($box));
            // verify nonce
            if (!wp_verify_nonce($_POST['post_'.$box['slug'].'_meta_box_nonce'], basename(__FILE__)))
                return $post_id;
            // check autosave
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
                return $post_id;
            // check permissions
            if ('page' == $_POST['post_type']) {
                if (!current_user_can('edit_page', $post_id))
                    return $post_id;
            } elseif (!current_user_can('edit_post', $post_id)) {
                return $post_id;
            }

            // loop through fields and save the data
            foreach ($box['fields'] as $field) {

                $old = get_post_meta($post_id, $field['id'], true);
                if (isset($_POST[$field['id']])) {
                    $new = $_POST[$field['id']];
                    if ($new && $new != $old) {
                        update_post_meta($post_id, $field['id'], $new);
                    } elseif ('' == $_POST[$field['id']] && $old) {
                        delete_post_meta($post_id, $field['id'], $old);
                    }
                }
                else {
                    delete_post_meta($post_id, $field['id'], $old);
                }
            } // end foreach
        }

    }

}