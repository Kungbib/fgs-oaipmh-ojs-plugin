<?php

use PKP\components\forms\FieldText;
use PKP\components\forms\FieldSelect;

/**
 * @file OrgUriPlugin.inc.php
 *
 * Copyright (c) 2021 National Library of Sweden
 * Distributed under the GNU GPL v3. For full terms see LICENSE in the plugin repository root.
 *
 * @class OrgUriPlugin
 * @ingroup generic_orgUri
 * @brief Organisation URI plugin.
 */

class OrgUriPlugin extends GenericPlugin {

    function register($category, $path, $mainContextId = null) {
        $success = parent::register($category, $path);
        if ($success && $this->getEnabled()) {
            HookRegistry::register('Schema::get::context', array($this, 'addToSchema'));
            HookRegistry::register('Form::config::before', array($this, 'addToForm'));
        }
        return $success;
    }

    public function addToSchema($hookName, $args) {
        $schema = $args[0];
        $schema->properties->organisationUri = (object) [
            'type' => 'string',
            'apiSummary' => true,
            'multilingual' => false,
            'validation' => ['nullable']
        ];

        $schema->properties->journalLibrisUri = (object) [
            'type' => 'string',
            'apiSummary' => true,
            'multilingual' => false,
            'validation' => ['nullable']
        ];

        $schema->properties->deliveryType = (object) [
            'type' => 'string',
            'apiSummary' => true,
            'multilingual' => false,
            'validation' => ['nullable']
        ];

        return false;
    }

    public function addtoForm($hookName, $form) {
        if (!defined('FORM_MASTHEAD') || $form->id !== FORM_MASTHEAD) {
            return;
        }

        $context = Application::get()->getRequest()->getContext();
        if (!$context) {
            return;
        }

        $form->addField(new FieldText('organisationUri', [
            'label' => __('plugins.generic.orgUri.organisationUri'),
            'groupId' => 'publishing',
            'value' => $context->getData('organisationUri'),
        ]));

        $form->addField(new FieldText('journalLibrisUri', [
            'label' => __('plugins.generic.orgUri.journalLibrisUri'),
            'groupId' => 'publishing',
            'value' => $context->getData('journalLibrisUri'),
        ]));

        $deliveryOptions = [
            [
                'value' => 'AGREEMENT',
                'label' => __('plugins.generic.orgUri.deliveryType.agreement')
            ],
            [
                'value' => 'DEPOSIT',
                'label' => __('plugins.generic.orgUri.deliveryType.deposit')
            ]
        ];

        $form->addField(new FieldSelect('deliveryType', [
            'label' => __('plugins.generic.orgUri.deliveryType'),
            'groupId' => 'publishing',
            'options' => $deliveryOptions,
            'default' => 'AGREEMENT',
            'value' => $context->getData('deliveryType'),
        ]));

        return false;
    }

	function getName() {
		return 'OrganisationUri';
	}

	function getDisplayName() {
		return __('plugins.generic.orgUri.displayName');
	}

	function getDescription() {
		return __('plugins.generic.orgUri.description');
	}

	function isSitePlugin() {
        return true;
    }

    // Can only be activated at site level.
    function getCanEnable() {
        return !((bool) Application::get()->getRequest()->getContext());
    }

    // Can only be deactivated at site level.
    function getCanDisable() {
        return !((bool) Application::get()->getRequest()->getContext());
    }
}
