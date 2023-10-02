<?php

/**
 * @file OAIMetadataFormat_FgsMetsMods.inc.php
 *
 * Copyright (c) 2023 National Library of Sweden
 * Distributed under the GNU GPL v3. For full terms see LICENSE in the plugin repository root.
 *
 * @class OAIMetadataFormat_FgsMetsMods
 * @ingroup oai_format
 * @see OAI
 *
 * @brief OAI metadata format class -- FgsMetsMods
 */
import('lib.pkp.classes.file.PrivateFileManager');

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
        $fileGroups = [];
        $galleys = $article->getGalleys();
        $submissionFileService = Services::get('submissionFile');
        $articleUrl = $request->getDispatcher()->url($request, ROUTE_PAGE, null, 'article', 'view', $article->getId());
        $altObjectId = explode('kb.se/', $articleUrl)[1];

        $temporaryFileManager = new PrivateFileManager();
        $basePath = $temporaryFileManager->getBasePath();

        foreach ($galleys as $galley) {
            $fileGroup = [];
            if ($galley->getFileType() == 'application/pdf' || $galley->getFileType() == 'text/xml') {
                $fileUrl = $request->url(null, 'article', 'download', [$article->getBestArticleId(), $galley->getBestGalleyId(), $galley->getFile()->getId()]);
                $fileGroup[] =
                    [
                        'type' => 'main',
                        'file' => $galley->getFile(),
                        'url' => $fileUrl,
                        'fileSize' => filesize($basePath . "/" . $galley->getFile()->getData('path'))
                    ];
                
                //Files embedded in XML that have been added to the galley's 'dependent files' section in the UI
                $dependentFilesIterator = $submissionFileService->getMany([
                    'assocTypes' => [ASSOC_TYPE_SUBMISSION_FILE],
                    'assocIds' => [$galley->getFileId()],
                    'fileStages' => [SUBMISSION_FILE_DEPENDENT],
                    'includeDependentFiles' => true,
                ]);

                $dependentFiles = iterator_to_array($dependentFilesIterator);
                foreach ($dependentFiles as $dependentFile) {
                    $dependentFileUrl = $request->url(null, 'article', 'download', [$article->getBestArticleId(), $galley->getBestGalleyId(), $dependentFile->getId()]);
                    $fileGroup[] =
                        [
                            'type' => 'supplement',
                            'file' => $dependentFile,
                            'url' => $dependentFileUrl,
                            'fileSize' => filesize($basePath . "/" . $dependentFile->getData('path'))
                        ];
                }
                $fileGroups[] = $fileGroup;
            }
        }

        $linkedKeywords = [];
        $simpleKeywords = [];
        foreach ($keywords as $locale => $kws) {
            $lang = AppLocale::get3LetterIsoFromLocale($locale);
            foreach ($kws as $kw) {
                if (str_contains($kw, 'https://id.kb.se/term')) {
                    $prefLabelAndUri = explode('|', $kw);
                    $prefLabel = $prefLabelAndUri[0];
                    $uriDecoded = end($prefLabelAndUri);

                    $uriFragments = explode('/', $uriDecoded);
                    $label = end($uriFragments);
                    array_pop($uriFragments);
                    $authority = end($uriFragments);
                    array_push($uriFragments, rawurlencode($label));
                    $linkedKeywords[] =
                        [
                            'lang' => $lang,
                            'label' => $prefLabel,
                            'uri' => implode('/', $uriFragments),
                            'authority' => $authority
                        ];
                } else {
                    $simpleKeywords[] =
                        [
                            'lang' => $lang,
                            'keyword' => $kw
                        ];
                }
            }
        }

        $templateMgr = TemplateManager::getManager();
        $templateMgr->assign(array(
            'journal' => $journal,
            'article' => $article,
            'articleUrl' => $articleUrl,
            'altObjectId' => $altObjectId,
            'issue' => $record->getData('issue'),
            'section' => $record->getData('section'),
            'keywords' => $simpleKeywords,
            'linkedKeywords' => $linkedKeywords,
            'galleyProps' => $galleyProps,
            'fileGroups' => $fileGroups,
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
