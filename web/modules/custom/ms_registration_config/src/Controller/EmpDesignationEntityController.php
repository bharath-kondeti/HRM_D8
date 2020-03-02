<?php

namespace Drupal\ms_registration_config\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\ms_registration_config\Entity\EmpDesignationEntityInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class EmpDesignationEntityController.
 *
 *  Returns responses for Employee Designation routes.
 */
class EmpDesignationEntityController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * The date formatter.
   *
   * @var \Drupal\Core\Datetime\DateFormatter
   */
  protected $dateFormatter;

  /**
   * The renderer.
   *
   * @var \Drupal\Core\Render\Renderer
   */
  protected $renderer;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->dateFormatter = $container->get('date.formatter');
    $instance->renderer = $container->get('renderer');
    return $instance;
  }

  /**
   * Displays a Employee Designation revision.
   *
   * @param int $emp_designation_entity_revision
   *   The Employee Designation revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($emp_designation_entity_revision) {
    $emp_designation_entity = $this->entityTypeManager()->getStorage('emp_designation_entity')
      ->loadRevision($emp_designation_entity_revision);
    $view_builder = $this->entityTypeManager()->getViewBuilder('emp_designation_entity');

    return $view_builder->view($emp_designation_entity);
  }

  /**
   * Page title callback for a Employee Designation revision.
   *
   * @param int $emp_designation_entity_revision
   *   The Employee Designation revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($emp_designation_entity_revision) {
    $emp_designation_entity = $this->entityTypeManager()->getStorage('emp_designation_entity')
      ->loadRevision($emp_designation_entity_revision);
    return $this->t('Revision of %title from %date', [
      '%title' => $emp_designation_entity->label(),
      '%date' => $this->dateFormatter->format($emp_designation_entity->getRevisionCreationTime()),
    ]);
  }

  /**
   * Generates an overview table of older revisions of a Employee Designation.
   *
   * @param \Drupal\ms_registration_config\Entity\EmpDesignationEntityInterface $emp_designation_entity
   *   A Employee Designation object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(EmpDesignationEntityInterface $emp_designation_entity) {
    $account = $this->currentUser();
    $emp_designation_entity_storage = $this->entityTypeManager()->getStorage('emp_designation_entity');

    $langcode = $emp_designation_entity->language()->getId();
    $langname = $emp_designation_entity->language()->getName();
    $languages = $emp_designation_entity->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $emp_designation_entity->label()]) : $this->t('Revisions for %title', ['%title' => $emp_designation_entity->label()]);

    $header = [$this->t('Revision'), $this->t('Operations')];
    $revert_permission = (($account->hasPermission("revert all employee designation revisions") || $account->hasPermission('administer employee designation entities')));
    $delete_permission = (($account->hasPermission("delete all employee designation revisions") || $account->hasPermission('administer employee designation entities')));

    $rows = [];

    $vids = $emp_designation_entity_storage->revisionIds($emp_designation_entity);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\ms_registration_config\EmpDesignationEntityInterface $revision */
      $revision = $emp_designation_entity_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = $this->dateFormatter->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $emp_designation_entity->getRevisionId()) {
          $link = $this->l($date, new Url('entity.emp_designation_entity.revision', [
            'emp_designation_entity' => $emp_designation_entity->id(),
            'emp_designation_entity_revision' => $vid,
          ]));
        }
        else {
          $link = $emp_designation_entity->link($date);
        }

        $row = [];
        $column = [
          'data' => [
            '#type' => 'inline_template',
            '#template' => '{% trans %}{{ date }} by {{ username }}{% endtrans %}{% if message %}<p class="revision-log">{{ message }}</p>{% endif %}',
            '#context' => [
              'date' => $link,
              'username' => $this->renderer->renderPlain($username),
              'message' => [
                '#markup' => $revision->getRevisionLogMessage(),
                '#allowed_tags' => Xss::getHtmlTagList(),
              ],
            ],
          ],
        ];
        $row[] = $column;

        if ($latest_revision) {
          $row[] = [
            'data' => [
              '#prefix' => '<em>',
              '#markup' => $this->t('Current revision'),
              '#suffix' => '</em>',
            ],
          ];
          foreach ($row as &$current) {
            $current['class'] = ['revision-current'];
          }
          $latest_revision = FALSE;
        }
        else {
          $links = [];
          if ($revert_permission) {
            $links['revert'] = [
              'title' => $this->t('Revert'),
              'url' => $has_translations ?
              Url::fromRoute('entity.emp_designation_entity.translation_revert', [
                'emp_designation_entity' => $emp_designation_entity->id(),
                'emp_designation_entity_revision' => $vid,
                'langcode' => $langcode,
              ]) :
              Url::fromRoute('entity.emp_designation_entity.revision_revert', [
                'emp_designation_entity' => $emp_designation_entity->id(),
                'emp_designation_entity_revision' => $vid,
              ]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.emp_designation_entity.revision_delete', [
                'emp_designation_entity' => $emp_designation_entity->id(),
                'emp_designation_entity_revision' => $vid,
              ]),
            ];
          }

          $row[] = [
            'data' => [
              '#type' => 'operations',
              '#links' => $links,
            ],
          ];
        }

        $rows[] = $row;
      }
    }

    $build['emp_designation_entity_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
