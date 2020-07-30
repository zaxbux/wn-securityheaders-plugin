<?php

namespace Zaxbux\SecurityHeaders\Console;

use Cache;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Zaxbux\SecurityHeaders\Classes\HeaderBuilder;
use Zaxbux\SecurityHeaders\Models\HSTSSettings;

class DisableHSTSCommand extends Command {
	/**
	 * @var string The console command name.
	 */
	protected $name = 'securityheaders:disable_hsts';

	/**
	 * @var string The console command description.
	 */
	protected $description = 'Disable HSTS.';

	/**
	 * Execute the console command.
	 * @return void
	 */
	public function handle() {
		// Clear cache
		Cache::forget(HeaderBuilder::CACHE_KEY_STRICT_TRANSPORT_SECURITY);

		// Disable
		HSTSSettings::set('enabled', false);

		$this->output->writeln('HSTS disabled.');
	}

}