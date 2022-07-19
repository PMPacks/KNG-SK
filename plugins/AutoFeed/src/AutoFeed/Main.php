<?php

namespace AutoFeed;

use pocketmine\Server;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\utils\TextFormat;
use pocketmine\block\Block;
use pocketmine\item\Item;
class Main extends PluginBase implements Listener{
	
	public $players = [];
	
	public function onEnable()
	{
		$this->getLogger()->info("AutoFeed code by ClickedTran is working");
		$this->getScheduler()->scheduleRepeatingTask(new AutoFeedTask($this), 1);
	}
	
	public function onCommand(CommandSender $sender, Command $command, string $label, array $params) : bool
	{
		if(!$sender instanceof Player){
			$sender->sendMessage("§cBạn chỉ có thể xài lệnh này trong game !");
			return false;
		}
		$username = strtolower($sender->getName());
		if($command->getName() == "autofeed"){
			if(isset($this->players[$username])){
				unset($this->players[$username]);
				$sender->sendMessage("§a§l[§e King§9Night§cVN§a ] §cBạn đã tắt chế độ tự động ăn");
				return true;
			}else{
				$this->players[$username] = true;
				$sender->sendMessage("§a§l[§e King§9Night§cVN§a ] §aBạn đã bật chế độ tự động ăn");
				return true;
			}
		}else{
			return false;
		}
	}
}