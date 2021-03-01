<?php

/**
 * @file OAIMetadataFormat_FgsMetsMods.inc.php
 *
 * Copyright (c) 2021 National Library of Sweden
 * Distributed under the GNU GPL v3. For full terms see LICENSE in the plugin repository root.
 *
 * @class OAIMetadataFormat_FgsMetsMods
 * @ingroup oai_format
 * @see OAI
 *
 * @brief OAI metadata format class -- FgsMetsMods
 */

class OAIMetadataFormat_FgsMetsMods extends OAIMetadataFormat {

    function toXml($record, $format = null) {
        $request = Application::get()->getRequest();
        $article = $record->getData('article');
        $journal = $record->getData('journal');
        $orgUri = $journal->getData('organisationUri');
        $keywordDao = DAORegistry::getDAO('SubmissionKeywordDAO');
        $keywords = $keywordDao->getKeywords($article->getCurrentPublication()->getId(), array($article->getLocale()));
        $plugin = PluginRegistry::getPlugin('oaiMetadataFormats', 'OAIMetadataFormatPlugin_FgsMetsMods');
        $galleyProps = [];
        $galleysToStore = [];
        $galleys = $article->getGalleys();

        foreach ($galleys as $galley) {
            if ($galley->getFileType() == 'application/pdf' || $galley->getFileType() == 'text/xml') {
                $galleyProps[] = Services::get('galley')->getSummaryProperties($galley, array('request' => $request));
                $galleysToStore[] = $galley;
            }
        }

        $templateMgr = TemplateManager::getManager();
        $templateMgr->assign(array(
            'journal' => $journal,
            'article' => $article,
            'issue' => $record->getData('issue'),
            'section' => $record->getData('section'),
            'keywords' => $keywords[$article->getLocale()],
            'galleyProps' => $galleyProps,
            'galleys' => $galleysToStore,
            'pluginName' => $plugin->getDisplayName(),
            'pluginVersion' => $plugin->getVersion(),
            'pluginUrl' => $plugin->getUrl(),
            'archivistUri' => $orgUri,
            'creatorUri' => $orgUri
        ));

        $templateMgr->assign(array(
            'abstract' => PKPString::html2text($article->getAbstract($article->getLocale())),
            'articleLanguage' => AppLocale::get3LetterIsoFromLocale($article->getLocale()),
            'journalPrimaryLanguage' => AppLocale::get3LetterIsoFromLocale($journal->getPrimaryLocale())
        ));

        return $templateMgr->fetch($plugin->getTemplateResource('record.tpl'));
    }
}
