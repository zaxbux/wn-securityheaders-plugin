<?php

namespace Zaxbux\SecurityHeaders\ReportWidgets;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Backend\Classes\ReportWidgetBase;
use Zaxbux\SecurityHeaders\Models\CSPLog;
use Exception;
use ApplicationException;

class CSPReports extends ReportWidgetBase {
	public function render() {

		try {
			$this->loadData();
		}
		catch (Exception $ex) {
			$this->vars['error'] = $ex->getMessage();
		}

		return $this->makePartial('widget');
	}

	public function defineProperties() {
		return [
			'title' => [
				'title'             => 'backend::lang.dashboard.widget_title_label',
				'default'           => e(trans('zaxbux.securityheaders::lang.report_widgets.csp_reports.title')),
				'type'              => 'string',
				'validationPattern' => '^.+$',
				'validationMessage' => 'backend::lang.dashboard.widget_title_error'
			],
			'days' => [
				'title'             => 'zaxbux.securityheaders::lang.report_widgets.csp_reports.days',
				'default'           => '30',
				'type'              => 'string',
				'validationPattern' => '^[0-9]+$'
			]
		];
	}

	protected function loadData() {
		$days = $this->property('days');

		if (!$days || !\is_numeric($days) || (int) $days < 1) {
			throw new ApplicationException('Invalid days value: '.$days);
		}

		$period = Carbon::now()->subDays((int) $days);

		$data = [
			'report_only' => self::loadLogs('report_only', $period),
			'enforce' => self::loadLogs('enforce', $period),
		];

		$reportOnlyPoints = self::loadPoints($data['report_only'], $period);
		$enforcePoints = self::loadPoints($data['enforce'], $period);


		$this->vars['points']['report_only'] = $reportOnlyPoints['points'];
		$this->vars['points']['enforce']     = $enforcePoints['points'];

		$this->vars['count']['report_only']  = $reportOnlyPoints['count'];
		$this->vars['count']['enforce']      = $enforcePoints['count'];

		if ($reportOnlyPoints['count'] == 0 && $enforcePoints['count'] == 0) {
			throw new ApplicationException('No reports logged yet.');
		}
	}

	protected static function loadLogs($action, $startPeriod) {
		$query = CSPLog::where('action', $action);

		$query->where('created_at', '>=', $startPeriod);

		$logs = $query->groupBy('date')->get([
			\DB::raw('Date(created_at) as date'),
			\DB::raw('COUNT(*) as count'),
		])->keyBy('date');

		return $logs->toArray();
	}

	protected static function loadPoints($logs, $startPeriod) {
		$periodRange = CarbonPeriod::create($startPeriod, Carbon::now());

		$count = 0;
		$points = [];
		foreach ($periodRange as $day) {
			$key = $day->format('Y-m-d');

			if (\array_key_exists($key, $logs)) {
				$dailyReports = $logs[$key]['count'];
				$count += $dailyReports;
			} else {
				$dailyReports = 0;
			}

			$points[] = [
				strtotime($day)*1000, //TODO: Convert UTC to user timezone
				$dailyReports
			];
		}

		return ['count' => $count, 'points' => self::formatData($points)];
	}

	private static function formatData($points) {
		return str_replace('"', '', substr(substr(json_encode($points), 1), 0, -1));;
	}
}