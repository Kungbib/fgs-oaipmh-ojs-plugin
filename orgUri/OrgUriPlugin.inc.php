<?php

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

        $form->addField(new \PKP\components\forms\FieldText('organisationUri', [
            'label' => __('plugins.generic.orgUri.organisationUri'),
            'groupId' => 'publishing',
            'value' => $context->getData('organisationUri'),
        ]));

        $form->addField(new \PKP\components\forms\FieldText('journalLibrisUri', [
            'label' => __('plugins.generic.orgUri.journalLibrisUri'),
            'groupId' => 'publishing',
            'value' => $context->getData('journalLibrisUri'),
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
