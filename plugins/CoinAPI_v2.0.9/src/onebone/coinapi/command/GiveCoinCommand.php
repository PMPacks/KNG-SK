<?php

namespace onebone\coinapi\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;
use pocketmine\Player;

use onebone\coinapi\CoinAPI;

class GiveCoinCommand extends Command{
	private $plugin;

	public function __construct(CoinAPI $plugin){
		$desc = $plugin->getCommandMessage("givecoin");
		parent::__construct("givecoin", $desc["description"], $desc["usage"]);

		$this->setPermission("coinapi.command.givecoin");

		$this->plugin = $plugin;
	}

	public function execute(CommandSender $sender, string $label, array $params): bool{
		if(!$this->plugin->isEnabled()) return false;
		if(!$this->testPermission($sender)){
			return false;
		}

		$player = array_shift($params);
		$amount = array_shift($params);

		if(!is_numeric($amount)){
			$sender->sendMessage(TextFormat::RED . "Usage: " . $this->getUsage());
			return true;
		}

		if(($p = $this->plugin->getServer()->getPlayer($player)) instanceof Player){
			$player = $p->getName();
		}

		$result = $this->plugin->addCoin($player, $amount);
		switch($result){
			case CoinAPI::RET_INVALID:
			$sender->sendMessage($this->plugin->getMessage("givecoin-invalid-number", [$amount], $sender->getName()));
			break;
			case CoinAPI::RET_SUCCESS:
			$sender->sendMessage($this->plugin->getMessage("givecoin-gave-coin", [$amount, $player], $sender->getName()));

			if($p instanceof Player){
				$p->sendMessage($this->plugin->getMessage("givecoin-coin-given", [$amount], $sender->getName()));
			}
			break;
			case CoinAPI::RET_CANCELLED:
			$sender->sendMessage($this->plugin->getMessage("request-cancelled", [], $sender->getName()));
			break;
			case CoinAPI::RET_NO_ACCOUNT:
			$sender->sendMessage($this->plugin->getMessage("player-never-connected", [$player], $sender->getName()));
			break;
		}
        return true;
	}
}
