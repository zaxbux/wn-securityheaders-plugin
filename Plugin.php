<?php namespace Zaxbux\SecurityHeaders;

use Yaml;
use System\Classes\PluginBase;
use Zaxbux\SecurityHeaders\Classes\CSPDirectives;


class Plugin extends PluginBase {
    /**
     * @var bool Plugin requires elevated permissions.
     * Necessary to alter headers on combined assets (/combine)
     */
    public $elevated = true;

    public function boot() {
        /*
         * Middleware
         */
        $this->app['Illuminate\Contracts\Http\Kernel']->prependMiddleware(Classes\NonceGeneratorMiddleware::class);
        $this->app['Illuminate\Contracts\Http\Kernel']->pushMiddleware(Classes\SecurityHeaderMiddleware::class);

        /*
         * Form Fields
         */
        \Event::listen('backend.form.extendFields', function ($widget) {
			if (!$widget->getController() instanceof \System\Controllers\Settings) {
				return;
			}

			if (!$widget->model instanceof Models\Settings) {
				return;
            }
            
            // Avoid adding fields to the repeater type fields
            if ($widget->isNested != false) {
                return;
            }

            $widget->addSecondaryTabFields(CSPDirectives::getFormConfig());

            
            $config = Yaml::parseFile($widget->getController()->getConfigPath('$/zaxbux/securityheaders/models/settings/fields-csp.yaml'));
            $widget->addSecondaryTabFields($config);
        });
    }

    public function registerComponents() {
        return [
            Components\NonceProvider::class => Components\NonceProvider::SHORT_NAME,
        ];
    }

    public function registerPermissions() {
        return [
            'zaxbux.securityheaders.access_settings' => [
                'label' => 'zaxbux.securityheaders::lang.permissions.access_settings',
                'tab'   => 'zaxbux.securityheaders::lang.plugin.name',
                'order' => 200,
                'roles' => [
                    'developer'
                ]
            ],
        ];
    }

    public function registerSettings() {
        return [
            'settings' => [
                'label'       => 'zaxbux.securityheaders::lang.settings.label',
                'description' => 'zaxbux.securityheaders::lang.settings.description',
                'category'    => 'zaxbux.securityheaders::lang.settings.category',
                'icon'        => 'icon-shield',
                'class'       => Models\Settings::class,
                'order'       => 500,
                'keywords'    => 'security headers csp',
                'permissions' => [
                    'zaxbux.securityheaders.access_settings'
                ]
            ],
        ];
    }
}
