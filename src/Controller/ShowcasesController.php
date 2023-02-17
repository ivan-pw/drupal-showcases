<?php

namespace Drupal\showcases\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Returns responses for Showcases routes.
 */
class ShowcasesController extends ControllerBase
{

    /**
     * Builds the response.
     */
    public function build()
    {

        $build['content'] = [
            '#type' => 'item',
            '#markup' => $this->t('It works!'),
        ];

        return $build;
    }

    public function getList($params = '')
    {
        $query = \Drupal::entityQuery('node');
        if ($params == 'featured') {

            $query->condition('status', 1)
                ->addTag('sort_by_random')
                ->condition('type', 'showcase')
                ->condition('field_featured', true)
                ->range(0, 3);
        } else {
            $query->condition('status', 1)
                ->addTag('sort_by_random')
                ->condition('type', 'showcase')
                ->range(0, 3);
        }
        $nids = $query->execute();

        $nodes = \Drupal\node\Entity\Node::loadMultiple($nids);

        $items = [];

        foreach ($nodes as $key => $node) {

            array_push($items, [
                'nid' => $node->id(),
                'title' => $node->getTitle(),
                'name' => $node->get('field_name')->getString(),
                'featured_image' => $this->getImage($node, 'field_featured_image'),
                'linked_article' => $this->getLinkedArticle($node),
            ]);
        }

        $payload = [
            'items' => $items,
        ];

        return new JsonResponse($payload);
    }

    public function getSingle($params)
    {
        $query = \Drupal::entityQuery('node');
        $query->condition('status', 1)
            ->condition('type', 'showcase')
            ->condition('nid', $params);
        $nids = $query->execute();

        $nodes = \Drupal\node\Entity\Node::loadMultiple($nids);

        $items = [];

        foreach ($nodes as $key => $node) {

            array_push($items, [
                'nid' => $node->id(),
                'title' => $node->getTitle(),
                'name' => $node->get('field_name')->getString(),
                'featured_image' => $this->getImage($node, 'field_featured_image'),
                'logo_image' => $this->getImage($node, 'field_logo_image'),
                'linked_article' => $this->getLinkedArticle($node),
                'featured' => $node->get('field_featured')->getString(),
                'address' => $node->get('field_address')->getString(),
                'short_description' => $node->get('field_short_description')->getString(),
                'description' => $node->get('field_description')->getString(),
                'facebook_url' => $node->get('field_facebook_url')->getString(),
                'twitter_url' => $node->get('field_twitter_url')->getString(),

            ]);
        }

        $payload = [
            'items' => $items,
        ];

        return new JsonResponse($payload);
    }

    private function getImage($node, $type)
    {
        $$type = 0;
        if ($node->hasField($type)) {
            $$type = array_merge(
                $node->get($type)->getValue()[0],
                ['url' => file_create_url($node->get($type)->entity->getFileUri())]);
        }
        return $$type;
    }

    private function getLinkedArticle($node)
    {
        $linked_article = 0;
        $linked_article_title = '';
        if ($node->hasField('field_linked_article')) {
            $nid = $node->get('field_linked_article')->getString();
            $linked_node = \Drupal\node\Entity\Node::load($nid);
            $linked_article_title = $linked_node->getTitle();
            $linked_article = [
                'nid' => $nid,
                'linked_article_title' => $linked_article_title,
            ];
        }

        return $linked_article;
    }

}
