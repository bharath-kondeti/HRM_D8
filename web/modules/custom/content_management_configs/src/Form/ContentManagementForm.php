<?php
/**
 * @file
 * Contains \Drupal\content_management_configs\Form\ContentManagementForm.
 */
namespace Drupal\content_management_configs\Form;


use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\file\FileUsage\FileUsageInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ContentManagementForm extends ConfigFormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'content_management_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form = parent::buildForm($form, $form_state);
    $config = $this->config('content_management_configs.settings');

    $form['home'] = [
      '#type' => 'fieldset',
      '#title' => 'Home page',
    ];

    $form['home']['home_banner_bg'] = [
      '#type' => 'managed_file',
      '#title' => 'Home page - Banner background',
      '#description' => $this->t('Upload an image file.'),
      '#default_value' => $config->get('content_management_configs.home_banner_bg'),
      '#upload_location' => 'public://',
    ];

    $form['home']['banner_subheading'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Home page - Banner sub heading'),
      '#default_value' => $config->get('content_management_configs.banner_subheading'),
      '#required' => TRUE,
    ];

    $form['home']['banner_title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Home page - Title on banner'),
      '#default_value' => $config->get('content_management_configs.banner_title'),
      '#required' => TRUE,
    ];

    $form['home']['banner_desc'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Home page - Description on banner'),
      '#default_value' => $config->get('content_management_configs.banner_desc'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('content_management_configs.settings');

    $config->set('content_management_configs.home_banner_bg', $form_state->getValue('home_banner_bg'));
    $config->set('content_management_configs.banner_subheading', $form_state->getValue('banner_subheading'));
    $config->set('content_management_configs.banner_title', $form_state->getValue('banner_title'));
    $config->set('content_management_configs.banner_desc', $form_state->getValue('banner_desc'));
    $config->save();    

    return parent::submitForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'content_management_configs.settings',
    ];
  }
}
