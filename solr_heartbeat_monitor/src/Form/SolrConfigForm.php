<?php

namespace Drupal\solr_heartbeat_monitor\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\NodeType;

class SolrConfigForm extends ConfigFormBase {

    public function getFormId() {
        return 'solr_heartbeat_monitor_config_form';
    }

    public function buildForm(array $form, FormStateInterface $form_state) {

        $config = $this->config('solr_heartbeat_monitor.settings');

        $form = parent::buildForm($form, $form_state);

        // Get a list of all content types.
        $content_types = NodeType::loadMultiple();

        $options = [];
        foreach ($content_types as $content_type) {
            $options[$content_type->id()] = $content_type->label();
        }

        $form['custom_types'] = [
            '#type' => 'checkboxes',
            '#title' => $this->t('Content Types'),
            '#description' => $this->t('Configure where the custom button should appear.'),
            '#options' => $options,
            '#default_value' => $config->get('custom_types', []),
        ];

        return $form;
    }

    public function submitForm(array &$form, FormStateInterface $form_state) {
        $config = $this->config('solr_heartbeat_monitor.settings');
        $config->set('custom_types', $form_state->getValue('custom_types')); 
        $config->save(); // save data in solr_heartbeat_monitor.settings

        parent::submitForm($form, $form_state);
    }

    protected function getEditableConfigNames() {
        return ['solr_heartbeat_monitor.settings'];
    }
}
