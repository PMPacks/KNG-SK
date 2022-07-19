<?php

namespace onebone\coinapi\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;
use pocketmine\Player;

use onebone\coinapi\CoinAPI;

class MyStatusCoinCommand extends Command{
	private $plugin;

	public function __construct(CoinAPI $plugin){
		$desc = $plugin->getCommandMessage("mystatuscoin");
		parent::__construct("mystatuscoin", $desc["description"], $desc["usage"]);

		$this->setPermission("coinapi.command.mystatuscoin");

		$this->plugin = $plugin;
	}

	public function execute(CommandSender $sender, string $label, array $params): bool{
		if(!$this->plugin->isEnabled()) return false;
		if(!$this->testPermission($sender)){
			return false;
		}

		if(!$sender instanceof Player){
			$sender->sendMessage(TextFormat::RED . "Please run this command in-game.");
			return true;
		}

		$coin = $this->plugin->getAllCoin();

		$allCoin = 0;
		foreach($coin as $m){
			$allCoin += $m;
		}
		$topCoin = 0;
		if($allCoin > 0){
			$topCoin = round((($coin[strtolower($sender->getName())] / $allCoin) * 100), 2);
		}

		$sender->sendMessage($this->plugin->getMessage("mystatuspp-show", [$topCoin], $sender->getName()));
		return true;
	}
}
