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
		$request = Application::getRequest();
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

		$response = "
		<article 
			dtd-version=\"1.1d3\" 
			xmlns:xlink=\"http://www.w3.org/1999/xlink\" 
			xmlns:mml=\"http://www.w3.org/1998/Math/MathML\" 
			xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" 
			xmlns:ali=\"http://www.niso.org/schemas/ali/1.0\" 

		<front>
		<journal-meta>
			<journal-id journal-id-type=\"ojs\">" . htmlspecialchars($journal->getPath()) . "</journal-id>
			<journal-title-group>
			<journal-title xml:lang=\"" . substr($journal->getPrimaryLocale(), 0, 2) . "\">" . htmlspecialchars($journal->getName($journal->getPrimaryLocale())) . "</journal-title>\n";
		// Translated journal titles
		foreach ($journal->getName(null) as $locale => $title) {
			if ($locale == $journal->getPrimaryLocale()) continue;
			$response .= "\t\t\t<trans-title-group xml:lang=\"" . substr($locale, 0, 2) . "\"><trans-title>" . htmlspecialchars($title) . "</trans-title></trans-title-group>\n";
		}
		$response .= ($journal->getAcronym($journal->getPrimaryLocale())?"\t\t\t<abbrev-journal-title xml:lang=\"" . substr($journal->getPrimaryLocale(), 0, 2) . "\">" . htmlspecialchars($journal->getAcronym($journal->getPrimaryLocale())) . "</abbrev-journal-title>":'');		
		$response .= "\n\t\t\t</journal-title-group>\n";

		$response .=
			(!empty($onlineIssn)?"\t\t\t<issn pub-type=\"epub\">" . htmlspecialchars($onlineIssn) . "</issn>\n":'') .
			(!empty($printIssn)?"\t\t\t<issn pub-type=\"ppub\">" . htmlspecialchars($printIssn) . "</issn>\n":'') .
			($publisherInstitution != ''?"\t\t\t<publisher><publisher-name>" . htmlspecialchars($publisherInstitution) . "</publisher-name></publisher>\n":'') .
			"\t\t</journal-meta>\n" .
			"\t\t<article-meta>\n" .
			"\t\t\t<article-id pub-id-type=\"publisher-id\">" . $article->getId() . "</article-id>\n" . 
			(!empty($articleDoi)?"\t\t\t<article-id pub-id-type=\"doi\">" . htmlspecialchars($articleDoi) . "</article-id>\n":'') .
			"\t\t\t<article-categories><subj-group xml:lang=\"" . $journal->getPrimaryLocale() . "\" subj-group-type=\"heading\"><subject>" . htmlspecialchars($section->getLocalizedTitle()) . "</subject></subj-group></article-categories>\n" .
			"\t\t\t<title-group>\n" .
			"\t\t\t\t<article-title xml:lang=\"" . substr($articleLocale, 0, 2) . "\">" . htmlspecialchars(strip_tags($article->getTitle($articleLocale))) . "</article-title>\n";
		if (!empty($subtitle = $article->getSubtitle($articleLocale))) $response .= "\t\t\t\t<subtitle xml:lang=\"" . substr($articleLocale, 0, 2) . "\">" . htmlspecialchars($subtitle) . "</subtitle>\n";

		// Translated article titles
		foreach ($article->getTitle(null) as $locale => $title) {
			if ($locale == $articleLocale) continue;
			if ($title){
				$response .= "\t\t\t\t<trans-title-group xml:lang=\"" . substr($locale, 0, 2) . "\">\n";
				$response .= "\t\t\t\t\t<trans-title>" . htmlspecialchars(strip_tags($title)) . "</trans-title>\n";
				if (!empty($subtitle = $article->getSubtitle($locale))) $response .= "\t\t\t\t\t<trans-subtitle>" . htmlspecialchars($subtitle) . "</trans-subtitle>\n";
				$response .= "\t\t\t\t\t</trans-title-group>\n";
			}
		}
		$response .=
			"\t\t\t</title-group>\n" .
			"\t\t\t<contrib-group content-type=\"author\">\n";

		// Authors
		$affiliations = array();
		foreach ($article->getAuthors() as $author) {
			$affiliation = $author->getLocalizedAffiliation();
			$affiliationToken = array_search($affiliation, $affiliations);
			if ($affiliation && !$affiliationToken) {
				$affiliationToken = 'aff-' . (count($affiliations)+1);
				$affiliations[$affiliationToken] = $affiliation;
			}
			$response .=
				"\t\t\t\t<contrib " . ($author->getPrimaryContact()?'corresp="yes" ':'') . ">\n" .
				"\t\t\t\t\t<name name-style=\"western\">\n" .
				"\t\t\t\t\t\t<surname>" . htmlspecialchars(method_exists($author, 'getLastName')?$author->getLastName():$author->getLocalizedFamilyName()) . "</surname>\n" .
				"\t\t\t\t\t\t<given-names>" . htmlspecialchars(method_exists($author, 'getFirstName')?$author->getFirstName():$author->getLocalizedGivenName()) . (((method_exists($author, 'getMiddleName') && $s = $author->getMiddleName()) != '')?" $s":'') . "</given-names>\n" .
				"\t\t\t\t\t</name>\n" .
				($affiliationToken?"\t\t\t\t\t<xref ref-type=\"aff\" rid=\"$affiliationToken\" />\n":'') .
				($author->getOrcid()?"\t\t\t\t\t<contrib-id contrib-id-type=\"orcid\" authenticated=\"true\">" . htmlspecialchars($author->getOrcid()) . "</contrib-id>\n":'') .
				"\t\t\t\t</contrib>\n";
		}
		$response .= "\t\t\t</contrib-group>\n";
		foreach ($affiliations as $affiliationToken => $affiliation) {
			$response .= "\t\t\t<aff id=\"$affiliationToken\"><institution content-type=\"orgname\">" . htmlspecialchars($affiliation) . "</institution></aff>\n";
		}

		// Publication date
		if ($datePublished) $response .=
			"\t\t\t<pub-date date-type=\"pub\" publication-format=\"epub\">\n" .
			"\t\t\t\t<day>" . strftime('%d', $datePublished) . "</day>\n" .
			"\t\t\t\t<month>" . strftime('%m', $datePublished) . "</month>\n" .
			"\t\t\t\t<year>" . strftime('%Y', $datePublished) . "</year>\n" .
			"\t\t\t</pub-date>\n";

		// Issue details
		if ($issue->getVolume() && $issue->getShowVolume())
			$response .= "\t\t\t<volume>" . htmlspecialchars($issue->getVolume()) . "</volume>\n";
		if ($issue->getNumber() && $issue->getShowNumber())
			$response .= "\t\t\t<issue>" . htmlspecialchars($issue->getNumber()) . "</issue>\n";

		// Page info, if available and parseable.
//		$pageInfo = $this->_getPageInfo($article);
//		if ($pageInfo){
//			$response .=
//				"\t\t\t\t<fpage>" . $pageInfo['fpage'] . "</fpage>\n" .
//				"\t\t\t\t<lpage>" . $pageInfo['lpage'] . "</lpage>\n";
//		}


		// Copyright, license and other permissions
		AppLocale::requireComponents(LOCALE_COMPONENT_PKP_SUBMISSION);
		$copyrightYear = $article->getCopyrightYear();
		$copyrightHolder = $article->getLocalizedCopyrightHolder();
		$licenseUrl = $article->getLicenseURL();
		$ccBadge = Application::getCCLicenseBadge($licenseUrl);

		// landing page link
		$response .= "\t\t\t<self-uri xlink:href=\"" . htmlspecialchars($request->url($journal->getPath(), 'article', 'view', $article->getBestArticleId())) . "\" />\n";

		// full text links
		$galleys = $article->getGalleys();
		$primaryGalleys = array();
		if ($galleys) {
			$genreDao = DAORegistry::getDAO('GenreDAO');
			$primaryGenres = $genreDao->getPrimaryByContextId($journal->getId())->toArray();
			$primaryGenreIds = array_map(function($genre) {
				return $genre->getId();
			}, $primaryGenres);
			foreach ($galleys as $galley) {
				$remoteUrl = $galley->getRemoteURL();
				$file = $galley->getFile();
				if (!$remoteUrl && !$file) {
					continue;
				}
				if ($remoteUrl || in_array($file->getGenreId(), $primaryGenreIds)) {
					$response .= "\t\t\t<self-uri content-type=\"" . $galley->getFileType() . "\" xlink:href=\"" . htmlspecialchars($request->url($journal->getPath(), 'article', 'download', array($article->getBestArticleId(), $galley->getBestGalleyId()), null, null, true)) . "\" />\n";
				}
			}
		}

		return $response;
	}

}
