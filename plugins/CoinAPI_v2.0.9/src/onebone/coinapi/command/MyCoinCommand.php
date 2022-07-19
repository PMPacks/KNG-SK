<?php

namespace onebone\coinapi\command;

use pocketmine\event\TranslationContainer;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;
use pocketmine\Player;

use onebone\coinapi\CoinAPI;

class MyCoinCommand extends Command{
	private $plugin;

	public function __construct(CoinAPI $plugin){
		$desc = $plugin->getCommandMessage("mycoin");
		parent::__construct("mycoin", $desc["description"], $desc["usage"]);

		$this->setPermission("coinapi.command.mycoin");

		$this->plugin = $plugin;
	}

	public function execute(CommandSender $sender, string $label, array $params): bool{
		if(!$this->plugin->isEnabled()) return false;
		if(!$this->testPermission($sender)){
			return false;
		}

		if($sender instanceof Player){
			$coin = $this->plugin->myCoin($sender);
			$sender->sendMessage($this->plugin->getMessage("mycoin-mycoin", [$coin]));
			return true;
		}
		$sender->sendMessage(TextFormat::RED."Please run this command in-game.");
		return true;
	}
}
