<?php

namespace onebone\coinapi\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;
use pocketmine\Player;

use onebone\coinapi\CoinAPI;
use onebone\coinapi\event\coin\PayCoinEvent;

class PayCoinCommand extends Command{
	private $plugin;

	public function __construct(CoinAPI $plugin){
		$desc = $plugin->getCommandMessage("paycoin");
		parent::__construct("paycoin", $desc["description"], $desc["usage"]);

		$this->setPermission("coinapi.command.paycoin");

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

		$player = array_shift($params);
		$amount = array_shift($params);

		if(!is_numeric($amount)){
			$sender->sendMessage(TextFormat::RED . "Usage: " . $this->getUsage());
			return true;
		}

		if(($p = $this->plugin->getServer()->getPlayer($player)) instanceof Player){
			$player = $p->getName();
		}

		if(!$p instanceof Player and $this->plugin->getConfig()->get("allow-pay-offline", true) === false){
			$sender->sendMessage($this->plugin->getMessage("player-not-connected", [$player], $sender->getName()));
			return true;
		}

		if(!$this->plugin->accountExists($player)){
			$sender->sendMessage($this->plugin->getMessage("player-never-connected", [$player], $sender->getName()));
			return true;
		}

		$this->plugin->getServer()->getPluginManager()->callEvent($ev = new PayCoinEvent($this->plugin, $sender->getName(), $player, $amount));

		$result = CoinAPI::RET_CANCELLED;
		if(!$ev->isCancelled()){
			$result = $this->plugin->reduceCoin($sender, $amount);
		}

		if($result === CoinAPI::RET_SUCCESS){
			$this->plugin->addCoin($player, $amount, true);

			$sender->sendMessage($this->plugin->getMessage("pay-success", [$amount, $player], $sender->getName()));
			if($p instanceof Player){
				$p->sendMessage($this->plugin->getMessage("coin-paid", [$sender->getName(), $amount], $sender->getName()));
			}
		}else{
			$sender->sendMessage($this->plugin->getMessage("pay-failed", [$player, $amount], $sender->getName()));
		}
		return true;
	}
}
