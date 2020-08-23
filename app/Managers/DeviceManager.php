<?php

declare(strict_types=1);

namespace WTG\Managers;

use DeviceDetector\DeviceDetector;
use Illuminate\Http\Request;

class DeviceManager
{
    protected Request $request;
    protected DeviceDetector $deviceDetector;

    /**
     * DeviceManager constructor.
     *
     * @param Request $request
     * @param DeviceDetector $deviceDetector
     */
    public function __construct(Request $request, DeviceDetector $deviceDetector)
    {
        $this->request = $request;

        $deviceDetector->setUserAgent($this->request->userAgent());
        $deviceDetector->parse();

        $this->deviceDetector = $deviceDetector;
    }

    /**
     * Get the client device name.
     *
     * @return string
     */
    public function getBrand(): string
    {
        return $this->deviceDetector->getBrandName();
    }

    /**
     * Get the client OS.
     *
     * @return string
     */
    public function getOS(): string
    {
        $os = $this->deviceDetector->getOs();

        if (! $os) {
            return DeviceDetector::UNKNOWN;
        }

        return sprintf('%s %s', $os['name'], $os['version']);
    }
}
