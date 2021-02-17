<?php

/**
 * @file plugins/generic/metsKb/index.php
 *
 * Copyright (c) 2014-2020 Simon Fraser University
 * Copyright (c) 2003-2020 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 */
require_once('OAIMetadataFormat_MetsKb.inc.php');
require_once('OAIMetadataFormatPlugin_MetsKb.inc.php');

return new OAIMetadataFormatPlugin_MetsKb();
