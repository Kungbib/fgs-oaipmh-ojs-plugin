<?php

/**
 * @file OAIMetadataFormat_MetsKb.inc.php
 *
 * Copyright (c) 2013-2020 Simon Fraser University
 * Copyright (c) 2003-2020 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class OAIMetadataFormat_MetsKb
 * @ingroup oai_format
 * @see OAI
 *
 * @brief OAI metadata format class -- MetsKb
 */

class OAIMetadataFormat_MetsKb extends OAIMetadataFormat {

	/**
	 * @see OAIMetadataFormat#toXml
	 */
	function toXml($record, $format = null) {
		$article = $record->getData('article');
		$journal = $record->getData('journal');
		$section = $record->getData('section');
		$issue = $record->getData('issue');
		$galleys = $record->getData('galleys');
		$articleId = $article->getId();
		$publication = $article->getCurrentPublication();
		$abbreviation = $journal->getLocalizedSetting('abbreviation');
		$printIssn = $journal->getSetting('printIssn');
		$onlineIssn = $journal->getSetting('onlineIssn');
		$articleLocale = $article->getLocale();
		$publisherInstitution = $journal->getSetting('publisherInstitution');
		$datePublished = $article->getDatePublished();
		$articleDoi = $article->getStoredPubId('doi');
//		$accessRights = $this->_getAccessRights($journal, $issue, $article);
		$resourceType = ($section->getData('resourceType') ? $section->getData('resourceType') : 'http://purl.org/coar/resource_type/c_6501'); # COAR resource type URI, defaults to "journal article"
		if (!$datePublished) $datePublished = $issue->getDatePublished();
		if ($datePublished) $datePublished = strtotime($datePublished);

        $metsNamespace = "http://www.loc.gov/METS/";
        $mets = new SimpleXMLElement('<mets></mets>');
        $mets->addAttribute("xmlns","http://www.loc.gov/METS/");
        $mets->addAttribute("xmlns:xmlns:xlink","http://www.w3.org/1999/xlink");
        $mets->addAttribute("xmlns:xmlns:mods", "http://www.loc.gov/mods/v3");
        $mets->addAttribute("xmlns:xmlns:xsi", "http://www.w3.org/2001/XMLSchema-instance");
        $mets->addAttribute("OBJID","XXXXX");
        $mets->addAttribute("TYPE","SIP");
        $mets->addAttribute("LABEL","XXXXX");
        $mets->addAttribute("PROFILE","http://www.kb.se/namespace/mets/fgs/eARD_Paket_FGS-PUBL.xml");

        $metsHdr = $mets->addChild("metsHdr");
        $metsHdr->addAttribute("RECORDSTATUS","VERSION");
        $metsHdr->addAttribute("CREATEDATE", $article->getDatePublished());

        $agentArchivist = $metsHdr->addChild("agent");
        $agentArchivist->addAttribute("ROLE", "ARCHIVIST");
        $agentArchivist->addAttribute("TYPE", "ORGANIZATION");
        $agentArchivist->addChild("name","tidskriftens namn");
        $agentArchivist->addChild("note","URI:http://id.kb.se/organisations/XXXXX");

        $agentCreator = $metsHdr->addChild("agent");
        $agentCreator->addAttribute("ROLE", "CREATOR");
        $agentCreator->addAttribute("TYPE", "ORGANIZATION");
        $agentCreator->addChild("name","tidskriftens namn");
        $agentCreator->addChild("note","URI:http://id.kb.se/organisations/XXXXX");

        $agentSoftware = $metsHdr->addChild("agent");
        $agentSoftware->addAttribute("ROLE", "ARCHIVIST");
        $agentSoftware->addAttribute("TYPE", "OTHER");
        $agentSoftware->addAttribute("OTHERTYPE", "SOFTWARE");
        $agentSoftware->addChild("name","pluginnamn... osv");
        $agentSoftware->addChild("note","Version 2.76");
        $agentSoftware->addChild("note","https://github.com/Kungbib/...");

        $altRecordId = $metsHdr->addChild("altRecordID");

        return $this->formatXml($mets);
	}

    function formatXml($xmlElement) {
        $document = new DOMDocument('1.0');
        $document->preserveWhiteSpace = false;
        $document->formatOutput = true;
        $document->loadXML($xmlElement->asXML());

        return $document->saveXML();
    }
}
