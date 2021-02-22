<?php

/**
 * @file OAIMetadataFormat_FgsMetsMods.inc.php
 *
 * Copyright (c) 2013-2020 Simon Fraser University
 * Copyright (c) 2003-2020 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class OAIMetadataFormat_FgsMetsMods
 * @ingroup oai_format
 * @see OAI
 *
 * @brief OAI metadata format class -- FgsMetsMods
 */

class OAIMetadataFormat_FgsMetsMods extends OAIMetadataFormat {

    function toXml($record, $format = null) {
        $request = Application::getRequest();
        $article = $record->getData('article');
        $journal = $record->getData('journal');
        $keywordDao = DAORegistry::getDAO('SubmissionKeywordDAO');
        $keywords = $keywordDao->getKeywords($article->getCurrentPublication()->getId(), array(AppLocale::getLocale()));
        $plugin = PluginRegistry::getPlugin('oaiMetadataFormats', 'OAIMetadataFormatPlugin_FgsMetsMods');
        $pdfGalley = null;
        $galleyProps = null;
        $galleys = $article->getGalleys();

        if ($journal->getData('organisationUri')) {
            $orgUri = $journal->getData('organisationUri');
        } else {
            $orgUri = "fixme";
        }

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
            'pluginName' => $plugin->getDisplayName(),
            'pluginVersion' => $plugin->getVersion(),
            'pluginUrl' => $plugin->getUrl(),
            'archivistUri' => $orgUri,
            'creatorUri' => $orgUri
        ));

        $templateMgr->assign(array(
            'abstract' => PKPString::html2text($article->getAbstract($article->getLocale())),
            'language' => AppLocale::get3LetterIsoFromLocale($article->getLocale())
        ));

        return $templateMgr->fetch($plugin->getTemplateResource('record.tpl'));
    }
}
