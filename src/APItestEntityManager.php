<?php

/**
 * @file
 * Contains \Drupal\APItest\APItestEntityManager
 */

namespace Drupal\APItest;

use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\Entity\EntityInterface;

/**
 * Helper class for the APItest module.
 */
class APItestEntityManager implements APItestEntityManagerInterface {

  /**
   * The entity manager service.
   *
   * @var \Drupal\Core\Entity\EntityManagerInterface
   */
  protected $entityManager;

  /**
   * The config factory service.
   *
   * @var \Drupal\Core\Config\ConfigFactory
   */
  protected $configFactory;

  /**
   * The entity definitions of entities that have APItest versions available.
   *
   * @var array
   */
  protected $compatibleEntities = array();

  /**
   * Constructs a new APItestEntityManager object.
   *
   * @param \Drupal\Core\Entity\EntityManagerInterface $entity_manager
   *  The entity manager service.
   * @param \Drupal\Core\Config\ConfigFactory $config_factory
   *  The configuration factory service.
   */
  public function __construct(EntityManagerInterface $entity_manager, ConfigFactory $config_factory) {
    $this->entityManager = $entity_manager;
    $this->configFactory = $config_factory;
  }

  /**
   * {@inheritdoc}
   */
  public function getAPItestEntities() {
    $compatible_entities = $this->getCompatibleEntities();
    //print_r($compatible_entities['node']);
    /*foreach($compatible_entities as $entity_type => $entity_definition){
      echo "first: ".$entity_type." second: ".$entity_definition."<br/>";
    }*/
    //echo "jola";
    //print_r($compatible_entities);
    //$entities = array();
    //$entity_type= $this->configFactory->get('APItest.settings')->get('APItest_entities');
    //foreach($this->configFactory->get('APItest.settings')->get('APItest_entities') as $entity_type) {
      //if (isset($compatible_entities[$entity_definition])) {
       // echo $entity_type;
       // $entities['node'] = $compatible_entities['node'];
      //}
    //}
    //return $entities;
    $entities = array();
    foreach($this->configFactory->get('APItest.settings')->get('APItest_entities') as $entity_type) {
      if (isset($compatible_entities[$entity_type])) {
        $entities[$entity_type] = $compatible_entities[$entity_type];
      }
    }
    return $entities;
  }

  /**
   * {@inheritdoc}
   */
  public function isAPItestEntity(EntityInterface $entity) {
    return array_key_exists($entity->getEntityType(), $this->getAPItestEntities());
  }

  /**
   * {@inheritdoc}
   */
  public function getCompatibleEntities() {
    // If the entities are yet to be populated, get the entity definitions from
    // the entity manager.
    if (empty($this->compatibleEntities)) {
      foreach($this->entityManager->getDefinitions() as $entity_type => $entity_definition) {
        // If this entity has a render controller, it has a APItest version.
        if ($entity_definition->hasHandlerClass('view_builder')) {
          $this->compatibleEntities[$entity_type] = $entity_definition;
        }
      }
    }
    return $this->compatibleEntities;
  }
}
