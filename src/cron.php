<?php

use Crontab\Crontab;
use Crontab\Job;

require_once 'utils.php';

class Cron{

	command = '';

	static $currentJob;
	static $crontab = new Crontab();

    function  __construct() {

    }

	public static function updateCron(int $interval,$readOnly = false){
		if(is_numeric($interval) && $interval > 0 ){
			if(!$readOnly){
				self::destroyCron();
			}
			self::$currentJob = Cron::buildCron($interval);
			if(!$readOnly){
				$crontab->addJob(self::$currentJob);
				$crontab->write();
				configuration('CRON_INTERVAL_MINUTES',$interval);
			}
		}
	}

	public static function destroyCron(){
		if( self::$currentJob ){
			$crontab->removeJob(self::$currentJob);
			$crontab->write();

			configuration('CRON_INTERVAL_MINUTES',null);

			self::$currentJob = null;
		}
	}

	public static function buildCron(int $minutes){

		$ss = $minutes * 60;

		$m = floor(($ss%3600)/60);
		$h = floor(($ss%86400)/3600);
		$d = floor(($ss%2592000)/86400);
		$M = floor($ss/2592000);

		$job = new Job();

		if( $m > 0 ){
			$job->setMinute($m);
		}

		if( $h > 0 ){
			$job->setHour($h);
		}

		if( $d > 0 ){
			$job->setDayOfMonth($d);
		}

		if( $M > 0 ){
			$job->setMonth($M);
		}

		$job->setCommand(self::command);

		return $job;
	}
}

$interval = configuration('CRON_INTERVAL_MINUTES');
Cron::updateCron((int)$interval,true);
