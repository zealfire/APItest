<?php

/**
 * @file
 * Contains \Drupal\APItest\Form\APItestConfigurationForm
 */

namespace Drupal\APItest\Form;

use Drupal\APItest\APItestEntityManagerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides shared configuration form for all APItest formats.
 */
class APItestConfigurationForm extends FormBase {

  /**
   * The APItest entity manager.
   *
   * @var \Drupal\APItest\APItestEntityManagerInterface
   */
  protected $APItestEntityManager;

  /**
   * Constructs a new form object.
   *
   * @param \Drupal\APItest\APItestEntityManagerInterface $APItest_entity_manager
   *   The APItest entity manager.
   */
  public function __construct(APItestEntityManagerInterface $APItest_entity_manager) {
    $this->APItestEntityManager = $APItest_entity_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('APItest.entity_manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormID() {
    return 'APItest_configuration';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $APItest_format = NULL) {

    // Allow users to choose what entities APItest is enabled for.
    $form['APItest_entities'] = array(
      '#type' => 'checkboxes',
      '#title' => $this->t('APItest Enabled Entities'),
      '#description' => $this->t('Select the entities that APItest support should be enabled for.'),
      '#options' => array(),
      '#default_value' => array(),
    );
    // Build the options array.
    foreach($this->APItestEntityManager->getCompatibleEntities() as $entity_type => $entity_definition) {
      $form['APItest_entities']['#options'][$entity_type] = $entity_definition->getLabel();
    }
    // Build the default values array.
    foreach($this->APItestEntityManager->getAPItestEntities() as $entity_type => $entity_definition) {
      $form['APItest_entities']['#default_value'][] = $entity_type;
    }

    // Provide option to open APItest page in a new tab/window.
    $form['open_target_blank'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Open in New Tab'),
      '#description' => $this->t('Open the APItest version in a new tab/window.'),
      '#default_value' => $this->config('APItest.settings')->get('open_target_blank'),
    );

    // Allow users to include CSS from the current theme.
    $form['css_include'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('CSS Include'),
      '#description' => $this->t('Specify an additional CSS file to include. Relative to the root of the Drupal install. The token <em>[theme:theme_machine_name]</em> is available.'),
      '#default_value' => $this->config('APItest.settings')->get('css_include'),
    );

    // Provide option to turn off link extraction.
    $form['extract_links'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Extract Links'),
      '#description' => $this->t('Extract any links in the content, e.g. "Some Link (http://drupal.org)'),
      '#default_value' => $this->config('APItest.settings')->get('extract_links'),
    );

    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => 'Submit',
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    \Drupal::configFactory()->getEditable('APItest.settings')->set('APItest_entities', $form_state->getValue('APItest_entities'))->save();
    \Drupal::configFactory()->getEditable('APItest.settings')->set('open_target_blank', $form_state->getValue('open_target_blank'))->save();
    \Drupal::configFactory()->getEditable('APItest.settings')->set('css_include', $form_state->getValue('css_include'))->save();
    \Drupal::configFactory()->getEditable('APItest.settings')->set('extract_links', $form_state->getValue('extract_links'))->save();
  }
}
