<?php

namespace BiffBangPow\BugHerd\Extension;

use SilverStripe\Core\Config\Configurable;
use SilverStripe\Core\Extension;
use SilverStripe\Security\Security;
use SilverStripe\SiteConfig\SiteConfig;
use SilverStripe\View\Requirements;

/**
 * Class \BiffBangPow\BugHerd\Extension\ControllerExtension
 *
 * @property \SilverStripe\Control\Controller|\BiffBangPow\BugHerd\Extension\ControllerExtension $owner
 */
class ControllerExtension extends Extension
{
    use Configurable;
    /**
     * @config
     * @var string $bugherd_script
     * Full URI of the bugherd script
     */
    private static $bugherd_script = 'https://www.bugherd.com/sidebarv2.js';
    /**
     * @config
     * @var string $bugherd_parameter
     * Parameter name used to pass the project key
     */
    private static $bugherd_parameter = 'apikey';

    private $siteConfig;

    public function onAfterInit(): void
    {
        $this->siteConfig = SiteConfig::current_site_config();
        if (!$this->siteConfig->EnableBugHerd || !$this->siteConfig->BugHerdProjectKey) {
            return;
        }

        $showBH = false;

        if ($this->siteConfig->ShowBugHerdToGroups()->count() > 0) {
            $member = Security::getCurrentUser();
            if (!$member) {
                return;
            }
            $groupIDs = $this->siteConfig->ShowBugHerdToGroups()->column('ID');
            $memberIDs = $member->Groups()->column('ID');
            $commonGroups = array_intersect($groupIDs, $memberIDs);

            if (!empty($commonGroups)) {
                $showBH = true;
            }
        }
        else {
            $showBH = true;
        }

        if ($showBH) {
            Requirements::javascript($this->getBHScriptURL(), [
                'async' => true,
                'type' => false
            ]);
        }
    }

    private function getBHScriptURL() {
        $base = $this->config()->get('bugherd_script');
        $parameter = $this->config()->get('bugherd_parameter');
        $key = $this->siteConfig->BugHerdProjectKey;

        return sprintf('%s?%s=%s', $base, $parameter, $key);
    }
}