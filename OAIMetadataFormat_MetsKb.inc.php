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
        $article = $record->getData('article');
        $journal = $record->getData('journal');

        $templateMgr = TemplateManager::getManager();
        $templateMgr->assign(array(
            'journal' => $journal,
            'article' => $article,
            'issue' => $record->getData('issue'),
            'section' => $record->getData('section')
        ));

        $subjects = array_merge_recursive(
            stripAssocArray((array)$article->getDiscipline(null)),
            stripAssocArray((array)$article->getSubject(null))
        );

        $templateMgr->assign(array(
            'subject' => isset($subjects[$journal->getPrimaryLocale()]) ? $subjects[$journal->getPrimaryLocale()] : '',
            'abstract' => PKPString::html2text($article->getAbstract($article->getLocale())),
            'language' => AppLocale::get3LetterIsoFromLocale($article->getLocale())
        ));

        $plugin = PluginRegistry::getPlugin('oaiMetadataFormats', 'OAIMetadataFormatPlugin_MetsKb');
        return $templateMgr->fetch($plugin->getTemplateResource('record.tpl'));
    }
}
