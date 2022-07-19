<?php

namespace KeyShopUI;

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\CommandExecutor;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\utils\Config;
use jojoe77777\FormAPI;
use onebone\pointapi\PointAPI;
use jojoe77777\FormAPI\SimpleForm;

class Main extends PluginBase implements Listener {
	
	public function onEnable(){
		$this->getLogger()->info("Enable Plugin By xVxSkoeyxVx");
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
	}
	
	public function onCommand(CommandSender $sender, Command $cmd, string $label,array $args): bool{
		switch($cmd->getName()){
			case "keyshop":
			if(!$sender instanceof Player){
			$sender->sendMessage("Pakai Command Di In-Game");
			return false;
			}
			if($sender instanceof Player){
			$this->shopFrom($sender);
			}
			break;		
		}
		return true;
	}
	
	public function shopFrom(Player $player){
		$form = new SimpleForm(function (Player $player, $data){
		$result = $data;
		if($result === null){
			return true;
			}
			switch($result){
				case 0:
					if(\pocketmine\Server::getInstance()->getPluginManager()->getPlugin("PointAPI")->myPoint($player) >= $this->getConfig()->get("common.price")){
						$this->getServer()->dispatchCommand(new \pocketmine\command\ConsoleCommandSender(), "key Common ".$player->getName()." ".$this->getConfig()->get("common.amount"));
						$player->sendMessage($this->getConfig()->get("common.success.purchase"));
						PointAPI::getInstance()->reducePoint($player, $this->getConfig()->get("common.price"));
					} else {
						$player->sendMessage($this->getConfig()->get("not.enough.money"));
					}
				break;
				case 1:
					if(\pocketmine\Server::getInstance()->getPluginManager()->getPlugin("PointAPI")->myPoint($player) >= $this->getConfig()->get("uncommon.price")){
						$this->getServer()->dispatchCommand(new \pocketmine\command\ConsoleCommandSender(), "key UnCommon ".$player->getName()." ".$this->getConfig()->get("uncommon.amount"));
						$player->sendMessage($this->getConfig()->get("uncommon.success.purchase"));
						PointAPI::getInstance()->reducePoint($player, $this->getConfig()->get("uncommon.price"));
					} else {
						$player->sendMessage($this->getConfig()->get("not.enough.money"));
					}
				break;
				case 2:
					if(\pocketmine\Server::getInstance()->getPluginManager()->getPlugin("PointAPI")->myPoint($player) >= $this->getConfig()->get("mythic.price")){
						$this->getServer()->dispatchCommand(new \pocketmine\command\ConsoleCommandSender(), "key Mythic ".$player->getName()." ".$this->getConfig()->get("mythic.amount"));
						$player->sendMessage($this->getConfig()->get("mythic.success.purchase"));
						PointAPI::getInstance()->reducePoint($player, $this->getConfig()->get("mythic.price"));
					} else {
						$player->sendMessage($this->getConfig()->get("not.enough.money"));
					}
				break;
				case 3:
					if(\pocketmine\Server::getInstance()->getPluginManager()->getPlugin("PointAPI")->myPoint($player) >= $this->getConfig()->get("legendary.price")){
						$this->getServer()->dispatchCommand(new \pocketmine\command\ConsoleCommandSender(), "key Legendary ".$player->getName()." ".$this->getConfig()->get("legendary.amount"));
						$player->sendMessage($this->getConfig()->get("legendary.success.purchase"));
						PointAPI::getInstance()->reducePoint($player, $this->getConfig()->get("legendary.price"));
					} else {
						$player->sendMessage($this->getConfig()->get("not.enough.money"));
					}
				break;
			}
		});
		$config = $this->getConfig();
		$this->getServer()->getPluginManager()->getPlugin("PointAPI")->myPoint($player);
		$form->setTitle("§l§dKey§eShopUI");
		$form->setContent("§l§e➛ §aXin Chào: §f". $player->getName(). "\n§aSố Tiền Bạn Đang Có: §f" . $this->getServer()->getPluginManager()->getPlugin("PointAPI")->myPoint($player). "\n§aRank Của Bạn: §f". $this->getServer()->getPluginManager()->getPlugin("PurePerms")->getUserDataMgr()->getGroup($player)->getName() . "\n§aChỉ Có Tại: KingNightVN\n§cIP : §akingnightvn.ddns.net");
		$form->addButton("§l§eCommon\n§aGiá: 25 §cPoint§e", 0, "textures/ui/accessibility_glyph_color");
		$form->addButton("§l§eUnCommon\n§aGiá: 35§c Point§e", 0, "textures/ui/accessibility_glyph_color");
		$form->addButton("§l§eMythic\n§aGiá: 55§cPoint§e", 0, "textures/ui/accessibility_glyph_color");
		$form->addButton("§l§eLegendary\n§aGiá: 70§c Point§e", 0, "textures/ui/accessibility_glyph_color");
		$form->sendToPlayer($player);
	}
}
