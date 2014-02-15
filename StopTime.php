<?php

/*
__PocketMine Plugin__
name=StopTime
description=
version=0.0.1
author=DreamWork Studio
class=StopTime
apiversion=12,13
*/

define("STOPTIME_VERSION", "0.0.1");
class StopTime implements Plugin{
	private $api, $times = array();
	public function __construct(ServerAPI $api, $server = false){
		$this->api = $api;
	}
	public function init(){
		$this->api->console->register("starttime", "<levelName>", array($this, "startTime"));
		$this->api->console->register("stoptime", "<levelName>", array($this, "stopTime"));
		$this->api->schedule(10, array($this, "checkTime"), array(), true);
	}
	public function __destruct(){}
	public function stopTime($cmd, $params, $issuer, $alias){
		$level = $this->api->level->get($params[0]);
		if($level === false){return "[StopTime] Level '".$params[0]."' not found!";}
		$this->times[$level->getName()] = $level->getTime();
		$this->checkTime();
		return "[StopTime] Stopped time of '".$level->getName()."'.";
	}
	public function startTime($cmd, $params, $issuer, $alias){
		$level = $this->api->level->get($params[0]);
		if($level === false){return "[StopTime] Level '".$params[0]."' not found!";}
		unset($this->times[$level->getName()]);
		$this->checkTime();
		return "[StopTime] Started time of '".$level->getName()."'.";
	}
	public function checkTime(){
		foreach($this->times as $levelName => $t){
			$level = $this->api->level->get($levelName);
			$level->setTime($t);
		}
	}
}
