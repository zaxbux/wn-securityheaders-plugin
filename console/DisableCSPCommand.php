<?php

namespace Zaxbux\SecurityHeaders\Console;

use Cache;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Zaxbux\SecurityHeaders\Classes\HeaderBuilder;
use Zaxbux\SecurityHeaders\Models\CSPSettings;

class DisableCSPCommand extends Command {
	/**
	 * @var string The console command name.
	 */
	protected $name = 'securityheaders:disable_csp';

	/**
	 * @var string The console command description.
	 */
	protected $description = 'Disable the Content Security Policy.';

	/**
	 * Execute the console command.
	 * @return void
	 */
	public function handle() {
		// Clear cache
		Cache::forget(HeaderBuilder::CACHE_KEY_CONTENT_SECURITY_POLICY);

		// Disable
		CSPSettings::set('enabled', false);

		$this->output->writeln('CSP disabled.');
	}

}