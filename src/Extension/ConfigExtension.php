<?php

namespace BiffBangPow\BugHerd\Extension;

use SilverStripe\Core\Extension;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\ListboxField;
use SilverStripe\Forms\TextField;
use SilverStripe\Security\Group;

/**
 * Class \BiffBangPow\BugHerd\Extension\ConfigExtension
 *
 * @property \SilverStripe\SiteConfig\SiteConfig|\BiffBangPow\BugHerd\Extension\ConfigExtension $owner
 * @property string $BugHerdProjectKey
 * @property bool $EnableBugHerd
 * @method \SilverStripe\ORM\ManyManyList|\SilverStripe\Security\Group[] ShowBugHerdToGroups()
 */
class ConfigExtension extends Extension
{
    private static $db = [
        'BugHerdProjectKey' => 'Varchar',
        'EnableBugHerd' => 'Boolean'
    ];
    private static $many_many = [
        'ShowBugHerdToGroups' => Group::class
    ];

    public function updateCMSFields(FieldList $fields)
    {
        $fields->addFieldsToTab('Root.BugHerd', [
            CheckboxField::create('EnableBugHerd', 'Enable Bug Herd Integration'),
            TextField::create('BugHerdProjectKey', 'Project Key')
                ->setDescription('BugHerd integration project key (found in the project settings)'),
            ListBoxField::create('ShowToGroups', 'Show To Groups', Group::get())
                ->setDescription('Only show to logged-in users in the following groups (leave blank to show to everyone)')
        ]);
    }
}