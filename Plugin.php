<?php namespace Zaxbux\SecurityHeaders;

use System\Classes\PluginBase;

use Validator;

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
         * Validation
         */
        Validator::extend('csp_source', Rules\CSPSource::class);
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
                    // role API codes
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
