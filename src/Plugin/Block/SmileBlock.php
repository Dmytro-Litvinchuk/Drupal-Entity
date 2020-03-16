<?php

namespace Drupal\smile_entity\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'SmileBlock' block.
 *
 * @Block(
 *  id = "smile_block",
 *  category = @Translation("Custom"),
 *  deriver = "Drupal\smile_entity\Plugin\Derivative\RoleSmileBlockDeriver",
 * )
 */
class SmileBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityManager;

  /**
   * SmileBlock constructor.
   *
   * @param array $configuration
   * @param $plugin_id
   * @param $plugin_definition
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityManager
   */
  public function __construct(array $configuration,
                              $plugin_id,
                              $plugin_definition,
                              EntityTypeManagerInterface $entityManager) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityManager = $entityManager;
  }

  /**
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   * @param array $configuration
   * @param string $plugin_id
   * @param mixed $plugin_definition
   *
   * @return \Drupal\Core\Plugin\ContainerFactoryPluginInterface|static
   */
  public static function create(ContainerInterface $container,
                                array $configuration,
                                $plugin_id,
                                $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager')
    );
  }

  /**
   * @inheritDoc
   */
  public function defaultConfiguration() {
    return [
      'count' => '10',
    ];
  }

  /**
   * @inheritDoc
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);
    $config = $this->getConfiguration();
    $form['count'] = [
      '#type' => 'number',
      '#min' => 1,
      '#max' => 10,
      '#title' => $this->t('How many times display a entities'),
      '#default_value' => $config['count'],
    ];
    return $form;
  }

  public function blockValidate($form, FormStateInterface $form_state) {
    $count = $form_state->getValue('count');
    if (!is_numeric($count) && $count < 1 && $count > 10) {
      $form_state->setErrorByName('count', $this->t('You must enter the number more than 0 and less or equal 10'));
    }
  }

  /**
   * @inheritDoc
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['count'] = $form_state->getValue('count');
  }

  /**
   * @inheritDoc
   */
  public function build() {
    // Get count(or something another) with config block form.
    $config = $this->getConfiguration();
    // Dynamic load block for user role.
/*    $current_user = \Drupal::currentUser();
    $roles = $current_user->getRoles();
    // If user have more than 1 role.
    if (count($roles) > 1) {
      $role = $roles[1];
    }
    else {
      $role = $roles[0];
    }*/
    // Dynamic role with plugin derivatives.
    $role = $this->getDerivativeId();
    $entity_type = 'smile';
    // Only entities for one role.
    $query = $this->entityManager->getStorage($entity_type)->getQuery()
      ->condition('role', $role);
    $query->sort('created', 'DESC');
    // Elements count.
    $query->range(0, $config['count']);
    $ids = $query->execute();
    if (isset($ids)) {
      $storage = $this->entityManager->getStorage($entity_type);
      $smile = $storage->loadMultiple($ids);
      $view_mode = 'rss';
      $view_builder = $this->entityManager->getViewBuilder($entity_type);
      $build = $view_builder->viewMultiple($smile, $view_mode);
      return $build;
    }
    else {
      return [
        '#markup' => $this->t('You did not create any smile entity'),
      ];
    }
  }

  /**
   * @inheritDoc
   */
  public function getCacheContexts() {
    return Cache::mergeContexts(parent::getCacheContexts(), ['user.roles']);
  }

  /**
   * @inheritDoc
   */
  public function getCacheMaxAge() {
    return 21600;
  }

}
