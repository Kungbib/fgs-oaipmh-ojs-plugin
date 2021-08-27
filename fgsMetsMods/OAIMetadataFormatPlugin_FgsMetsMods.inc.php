<?php

/**
 * @file OAIMetadataFormatPlugin_FgsMetsMods.inc.php
 *
 * Copyright (c) 2021 National Library of Sweden
 * Distributed under the GNU GPL v3. For full terms see LICENSE in the plugin repository root.
 *
 * @class OAIMetadataFormatPlugin_FgsMetsMods
 * @ingroup oai_format_fgsMetsMods
 * @see OAI
 *
 * @brief FGS-PUBL METS/MODS format plugin.
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
		return 'http://www.kb.se/namespace/mets/fgs/eARD_Paket_FGS-PUBL_mets.xsd';
	}

	static function getNamespace() {
		return 'http://www.kb.se/namespace';
	}

	static function getVersion() {
        return '1.1.3';
    }

    static function getUrl() {
        return 'https://github.com/Kungbib/fgs-oaipmh-ojs-plugin';
    }
}
