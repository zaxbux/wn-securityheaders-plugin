<?php

namespace Zaxbux\SecurityHeaders\Controllers;

use Lang;
use Flash;
use BackendMenu;
use Backend\Classes\Controller;
use System\Classes\SettingsManager;
use Zaxbux\SecurityHeaders\Models\CSPLog;

class CSPLogs extends Controller {
	/**
	 * @var array Extensions implemented by this controller.
	 */
	public $implement = [
		\Backend\Behaviors\FormController::class,
		\Backend\Behaviors\ListController::class
	];
	
	/**
	 * @var array `FormController` configuration.
	 */
	public $formConfig = 'config_form.yaml';

	/**
	 * @var array `ListController` configuration.
	 */
	public $listConfig = 'config_list.yaml';

	/**
	 * @var array Permissions required to view this page.
	 */
	public $requiredPermissions = ['system.access_logs'];
	
	/**
	 * Constructor
	 * Set backend menu contexts.
	 */
	public function __construct() {
		parent::__construct();

		BackendMenu::setContext('October.System', 'system', 'settings');
		SettingsManager::setContext('Zaxbux.SecurityHeaders', 'csp_logs');
	}

	public function index_onRefresh() {
		return $this->listRefresh();
	}

	public function index_onEmptyLog() {
		CSPLog::truncate();
		Flash::success(Lang::get('system::lang.event_log.empty_success'));
		return $this->listRefresh();
	}

	public function index_onDelete() {
		if (($checkedIds = post('checked')) && is_array($checkedIds) && count($checkedIds)) {
			foreach ($checkedIds as $recordId) {
				if (!$record = CSPLog::find($recordId)) {
					continue;
				}
				$record->delete();
			}

			Flash::success(Lang::get('backend::lang.list.delete_selected_success'));
		}
		else {
			Flash::error(Lang::get('backend::lang.list.delete_selected_empty'));
		}

		return $this->listRefresh();
	}

	/**
	 * Preview page action
	 * @return void
	 */
	public function preview($id) {
		$this->addCss('/plugins/zaxbux/securityheaders/assets/css/preview.css');
		$this->addJs('/plugins/zaxbux/securityheaders/assets/js/reports-prettyprint.js');

		return $this->asExtension('FormController')->preview($id);
	}
}