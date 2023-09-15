# 
Om insticksmodulerna
--------------------
Det här kodförrådet innehåller två insticksmoduler till publiceringsplattformen Open Journal Systems (OJS). [Den ena insticksmodulen](fgsMetsMods) lägger till en metadatastruktur som följer den förvaltningsgemensamma specifikationen [FGS-PUBL](http://www.kb.se/namespace/digark/deliveryspecification/deposit/fgs-publ/) i OJS OAI-PMH-gränssnitt. Detta gränssnitt kan användas av Kungliga Biblioteket för att hösta artikelmetadata och långtidslagra artiklar. [Den andra insticksmodulen](orgUri), som ska användas tillsammans med den första, lägger till extra fält (organisations-URI, Libris-ID etc) och inställningar i tidskrifternas publiceringsgränssnitt.

About the plugins
-----------------
This repository contains two plugins for the open source publishing platform Open Journal Systems (OJS). [The first plugin](fgsMetsMods) adds a new metadata format that follows the [FGS-PUBL specification](http://www.kb.se/namespace/digark/deliveryspecification/deposit/fgs-publ/) used by the National Library of Sweden for harvesting metadata and downloading article galleys. The metadata is exposed through the OAI-PMH endpoint for each journal. [The second plugin](orgUri), which should be used in tandem with the first, adds some extra fields and options (organisation URI, Libris ID etc) to to the journal publishing settings.

Installation
------------
* Download the .tar file from the latest release in the [releases section](https://github.com/Kungbib/fgs-oaipmh-ojs-plugin/releases).

* Install via the OJS UI: Administration-> Settings-> Website-> Plugins-> Upload A New Plugin. If the plugin is already installed: upgrade to a new version by selecting the plugin in the plugin list and click 'Upgrade'.

How to use the plugins
----------------------
Once installed, the OAI-PMH format plugin is automatically activated site-wide. The organisation URI plugin can be activated by navigating to the OJS plugin section at site level: Administration-> Site Settings-> Plugins.

Requirements
------------
Open Journal Systems 3.3.0-3 or later

Creating a new release
----------------------
Bump versions in `fgsMetsMods/version.xml` and/or `orgUri/version.xml`.

For the fgsMetsMods plugin also update the `getVersion()` method in `OAIMetadataFormatPlugin_FgsMetsMods.inc.php`. 

In the root directory (fgs-oaipmh-ojs-plugin), create a tar file with the latest code:
```
tar czf fgsMetsMods.tar.gz --directory=$(pwd) fgsMetsMods/
```
Draft a new github-release, tag the new version (`v.<M>.<m>.<p>`) and attach relevant tar files. 

Local development
----------------------
Run the docker version of OJS by following the README at https://github.com/pkp/docker-ojs. Follow the instructions in the [installation section](https://github.com/Kungbib/fgs-oaipmh-ojs-plugin/tree/master#installation) to get the plugins up and running.
