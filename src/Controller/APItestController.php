<?php

/**
 * @file
 * Contains \Drupal\hardcopy\Controller\HardcopyController
 */

namespace Drupal\hardcopy\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\hardcopy\HardcopyFormatPluginManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Controller to display an entity in a particular hardcopy format.
 */
class HardcopyController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * The hardcopy format plugin manager.
   *
   * @var \Drupal\hardcopy\HardcopyFormatPluginManager
   */
  protected $hardcopyFormatManager;

  /**
   * Constructs a \Drupal\hardcopy\Controller\HardcopyController object.
   *
   * @param \Drupal\hardcopy\HardcopyFormatPluginManager
   *   The hardcopy format plugin manager.
   */
  public function __construct(HardcopyFormatPluginManager $hardcopy_format_manager) {
    $this->hardcopyFormatManager = $hardcopy_format_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('plugin.manager.hardcopyformat')
    );
  }

  /**
   * Returns the entity rendered via the given hardcopy format.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *  The entity to be printed.
   * @param string $hardcopy_format.
   *  The identifier of the hadcopy format plugin.
   *
   * @return \Symfony\Component\HttpFoundation\Response
   *  The hardcopy response.
   */
  public function showFormat(EntityInterface $entity, $hardcopy_format) {
    //if ($this->hardcopyFormatManager->getDefinition($hardcopy_format)) {
      $format = $this->hardcopyFormatManager->createInstance($hardcopy_format);
      $content = $this->entityManager()->getViewBuilder($entity->getEntityTypeId())->view($entity, 'hardcopy');
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

  public function demo(/*$entity1,$hardcopy_format*/) {
  return array(
      '#markup' => t('Hello World!'),
    );
 }
 public function demo1($entity) {
return array(
      '#markup' => t('fuck World!'),
    );
 }
}

