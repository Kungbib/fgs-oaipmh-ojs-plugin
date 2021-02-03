{**
 * plugins/oaiMetadataFormats/kbmets/record.tpl
 *
 * Copyright (c) 2013-2020 Simon Fraser University
 * Copyright (c) 2003-2020 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * Mets metadata record for an article
 *}
<mets xmlns="http://www.loc.gov/METS/"
	  xmlns:xlink="http://www.w3.org/1999/xlink"
	  xmlns:mods="http://www.loc.gov/mods/v3"
	  xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
	  OBJID="$ett-unikt-id"
	  TYPE="SIP"
	  LABEL="$titel"
	  PROFILE="http://www.kb.se/namespace/mets/fgs/eARD_Paket_FGS-PUBL.xml">
	<metsHdr RECORDSTATUS="VERSION" CREATEDATE="2020-03-02T12:26:08.725+01:00">
		<agent ROLE="ARCHIVIST" TYPE="ORGANIZATION">
			<name>{$journal->getName($journal->getPrimaryLocale())|escape}</name>
			<note>URI:http://id.kb.se/organisations/xxxxxx</note>
		</agent>
		<agent ROLE="CREATOR" TYPE="ORGANIZATION">
			<name>{{$journal->getName($journal->getPrimaryLocale())|escape}}</name>
			<note>URI:http://id.kb.se/organisations/xxxxxx</note>
		</agent>
		<agent ROLE="ARCHIVIST" TYPE="OTHER" OTHERTYPE="SOFTWARE">
			<name>OJS e-pliktsplugin för OAI-PMH</name>
			<note>Version 1.0</note>
			<note>https://github.com/Kungbib/...</note>
		</agent>
		<altRecordID TYPE="DELIVERYTYPE">DEPOSIT</altRecordID>
		<altRecordID TYPE="DELIVERYSPECIFICATION">http://www.kb.se/namespace/digark/deliveryspecification/deposit/fgs-publ/mods/MODS_enligt_FGS-PUBL.pdf</altRecordID>
		<altRecordID TYPE="SUBMISSIONAGREEMENT">http://www.kb.se/namespace/digark/submissionagreement/oai-pmh/fgs-mods/</altRecordID>
	</metsHdr>
	<dmdSec ID="dmdSec1">
		<mdWrap MDTYPE="MODS">
			<xmlData>
				<mods:mods xmlns:mods="http://www.loc.gov/mods/v3" version="3.2"
						   xsi:schemaLocation="http://www.loc.gov/mods/v3 http://www.loc.gov/standards/mods/v3/mods-3-2.xsd">
					<mods:accessCondition type="restriction on access" xlink:href="http://purl.org/eprint/accessRights/OpenAccess" displayLabel="Access Status">Open Access</mods:accessCondition>
					<mods:accessCondition>gratis</mods:accessCondition>
					<mods:genre>journal article</mods:genre>
					<mods:typeOfResource valueURI="https://id.kb.se/term/rda/Text">text</mods:typeOfResource>
					<mods:typeOfResource valueURI="http://id.loc.gov/vocabulary/contentTypes/txt">text</mods:typeOfResource>

					{assign var=authors value=$article->getAuthors()}
					{foreach from=$authors item=author}
					<mods:name type="personal">
						<mods:namePart type="family">{$author->getFamilyName($journal->getPrimaryLocale())|escape}</mods:namePart>
						<mods:namePart type="given">{$author->getGivenName($journal->getPrimaryLocale())|escape}</mods:namePart>
						<mods:role>
							<mods:roleTerm type="code" authority="marcrelator">aut</mods:roleTerm>
						</mods:role>
						{assign var=affiliation value=$author->getAffiliation($journal->getPrimaryLocale())}
						{if $affiliation}<mods:affiliation>{$affiliation|escape}</mods:affiliation>{/if}
						{if $author->getData('orcid')}<mods:nameIdentifier type="orcid">{$author->getOrcid('orcid')|escape}</mods:nameIdentifier>{/if}
					</mods:name>
					{/foreach}
					<mods:titleInfo lang="{$language}">
						<mods:title>{$article->getTitle($journal->getPrimaryLocale())|escape}</mods:title>
						{assign var=subTitle value=$article->getSubTitle($journal->getPrimaryLocale())}
						{if $subTitle}<mods:subTitle>{$subTitle|escape}</mods:subTitle>{/if}
					</mods:titleInfo>
					<mods:language>
						<mods:languageTerm type="code" authority="iso639-2b">{$language}</mods:languageTerm>
					</mods:language>
					<mods:language objectPart="abstract">
						<mods:languageTerm type="code" authority="iso639-2b">{$language}</mods:languageTerm>
					</mods:language>
					<mods:originInfo>
						<mods:publisher>{$journal->getName($journal->getPrimaryLocale())|escape}</mods:publisher>
						{if $article->getDatePublished()}
							<mods:dateIssued encoding="w3cdtf">{$article->getDatePublished()|strftime|date_format:'c'|escape}</mods:dateIssued>
						{/if}
					</mods:originInfo>
{*					Separat spår med inscannade gamla artiklar? *}
					<mods:physicalDescription>
						<mods:digitalOrigin>digitized other analog</mods:digitalOrigin>
					</mods:physicalDescription>
					{if $article->getStoredPubId('doi')}
						<mods:identifier type="doi">doi:{$article->getStoredPubId('doi')|escape}</mods:identifier>
					{/if}
					{if $article->getStoredPubId('doi')}
						<mods:identifier type="uri">$artikel-uri</mods:identifier>
					{/if}

					<mods:relatedItem type="host">
						<mods:genre authority="marcgt">journal</mods:genre>
						<!-- var lagras denna i OJS? -->
{*						Typ den seriella resursen*}
						<mods:identifier type="uri">http://libris.kb.se/xxxxxxxxxxx</mods:identifier>
						{if $journal->getData('onlineIssn')}
							<mods:identifier type="issn">{$journal->getData('onlineIssn')|escape}</mods:identifier>
						{/if}
						{if $journal->getData('printIssn')}
							<mods:identifier type="issn">{$journal->getData('printIssn')|escape}</mods:identifier>
						{/if}
						<mods:part>
							<mods:detail type="issue"/>
							<mods:detail>
								<mods:title>{$issue->getLocalizedTitle()|escape}</mods:title>
							</mods:detail>
							<mods:date encoding="w3cdtf">{$issue->getDatePublished()|strftime|date_format:'c'|escape}</mods:date>
						</mods:part>
					</mods:relatedItem>

					<mods:relatedItem type="host">
						<mods:titleInfo>
							<mods:title lang="{$language}">{$journal->getName($journal->getPrimaryLocale())|escape}</mods:title>
						</mods:titleInfo>

						<mods:part>
							<mods:detail type="volume">
								<mods:number>{$issue->getVolume()|escape}</mods:number>
								<mods:caption>vol.</mods:caption>
							</mods:detail>
							<mods:detail type="number">
								<mods:number>{$issue->getNumber()|escape}</mods:number>
								<mods:caption>no.</mods:caption>
							</mods:detail>
							<mods:extent unit="page">
								<mods:start>{$article->getStartingPage()}</mods:start>
								<mods:end>{$article->getEndingPage()}</mods:end>
							</mods:extent>
							{if $issue->getDatePublished()}
								<mods:date encoding="w3cdtf">{$issue->getDatePublished()|strftime|date_format:'c'|escape}</mods:date>
							{/if}
						</mods:part>
						{if $journal->getData('printIssn')}
						<mods:identifier type="issn">{$journal->getData('printIssn')|escape}</mods:identifier>
						{/if}
{*						tidskrift, record in libris to link to*}
						<mods:identifier type="uri">http://libris.kb.se/xxxxxxxxxxx</mods:identifier>
					</mods:relatedItem>

					{foreach $keywords as $k}
							<mods:subject lang="eng">
								<mods:topic>{$k}</mods:topic>
							</mods:subject>
					{/foreach}

					{if $abstract}
					<mods:abstract lang="{$language}">
						{$abstract|escape}
					</mods:abstract>
					{/if}
{*					<!-- https://www.loc.gov/standards/mods/userguide/location.html#url -->*}
					<mods:location>
{*						<!-- hårdkoda dessa värden eller? -->*}
						<mods:url displayLabel="fulltext" note="free" access="raw object">
							{$galleyProps["urlPublished"]|escape}
						</mods:url>
					</mods:location>
				</mods:mods>
			</xmlData>
		</mdWrap>
	</dmdSec>
	<structMap TYPE="physical">
		<div TYPE="files">
			<div TYPE="publication">
				<fptr FILEID="{$file->getFileId()}"/>
			</div>
		</div>
	</structMap>
	<fileSec>
{*		Fler filer skulle ge fler file groups här enligt https://www.loc.gov/standards/mets/METSOverview.v2.html#filegrp
		t.ex. en grupp för varje version av en fil/galley
*}
		<fileGrp>
			<file ID="{$file->getFileId()}" MIMETYPE="{$file->getFileType()}"
				  USE="Acrobat PDF 1.6 - Portable Document Format;1.6;PRONOM:fmt/20"
				  SIZE="{$file->getFileSize()}"
				  CREATED="{$file->getDateModified()|strftime|date_format:'c'|escape}">
				<FLocat LOCTYPE="URL" xlink:type="simple" xlink:href="file:{$file->getClientFileName()}"/>
			</file>
		</fileGrp>
	</fileSec>
</mets>
