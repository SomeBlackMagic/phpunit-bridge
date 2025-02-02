<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require __DIR__ . '/../src/BarCov.php';
require __DIR__ . '/../src/FooCov.php';

require __DIR__ . '/../../../../src/Legacy/CoverageListenerTrait.php';

if (class_exists('PHPUnit_Runner_Version') && version_compare(\PHPUnit_Runner_Version::id(), '6.0.0', '<')) {
    require_once __DIR__ . '/../../../../src/Legacy/CoverageListenerForV5.php';
} elseif (version_compare(\PHPUnit\Runner\Version::id(), '7.0.0', '<')) {
    require_once __DIR__ . '/../../../../src/Legacy/CoverageListenerForV6.php';
} else {
    require_once __DIR__ . '/../../../../src/Legacy/CoverageListenerForV7.php';
}

require __DIR__ . '/../../../../src/CoverageListener.php';
