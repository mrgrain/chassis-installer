<?php
namespace Chassis\Installer;

use Composer\Installers\BaseInstaller;

/**
 * Installer for Chassis skins.
 * @package Chassis\Installer
 */
class ChassisInstaller extends BaseInstaller
{
    /**
     * @var array
     */
    protected $locations = array(
        'skin'    => 'wp-content/skins/{$name}/',
    );
}
