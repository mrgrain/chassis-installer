<?php
namespace Chassis\Installer;

use Composer\Installers\Installer as InstallerPlugin;
use Composer\Package\PackageInterface;

/**
 * Class Plugin
 * @package Chassis\Installer
 */
class Installer extends InstallerPlugin
{
    /**
     * Package types to installer class map
     *
     * @var array
     */
    protected $supportedTypes = array(
        'chassis'       => 'Chassis\\Installer\\ChassisInstaller',
    );

    /**
     * {@inheritDoc}
     */
    public function getInstallPath(PackageInterface $package)
    {
        $type = $package->getType();
        $frameworkType = $this->findFrameworkType($type);
        if ($frameworkType === false) {
            throw new \InvalidArgumentException(
                'Sorry the package type of this package is not yet supported.'
            );
        }
        $class = $this->supportedTypes[$frameworkType];
        /** @var \Composer\Installers\BaseInstaller $installer */
        $installer = new $class($package, $this->composer, $this->io);
        return $installer->getInstallPath($package, $frameworkType);
    }

    /**
     * {@inheritDoc}
     */
    protected function findFrameworkType($type)
    {
        $frameworkType = false;

        krsort($this->supportedTypes);

        foreach ($this->supportedTypes as $key => $val) {
            if ($key === substr($type, 0, strlen($key))) {
                $frameworkType = substr($type, 0, strlen($key));
                break;
            }
        }

        return $frameworkType;
    }

    /**
     * {@inheritdoc}
     */
    protected function getLocationPattern($frameworkType)
    {
        $pattern = false;
        if (!empty($this->supportedTypes[$frameworkType])) {
            $frameworkClass = $this->supportedTypes[$frameworkType];
            /** @var \Composer\Installers\BaseInstaller $framework */
            $framework = new $frameworkClass(null, $this->composer, $this->io);
            $locations = array_keys($framework->getLocations());
            $pattern = $locations ? '(' . implode('|', $locations) . ')' : false;
        }
        return $pattern ? : '(\w+)';
    }
}
