<?php

namespace AutoFeed;

use pocketmine\scheduler\Task;

class AutoFeedTask extends Task{
	
	public function __construct(Main $main){
		$this->main = $main;
	}
	
	public function onRun($currentTicks){
		foreach($this->getMain()->getServer()->getOnlinePlayers() as $player){
			if(isset($this->main->players[strtolower($player->getName())])){
			$player->setFood(20);
			}
		}
	}
	
	public function getMain()
	{
		return $this->main;
	}
}	