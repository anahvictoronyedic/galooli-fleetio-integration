<?php

use Crontab\Crontab;
use Crontab\Job;

require_once 'utils.php';

class Cron{

	public $command;
	public $config_key;

	public $currentJob;
	public $crontab;

    public function  __construct($command,$config_key) {
    	$this->command = $command;
    	$this->config_key = $config_key;

    	$this->crontab = new Crontab();

		$interval = configuration($this->config_key);
		$this->updateCron((int)$interval,true);
    }

	public function updateCron(int $interval,$readOnly = false){
		if(is_numeric($interval) ){
			if(!$readOnly){
				$this->destroyCron();
			}

			if($readOnly || $interval > 0) $this->currentJob = $this->buildCron($interval);

			if(!$readOnly && $interval > 0){
				$this->crontab->addJob($this->currentJob);
				$this->crontab->write();
				configuration($this->config_key,$interval);
			}
		}
	}

	public function destroyCron(){
		if( $this->currentJob ){
			$this->crontab->removeJob($this->currentJob);
			$this->crontab->write();

			configuration($this->config_key,null);

			$this->currentJob = null;
		}
	}

	private function buildCron(int $minutes){

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

		$job->setCommand($this->command);

		return $job;
	}
}

