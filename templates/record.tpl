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
			<name>$tidskriftens-namn</name>
			<note>URI:http://id.kb.se/organisations/xxxxxx</note>
		</agent>

		{assign var=publisher value=$journal->getName($journal->getPrimaryLocale())}
		{if $journal->getData('publisherInstitution')}
			{assign var=publisher value=$journal->getData('publisherInstitution')}
		{/if}
		<agent ROLE="CREATOR" TYPE="ORGANIZATION">
			<name>{$publisher|escape}</name>
			<note>URI:http://id.kb.se/organisations/xxxxxx</note>
		</agent>
		<agent ROLE="ARCHIVIST" TYPE="OTHER" OTHERTYPE="SOFTWARE">
			<name>PLuginnamn...</name>
			<note>Version 2.76</note>
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

					<!-- "Det namn som resursen är känd under.Repeterbart för flera olika typer av titlar. Använd inte attributet @type för huvudtiteln. Först nämnda titel utan @type behandlas alltid som huvudtitel." -->
					<mods:titleInfo lang="eng">
						<mods:title>Translating Neoliberalism</mods:title>
						<mods:subTitle>The European Social Fund and the Governing of Unemployment and Social Exclusion in Malmö, Sweden</mods:subTitle>
					</mods:titleInfo>

					<!-- repeterbar, en för varje språk -->
					<!-- ISO-639-2B? ISO-639-2T? ISO-639-3? (t.ex olika samiska språk)-->
					<!-- https://www.loc.gov/standards/mods/userguide/language.html#examples -->
					<mods:language>
						<mods:languageTerm type="code" authority="iso639-2b">swe</mods:languageTerm>
					</mods:language>
					<mods:language objectPart="abstract">
						<mods:languageTerm type="code" authority="iso639-2b">eng</mods:languageTerm>
					</mods:language>

					<mods:originInfo>
						<!-- ej obligatoriskt -->
						<mods:publisher>$tidskriftens-namn</mods:publisher>
						<!-- obligatoriskt -->
						<mods:dated encoding="w3cdtf">2012-12-13</mods:dateIssued>
					</mods:originInfo>

					<!-- Hur får vi dit den  på scannade importerade artiklar? -->
					<!-- "Normalvärde är ”born digital”(default om inget anges). Obligatoriskt för digitaliserade dokument, då med något av de tre senare kontrollerade värdena nedan." -->
					<!-- "Kontrollerade värden: born digital, reformatted digital, digitized microfilm, digitized other analog" -->
					<mods:physicalDescription>
						<mods:digitalOrigin>digitized other analog</mods:digitalOrigin>
					</mods:physicalDescription>

					<!-- Minst en behövs -->
					<!-- "När annan lämpligidentifikator saknas, använd här nätadressen (R102) nedan, med mods:identifier[@type= "uri"]." -->
					<mods:identifier type="uri">$artikel-uri</mods:identifier>
					<!-- DOI! -->
					<mods:identifier type="doi">doi:10.1006/jmbi.1995.0238</mods:identifier>

					<!-- värdpublikation / tidskrift-->
					<mods:relatedItem type="host">
						<mods:genre authority="marcgt">journal</mods:genre>
						<!-- var lagras denna i OJS? -->
						<mods:identifier type="uri">http://libris.kb.se/xxxxxxxxxxx</mods:identifier>
						<mods:identifier type="issn">$issn</mods:identifier>
						<mods:part>
							<mods:detail type="issue"/>
							<mods:detail>
								<mods:title>"numrets" titel</mods:title>
							</mods:detail>
							<mods:date encoding="w3cdtf">...</mods:date>
						</mods:part>
					</mods:relatedItem>

					<!-- värdpublikation / tidskrift-->
					<!-- taget från https://www.loc.gov/standards/mods/v3/mods-userguide-examples.html#journal_article -->
					<mods:relatedItem type="host">
						<mods:titleInfo>
							<!-- https://github.com/ojsde/openAIRE/blob/d84ff938dcc5f078d72fec2f2292cf666ea51939/OAIMetadataFormat_OpenAIRE.inc.php#L62 -->
							<mods:title lang="xxx">tidskriftens namn</mods:title>
						</mods:titleInfo>
						<mods:titleInfo xml:lang="fr" type="translated">
							<mods:nonSort>L'</mods:nonSort>
							<mods:title>homme qui voulut être roi</mods:title>
						</mods:titleInfo>

						<mods:part>
							<!-- https://github.com/ojsde/openAIRE/blob/d84ff938dcc5f078d72fec2f2292cf666ea51939/OAIMetadataFormat_OpenAIRE.inc.php#L129 -->
							<mods:detail type="volume">
								<mods:number>3</mods:number>
								<mods:caption>vol.</mods:caption>
							</mods:detail>
							<mods:detail type="number">
								<mods:number>1</mods:number>
								<mods:caption>no.</mods:caption>
							</mods:detail>
							<mods:extent unit="page">
								<mods:start>53</mods:start>
								<mods:end>57</mods:end>
							</mods:extent>
							<mods:date encoding="w3cdtf">...</mods:date>
						</mods:part>
						<mods:identifier type="issn">1531-2542</mods:identifier>
						<mods:identifier type="uri">http://libris.kb.se/xxxxxxxxxxx</mods:identifier>
					</mods:relatedItem>

					<!-- Ämesord: https://www.loc.gov/standards/mods/userguide/subject.html -->
					<!-- Ämnesord från "ej kontrollerad lista" -->
					<mods:subject lang="eng">
						<mods:topic>The European Social Fund</mods:topic>
					</mods:subject>
					<!-- Ämnesord med kod för ämnesordssystem -->
					<mods:subject lang="eng" authority="hsv">
						<mods:topic>Social Sciences</mods:topic>
					</mods:subject>
					<mods:subject lang="swe" authority="hsv">
						<mods:topic>Samhällsvetenskap</mods:topic>
					</mods:subject>
					<!-- Länkat ämnesord (authority är optional/överflödig egentligen) -->
					<!-- Kan man skippa benämning också? eftersom vi eg vill ha länkad data -->
					<mods:subject>
						<mods:topic authority="lcsh" valueURI="http://id.loc.gov/authorities/subjects/sh85075538">Learning disabilities</mods:topic>
					</mods:subject>
					<mods:subject>
						<mods:topic valueURI="https://id.kb.se/term/sao/H%C3%A4star">Hästar</mods:topic>
					</mods:subject>

					<!-- Abstract -->
					<mods:abstract lang="eng">&lt;p&gt;This thesis is concerned with how the governing
						of unemployment and social exclusion is accomplished through labor market
						projects that are initiated, tailored...
					</mods:abstract>

					<!-- https://www.loc.gov/standards/mods/userguide/location.html#url -->
					<mods:location>
						<!-- hårdkoda dessa värden eller? -->
						<mods:url displayLabel="fulltext" note="free" access="raw object">
							http://mau.diva-portal.org/smash/get/diva2:1404314/FULLTEXT01
						</mods:url>
					</mods:location>

					<!-- de här är lite oklara -->
					<!-- https://www.loc.gov/standards/mods/userguide/recordinfo.html#recordcontentsource -->
					<mods:recordInfo>
						<mods:recordOrigin>Generated by the e-plikt plugin for OJS ...?</mods:recordOrigin>
						<mods:recordContentSource>$systemid/url?</mods:recordContentSource>
						<mods:recordCreationDate>2020-02-28</mods:recordCreationDate>
						<mods:recordChangeDate>2020-03-02</mods:recordChangeDate>
						<!-- "Contains the system control number assigned by the organization creating, using, or distributing the record." -->
						<mods:recordIdentifier>diva2:1404314</mods:recordIdentifier>
					</mods:recordInfo>
				</mods:mods>
			</xmlData>
		</mdWrap>
	</dmdSec>


</mets>
