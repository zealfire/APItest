<?php

/**
 * @file
 * Contains \Drupal\APItest\Controller\APItestController
 */

namespace Drupal\APItest\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\APItest\APItestFormatPluginManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Controller to display an entity in a particular APItest format.
 */
class APItestController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * The APItest format plugin manager.
   *
   * @var \Drupal\APItest\APItestFormatPluginManager
   */
  protected $APItestFormatManager;

  /**
   * Constructs a \Drupal\APItest\Controller\APItestController object.
   *
   * @param \Drupal\APItest\APItestFormatPluginManager
   *   The APItest format plugin manager.
   */
  public function __construct(APItestFormatPluginManager $APItest_format_manager) {
    $this->APItestFormatManager = $APItest_format_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('plugin.manager.APItestformat')
    );
  }

  /**
   * Returns the entity rendered via the given APItest format.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *  The entity to be printed.
   * @param string $APItest_format.
   *  The identifier of the hadcopy format plugin.
   *
   * @return \Symfony\Component\HttpFoundation\Response
   *  The APItest response.
   */
  public function showFormat(EntityInterface $entity, $APItest_format) {
    //if ($this->APItestFormatManager->getDefinition($APItest_format)) {
      $format = $this->APItestFormatManager->createInstance($APItest_format);
      $content = $this->entityManager()->getViewBuilder($entity->getEntityTypeId())->view($entity, 'APItest');
      $format->setContent($content);
      return $format->getResponse();
    //}
    //else {
      //throw new NotFoundHttpException();
    //}
    //return array(
      //'#markup' => t('Hello World!'),
    //);
  }

}

