{*
 * Copyright (c) 2021 National Library of Sweden
 * Distributed under the GNU GPL v3. For full terms see LICENSE in the plugin repository root.
 *
 * FGS-PUBL METS/MODS metadata record for an article.
 *}

<mets xmlns="http://www.loc.gov/METS/"
	  xmlns:xlink="http://www.w3.org/1999/xlink"
	  xmlns:mods="http://www.loc.gov/mods/v3"
	  xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
	  OBJID="doi:{$article->getStoredPubId('doi')|escape}"
	  TYPE="SIP"
	  LABEL="{$article->getTitle($article->getLocale())|escape}"
	  PROFILE="http://www.kb.se/namespace/mets/fgs/eARD_Paket_FGS-PUBL.xml">
	<metsHdr RECORDSTATUS="VERSION" CREATEDATE="2020-03-02T12:26:08.725+01:00">
		{assign var=publisher value=$journal->getData('publisherInstitution')}
		<agent ROLE="ARCHIVIST" TYPE="ORGANIZATION">
			{if $publisher}
			<name>{$publisher|escape}</name>
			{else}
			<name>{$journal->getName($journal->getPrimaryLocale())|escape}</name>
			{/if}
			{if archivistUri}
			<note>URI:{$archivistUri|escape}</note>
			{/if}
		</agent>
		<agent ROLE="CREATOR" TYPE="ORGANIZATION">
			{if $publisher}
			<name>{$publisher|escape}</name>
			{else}
			<name>{$journal->getName($journal->getPrimaryLocale())|escape}</name>
			{/if}
			{if creatorUri}
			<note>URI:{$creatorUri|escape}</note>
			{/if}
		</agent>
		<agent ROLE="ARCHIVIST" TYPE="OTHER" OTHERTYPE="SOFTWARE">
			<name>{$pluginName|escape}</name>
			<note>Version: {$pluginVersion}</note>
			<note>{$pluginUrl|escape}</note>
		</agent>
		<altRecordID TYPE="DELIVERYTYPE">{$journal->getData('deliveryType')|escape}</altRecordID>
		<altRecordID TYPE="DELIVERYSPECIFICATION">http://www.kb.se/namespace/digark/deliveryspecification/deposit/fgs-publ/mods/MODS_enligt_FGS-PUBL.pdf</altRecordID>
		<altRecordID TYPE="SUBMISSIONAGREEMENT">http://www.kb.se/namespace/digark/submissionagreement/oai-pmh/fgs-mods/</altRecordID>
	</metsHdr>
	<dmdSec ID="dmdSec1">
		<mdWrap MDTYPE="MODS">
			<xmlData>
				<mods:mods xmlns="http://www.loc.gov/mods/v3" version="3.2"
						   xsi:schemaLocation="http://www.loc.gov/mods/v3 http://www.loc.gov/standards/mods/v3/mods-3-2.xsd">
					<mods:accessCondition type="restriction on access"
										  xlink:href="http://purl.org/eprint/accessRights/OpenAccess"
										  displayLabel="Access Status">Open Access
					</mods:accessCondition>
					<mods:accessCondition>gratis</mods:accessCondition>
					<mods:genre>journal article</mods:genre>
					<mods:typeOfResource>text</mods:typeOfResource>
					{assign var=authors value=$article->getAuthors()}
					{foreach from=$authors item=author}
					<mods:name type="personal">
						<mods:namePart type="family">{$author->getFamilyName($journal->getPrimaryLocale())|escape}</mods:namePart>
						<mods:namePart type="given">{$author->getGivenName($journal->getPrimaryLocale())|escape}</mods:namePart>
						<mods:role>
							<mods:roleTerm type="code" authority="marcrelator">aut</mods:roleTerm>
						</mods:role>
						{assign var=affiliation value=$author->getAffiliation($journal->getPrimaryLocale())}
						{if $affiliation}
						<mods:affiliation>{$affiliation|escape}</mods:affiliation>{/if}
						{if $author->getData('orcid')}
						<mods:nameIdentifier type="orcid">{$author->getOrcid('orcid')|escape}</mods:nameIdentifier>
						{/if}
					</mods:name>
					{/foreach}
					<mods:titleInfo lang="{$articleLanguage|escape}">
						<mods:title>{$article->getTitle($article->getLocale())|escape}</mods:title>
						{assign var=subTitle value=$article->getSubTitle($article->getLocale())}
						{if $subTitle}
						<mods:subTitle>{$subTitle|escape}</mods:subTitle>
						{/if}
					</mods:titleInfo>
					<mods:language>
						<mods:languageTerm type="code" authority="iso639-2b">{$articleLanguage|escape}</mods:languageTerm>
					</mods:language>
					<mods:language objectPart="abstract">
						<mods:languageTerm type="code" authority="iso639-2b">{$articleLanguage|escape}</mods:languageTerm>
					</mods:language>
					<mods:originInfo>
						<mods:publisher>{$publisher|escape}</mods:publisher>
						{if $article->getDatePublished()}
						<mods:dateIssued encoding="w3cdtf">{$article->getDatePublished()|strftime|date_format:'c'|escape}</mods:dateIssued>
						{/if}
					</mods:originInfo>
					{if $article->getStoredPubId('doi')}
					<mods:identifier type="doi">doi:{$article->getStoredPubId('doi')|escape}</mods:identifier>
					{/if}
					<mods:relatedItem type="host">
						<mods:titleInfo>
							<mods:title lang="{$journalPrimaryLanguage}">{$journal->getName($journal->getPrimaryLocale())|escape}</mods:title>
						</mods:titleInfo>
						<mods:part>
							<mods:detail type="issue">
								<mods:title>{$issue->getLocalizedTitle()|escape}</mods:title>
							</mods:detail>
							<mods:detail type="volume">
								<mods:number>{$issue->getVolume()|escape}</mods:number>
								<mods:caption>vol.</mods:caption>
							</mods:detail>
							<mods:detail type="number">
								<mods:number>{$issue->getNumber()|escape}</mods:number>
								<mods:caption>no.</mods:caption>
							</mods:detail>
							<mods:extent unit="page">
								<mods:start>{$article->getStartingPage()|escape}</mods:start>
								<mods:end>{$article->getEndingPage()|escape}</mods:end>
							</mods:extent>
							{if $issue->getDatePublished()}
							<mods:date encoding="w3cdtf">{$issue->getDatePublished()|strftime|date_format:'c'|escape}</mods:date>
							{/if}
						</mods:part>
						{if $journal->getData('onlineIssn')}
						<mods:identifier type="issn">{$journal->getData('onlineIssn')|escape}</mods:identifier>
						{elseif $journal->getData('printIssn')}
						<mods:identifier type="issn">{$journal->getData('printIssn')|escape}</mods:identifier>
						{/if}
						{if $journal->getData('journalLibrisUri')}
						<mods:identifier type="uri">{$journal->getData('journalLibrisUri')|escape}</mods:identifier>
						{/if}
					</mods:relatedItem>
					{foreach $keywords as $keyword}
					<mods:subject lang="{$keyword.lang}">
						<mods:topic>{$keyword.keyword|escape}</mods:topic>
					</mods:subject>
					{/foreach}
					{* Remove when Mimer accepts the valueUri attribute *}
					{foreach $linkedKeywords as $keyword}
					<mods:subject lang="{$keyword.lang}" authority="{$keyword.authority}">
						<mods:topic>{$keyword.label|escape}</mods:topic>
					</mods:subject>
					{/foreach}
					{* Activate when Mimer accepts the valueUri attribute!
					{foreach $linkedKeywords as $keyword}
						<mods:subject lang="{$keyword.lang}">
							<mods:topic valueUri="{$keyword.uri}">{$keyword.label}</mods:topic>
						</mods:subject>
					{/foreach}
					*}
					{if $abstract}
					<mods:abstract lang="{$articleLanguage|escape}">{$abstract|escape}</mods:abstract>
					{/if}
					<mods:location>
						<mods:url usage="primary">
							{$articleUrl|escape}
						</mods:url>
					</mods:location>
				</mods:mods>
			</xmlData>
		</mdWrap>
	</dmdSec>
	<fileSec>
		{foreach $fileGroups as $fileGroup}
			<fileGrp>
				{foreach $fileGroup as $fileInfo}
					{assign var=file value=$fileInfo.file}
					<file ID="{"FILE"|cat: $file->getId()|escape}" MIMETYPE="{$file->getdata('mimetype')|escape}"
							{if $file->getdata('mimetype') == "application/pdf"}
								USE="Portable document format (PDF)"
							{elseif $file->getdata('mimetype') == "text/xml" or $file->getdata('mimetype') == "application/xml"}
								USE="Extensible markup language (XML)"
							{else}
								USE="{$file->getdata('mimetype')|regex_replace:"#.*/#":""|upper|escape}"
							{/if}
						  SIZE="{$fileInfo.fileSize|escape}"
						  CREATED="{$file->getDateModified()|strftime|date_format:'c'|escape}">
						<FLocat LOCTYPE="URL" xlink:type="simple" xlink:href="{$fileInfo.url|escape}"/>
					</file>
				{/foreach}
			</fileGrp>
		{/foreach}
	</fileSec>
	<structMap TYPE="physical">
		<div TYPE="files">
			{foreach $fileGroups as $fileGroup}
				{foreach $fileGroup as $file}
					{if $file.type == 'main'}
						<div TYPE="publication">
							<fptr FILEID="{"FILE"|cat: $file.file->getId()|escape}"/>
						</div>
					{elseif $file.type == 'supplement'}
						<div TYPE="supplement">
							<fptr FILEID="{"FILE"|cat: $file.file->getId()|escape}"/>
						</div>
					{/if}
				{/foreach}
			{/foreach}
		</div>
	</structMap>
</mets>
