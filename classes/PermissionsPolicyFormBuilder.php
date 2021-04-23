<?php namespace Zaxbux\SecurityHeaders\Classes;

class PermissionsPolicyFormBuilder {

	const LANG_PREFIX = 'zaxbux.securityheaders::lang.fields.permissionsPolicy.';

	/**
	 * @see https://github.com/w3c/webappsec-permissions-policy/blob/main/features.md
	 */
	const FEATURES = [
		'accelerometer',
		'ambient-light-sensor',
		'autoplay',
		'battery',
		'camera',
		'cross-origin-isolated',
		'display-capture',
		'document-domain',
		'encrypted-media',
		'execution-while-not-rendered',
		'execution-while-out-of-viewport',
		'fullscreen',
		'geolocation',
		'gyroscope',
		'magnetometer',
		'microphone',
		'midi',
		'navigation-override',
		'payment',
		'picture-in-picture',
		'publickey-credentials-get',
		'screen-wake-lock',
		'sync-xhr',
		'usb',
		'web-share',
		'xr-spatial-tracking',
		'clipboard-read',
		'clipboard-write',
		'interest-cohort', // FLoC
	];

	public function makeForm(\Backend\Widgets\Form $widget) {
		$this->addTabFields($widget);
	}

	private function addTabFields(\Backend\Widgets\Form $widget) {
		foreach (self::FEATURES as $feature) {
			$widget->addSecondaryTabFields([
				/*\str_replace('-', '_', $feature) . '_hint' => [
					'type'     => 'hint',
					'content'  => self::LANG_PREFIX . $feature . '.hint',
					'tab'      => $feature,
				],*/
				\str_replace('-', '_', $feature) . '[none]' => [
					'type'     => 'checkbox',
					'label'    => '()',
					'comment'  => 'Disable for all origins.',
					'tab'      => $feature,
					'span'     => 'storm',
					'cssClass' => 'col-md-4',
				],
				\str_replace('-', '_', $feature) . '[all]' => [
					'type'     => 'checkbox',
					'label'    => '*',
					'comment'  => 'Allow for all origins.',
					'tab'      => $feature,
					'span'     => 'storm',
					'cssClass' => 'col-md-4',
				],
				\str_replace('-', '_', $feature) . '[self]' => [
					'type'     => 'checkbox',
					'label'    => 'self',
					'comment'  => 'Allow for the origin specifying the policy.',
					'tab'      => $feature,
					'span'     => 'storm',
					'cssClass' => 'col-md-4',
				],
				\str_replace('-', '_', $feature) . '[origins]' => [
					'label' => 'Origins',
					'commentAbove' => 'Allow for specific origins.',
					'type' => 'repeater',
					'tab' => $feature,
					'prompt' => 'zaxbux.securityheaders::lang.fields.permissionsPolicy.origin.prompt',
					'nameFrom' => 'origin',
					'form' => [
						'fields'=> [
							'origin' => [
								'type' => 'text',
								'label' => 'zaxbux.securityheaders::lang.fields.permissionsPolicy.origin.name',
								'comment' => 'zaxbux.securityheaders::lang.fields.permissionsPolicy.origin.comment',
							],
						],
					],
				],
			]);
		}
	}
}
