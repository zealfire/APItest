<?php

/**
 * @file
 * Contains \Drupal\APItest\APItestEntityManagerInterface
 */

namespace Drupal\APItest;

use Drupal\Core\Entity\EntityInterface;

/**
 * Entity manager interface for the APItest module.
 */
interface APItestEntityManagerInterface {

  /**
   * Get the entities that APItest is available for.
   *
   * @return array
   *  An array of entity definitions keyed by the entity type.
   */
  public function getAPItestEntities();

  /**
   * Check if an entity has a APItest version available for it.
   *
   * @param EntityInterface $entity
   *  The entity to check a APItest version is available for.
   *
   * @return bool
   *  TRUE if the entity has a APItest version available, FALSE if not.
   */
  public function isAPItestEntity(EntityInterface $entity);

  /**
   * Get the entities that APItest can generate hardcopies for.
   *
   * @return array
   *  An array of entity definitions keyed by the entity type.
   */
  public function getCompatibleEntities();

}
