# 
Om insticksmodulerna
--------------------
Det här kodförrådet innehåller två insticksmoduler till publiceringsplattformen Open Journal Systems (OJS). [Den ena insticksmodulen](fgsMetsMods) lägger till en metadatastruktur som följer den förvaltningsgemensamma specifikationen [FGS-PUBL](http://www.kb.se/namespace/digark/deliveryspecification/deposit/fgs-publ/) i OJS OAI-PMH-gränssnitt. Detta gränssnitt kan användas av Kungliga Biblioteket för att hösta artikelmetadata och långtidslagra artiklar. [Den andra insticksmodulen](orgUri), som ska användas tillsammans med den första, lägger till extra fält (organisations-URI, Libris-ID etc) och inställningar i tidskrifternas publiceringsgränssnitt.

About the plugins
-----------------
This repository contains two plugins for the open source publishing platform Open Journal Systems (OJS). [The first plugin](fgsMetsMods) adds a new metadata format that follows the [FGS-PUBL specification](http://www.kb.se/namespace/digark/deliveryspecification/deposit/fgs-publ/) used by the National Library of Sweden for harvesting metadata and downloading article galleys. The metadata is exposed through the OAI-PMH endpoint for each journal. [The second plugin](orgUri), which should be used in tandem with the first, adds some extra fields and options (organisation URI, Libris ID etc) to to the journal publishing settings.

Installation
------------
* Download and extract the latest release from the [releases section](https://github.com/Kungbib/fgs-oaipmh-ojs-plugin/releases).

* With reference to the OJS installation root, place the `orgUri` and `fgsMetsMods` folders under the `plugins/generic` and `plugins/oaiMetadataFormats` directories, respectively.

* Set owner and permissions for the plugin folders to conform to the rest of your OJS installation.

How to use the plugins
----------------------
Once installed, the OAI-PMH format plugin is automatically activated site-wide. The organisation URI plugin can be activated by navigating to the OJS plugin section at the journal level.

Requirements
------------
Open Journal Systems 3.3 or later

Creating a new release
----------------------
On the develop branch, bump versions in 
`plugins/oaiMetadataFormats/fgsMetsMods/version.xml` and/or `plugins/generic/orgUri/version.xml`

For the fgsMetsMods plugin also update the `getVersion()` method in `OAIMetadataFormatPlugin_FgsMetsMods.inc.php`. 

Merge the develop branch into master.

In the root directory (fgs-oaipmh-ojs-plugin), create a tar file with the latest code:
```
tar czf fgsMetsMods.tar.gz --directory=$(pwd) fgsMetsMods/
```
Draft a new github-release, tag the new version (`v.<M>.<m>.<p>`) and attach relevant tar files. 

