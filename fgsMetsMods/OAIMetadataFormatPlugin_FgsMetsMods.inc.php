<?php

/**
 *
 * Copyright (c) 2014-2020 Simon Fraser University
 * Copyright (c) 2003-2020 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class OAIMetadataFormatPlugin_FgsMetsMods
 * @ingroup oai_format_fgs_mets_mods
 * @see OAI
 *
 * @brief Mets format plugin.
 */
import('lib.pkp.classes.plugins.OAIMetadataFormatPlugin');
import('plugins.oaiMetadataFormats.fgsMetsMods.OAIMetadataFormat_FgsMetsMods');

class OAIMetadataFormatPlugin_FgsMetsMods extends OAIMetadataFormatPlugin {
    /**
	 * Get the name of this plugin. The name must be unique within
	 * its category.
	 * @return String name of plugin
	 */
	function getName() {
		return 'OAIMetadataFormatPlugin_FgsMetsMods';
	}

	/**
	 * @copydoc Plugin::getDisplayName()
	 */
	function getDisplayName() {
		return __('plugins.oaiMetadata.fgsMetsMods.displayName');
	}

	/**
	 * @copydoc Plugin::getDescription()
	 */
	function getDescription() {
		return __('plugins.oaiMetadata.fgsMetsMods.description');
	}

	function getFormatClass() {
		return 'OAIMetadataFormat_FgsMetsMods';
	}

	static function getMetadataPrefix() {
		return 'oai_fgs_mets_mods';
	}

	static function getSchema() {
		return 'http://www.kb.se/namespace/mets/kbse_mets_002.xsd';
	}

	static function getNamespace() {
		return 'http://www.kb.se/namespace';
	}
}
