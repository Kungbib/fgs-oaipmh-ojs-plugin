{*
 * Copyright (c) 2013-2020 Simon Fraser University
 * Copyright (c) 2003-2020 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
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
		<agent ROLE="ARCHIVIST" TYPE="ORGANIZATION">
			<name>{$journal->getName($journal->getPrimaryLocale())|escape}</name>
			<note>URI:{$archivistUri}</note>
		</agent>
		<agent ROLE="CREATOR" TYPE="ORGANIZATION">
			<name>{{$journal->getName($journal->getPrimaryLocale())|escape}}</name>
			<note>URI:{$creatorUri}</note>
		</agent>
		<agent ROLE="ARCHIVIST" TYPE="OTHER" OTHERTYPE="SOFTWARE">
			<name>{$pluginName}</name>
			<note>Version: {$pluginVersion}</note>
			<note>{$pluginUrl}</note>
		</agent>
		<altRecordID TYPE="DELIVERYTYPE">{$journal->getData('deliveryType')}</altRecordID>
		<altRecordID TYPE="DELIVERYSPECIFICATION">http://www.kb.se/namespace/digark/deliveryspecification/deposit/fgs-publ/mods/MODS_enligt_FGS-PUBL.pdf</altRecordID>
		<altRecordID TYPE="SUBMISSIONAGREEMENT">http://www.kb.se/namespace/digark/submissionagreement/oai-pmh/fgs-mods/</altRecordID>
	</metsHdr>
	<dmdSec ID="dmdSec1">
		<mdWrap MDTYPE="MODS">
			<xmlData>
				<mods:mods xmlns:mods="http://www.loc.gov/mods/v3" version="3.2"
						   xsi:schemaLocation="http://www.loc.gov/mods/v3 http://www.loc.gov/standards/mods/v3/mods-3-2.xsd">
					<mods:accessCondition type="restriction on access"
										  xlink:href="http://purl.org/eprint/accessRights/OpenAccess"
										  displayLabel="Access Status">Open Access
					</mods:accessCondition>
					<mods:accessCondition>gratis</mods:accessCondition>
					<mods:genre>journal article</mods:genre>
					<mods:typeOfResource valueURI="https://id.kb.se/term/rda/Text">text</mods:typeOfResource>
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
						<mods:publisher>{$journal->getName($journal->getPrimaryLocale())|escape}</mods:publisher>
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
								<mods:end>{$article->getEndingPage()}</mods:end>
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
						<mods:identifier type="uri">{$journal->getData('journalLibrisUri')}</mods:identifier>
						{/if}
					</mods:relatedItem>
					{foreach $keywords as $keyword}
					<mods:subject lang="{$articleLanguage}">
						<mods:topic>{$keyword}</mods:topic>
					</mods:subject>
					{/foreach}
					{if $abstract}
					<mods:abstract lang="{$articleLanguage}">{$abstract|escape}</mods:abstract>
					{/if}
					{foreach $galleyProps as $galleyProp}
					<mods:location>
						<mods:url displayLabel="fulltext" access="raw object">
							{$galleyProp["urlPublished"]|escape}
						</mods:url>
					</mods:location>
					{/foreach}
				</mods:mods>
			</xmlData>
		</mdWrap>
	</dmdSec>
	<structMap TYPE="physical">
		<div TYPE="files">
			<div TYPE="publication">
				{foreach $galleys as $galley}
				<fptr FILEID="{$galley->getFile()->getFileId()}"/>
				{/foreach}
			</div>
		</div>
	</structMap>
	<fileSec>
		<fileGrp>
			{foreach $galleys as $galley}
			{assign var=file value=$galley->getFile()}
			<file ID="{$file->getFileId()}" MIMETYPE="{$file->getFileType()}"
				{if $file->getFileType() == "application/pdf"}
					USE="Portable document format (PDF)"
				{elseif $file->getFileType() == "text/xml" or $file->getFileType() == "application/xml"}
					USE="Extensible Markup Language (XML)"
				{/if}
				  SIZE="{$file->getFileSize()}"
				  CREATED="{$file->getDateModified()|strftime|date_format:'c'|escape}">
				<FLocat LOCTYPE="URL" xlink:type="simple" xlink:href="file:{$file->getClientFileName()}"/>
			</file>
			{/foreach}
		</fileGrp>
	</fileSec>
</mets>
