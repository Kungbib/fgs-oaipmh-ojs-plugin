<?php

use PKP\components\forms\FieldText;
use PKP\components\forms\FieldSelect;

/**
 *
 * Copyright (c) 2014-2020 Simon Fraser University
 * Copyright (c) 2003-2020 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
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


    /**
	 * Get the name of this plugin. The name must be unique within
	 * its category.
	 * @return String name of plugin
	 */
	function getName() {
		return 'OrganisationUri';
	}

	/**
	 * @copydoc Plugin::getDisplayName()
	 */
	function getDisplayName() {
		return __('plugins.generic.orgUri.displayName');
	}

	/**
	 * @copydoc Plugin::getDescription()
	 */
	function getDescription() {
		return __('plugins.generic.orgUri.description');
	}
}
