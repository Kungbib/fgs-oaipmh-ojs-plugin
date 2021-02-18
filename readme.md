
# 

About
-----
This repository contains two plugins for the open source publishing platform Open Journal Systems (OJS). [The first plugin](fgsMetsMods) adds a new metadata format that follows the [FGS-PUBL specification](http://www.kb.se/namespace/digark/deliveryspecification/deposit/fgs-publ/mods/MODS%5Fenligt%5FFGS-PUBL.pdf) used by the National Library of Sweden for harvesting metadata and downloading article galleys. The metadata is exposed through an OAI-PMH endpoint for each journal. [The second plugin](orgUri), which should be used in tandem with the first, adds some extra fields and options to to the journal publishing settings.

Installation
------------
* Download and extract the latest release from the [releases section](https://github.com/KungBib/fgs-oaipmh-ojs-plugin/releases).

* With reference to the OJS installation root, place the `orgUri` and `fgsMetsMods` folders under the `plugins/generic` and `plugins/oaiMetadataFormats` directories, respectively. 

* Set owner and permissions to conform to the rest of your OJS installation.

How to use the plugins
----------------------
Once installed, the OAI-PMH format plugin is automatically activated site-wide. The organisation URI plugin can be activated by navigating to the OJS plugin section at the journal level.

