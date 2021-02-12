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

    function toXml($record, $format = null) {
        $request = Application::getRequest();
        $article = $record->getData('article');
        $journal = $record->getData('journal');
        $keywordDao = DAORegistry::getDAO('SubmissionKeywordDAO');
        $keywords = $keywordDao->getKeywords($article->getCurrentPublication()->getId(), array(AppLocale::getLocale()));
        $pdfGalley = null;
        $galleyProps = null;
        $galleys = $article->getGalleys();
        $pluginName = "OJS e-pliktsplugin för OAI-PMH";
        $pluginVersion = "1.0";
        $pluginUrl = "https://github.com/Kungbib/TBD";

        // Requires activated organization URI plugin
        $orgUri = $journal->getData('organisationUri');

        foreach ($galleys as $galley) {
        //Support html galleys as well?
            if($galley->getFileType() == 'application/pdf') {
                $galleyProps = Services::get('galley')->getSummaryProperties($galley,array(
                    'request' => $request));
                $pdfGalley = $galley;
            }
        }

        $templateMgr = TemplateManager::getManager();
        $templateMgr->assign(array(
            'journal' => $journal,
            'article' => $article,
            'issue' => $record->getData('issue'),
            'section' => $record->getData('section'),
            'keywords' => $keywords[$journal->getPrimaryLocale()],
            'galleyProps' => $galleyProps,
            'file' => $pdfGalley->getFile(),
            'pluginName' => $pluginName,
            'pluginVersion' => $pluginVersion,
            'pluginUrl' => $pluginUrl,
            'archivistUri' => $orgUri,
            'creatorUri' => $orgUri
        ));

        $templateMgr->assign(array(
            'abstract' => PKPString::html2text($article->getAbstract($article->getLocale())),
            'language' => AppLocale::get3LetterIsoFromLocale($article->getLocale())
        ));

        $plugin = PluginRegistry::getPlugin('oaiMetadataFormats', 'OAIMetadataFormatPlugin_MetsKb');
        return $templateMgr->fetch($plugin->getTemplateResource('record.tpl'));
    }
}
