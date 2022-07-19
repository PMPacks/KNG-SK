<?php

namespace MuaSsp;

use pocketmine\{Player, Server};
use pocketmine\plugin\PluginBase;
use pocketmine\utils\{TextFormat};
use pocketmine\event\Listener;
use pocketmine\command\{Command, CommandSender, ConsoleCommandSender};
use jojoe77777\FormAPI;
use onebone\pointapi\PointAPI;

class Main extends PluginBase implements Listener{
	
	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$this->point = $this->getServer()->getPluginManager()->getPlugin("PointAPI");
	}
	
	public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args): bool{
		$player = $sender->getPlayer();
		switch ($cmd->getName()){
			case "muassp":
			if(!($sender instanceof Player)){
				$this->getLogger()->notice("Dùng lệnh trong game.");
				return true;
			}
			$this->mainForm($player);
			break;
		}
		return true;
    }
    
	public function mainForm($player){

		$form = $this->formapi->createSimpleForm(function (Player $player, $result){

			if($result === null){
				return;
			}
			switch($result){
				case 0:
				    if($player->hasPermission("royalpass.knvn")){
				       $player->sendMessage("§l§a【Ｓeason Ｐass】 §r§cBạn đã mua thẻ huyền thoại rồi");
				       return;
				    }
			        if($this->point->myPoint($player) >= 1000){
				        $player->sendMessage("§l§a【Ｓeason Ｐass】 §r§eBạn đã mua thành công §6thẻ huyền thoại.");
				        $this->point->reducePoint($player, 1000);
				        $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "setuperm " .  $player->getName() . " royalpass.knvn");
			        }else{
			          $player->sendMessage("§l§a【Ｓeason Ｐass】 §r§cBạn cần §61000 POINTS§c để mua thẻ huyền thoại này!");
			        }
				break;
				
		}
		});
		$form->setTitle("§l【 MUA THẺ HUYỀN THOẠI MÙA I 】");
		$form->setContent("\n§7➣ Mua thẻ huyền thoại để nhận đặc quyền dùng thẻ huyền thoại ở §6/ssp\n\n");
		$form->addButton("§lKÍCH HOẠT ROYAL SEASONPASS\n§r§8GIÁ: 1000 POINTS");
		$form->sendToPlayer($player);
		
	}
 
}