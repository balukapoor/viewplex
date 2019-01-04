<?php

use Illuminate\Foundation\Inspiring;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

Artisan::command('adminpanel', function () {
	for ($i=0; $i <15 ; $i++) { 
		if($i==7) {			
			echo 'just completing the build';
			echo '.........'.PHP_EOL;
			continue;
		}
		echo $i.PHP_EOL;		
	}
    $this->comment('building admin panel');
})->describe('Building the admin panel');
