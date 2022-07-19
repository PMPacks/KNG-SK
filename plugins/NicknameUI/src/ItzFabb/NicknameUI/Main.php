<?php

namespace ItzFabb\NicknameUI;

use pocketmine\Server;
use pocketmine\Player;

use pocketmine\plugin\PluginBase;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use pocketmine\event\Listener;

use jojoe77777\FormAPI\FormAPI;
use jojoe77777\FormAPI\SimpleForm;
use jojoe77777\FormAPI\CustomForm;

class Main extends PluginBase implements Listener {
  
  public function onEnable(){
    $this->getLogger()->info("§bNickUI §aMade by ItzFabb §lENABLED!§r");
  
  }
  
  public function onCommand(CommandSender $sender, Command $cmd, String $label, Array $args) : bool {

  	switch($cmd->getName()){
  		case "nickname":
  		 if($sender instanceof Player){
  		 	if($sender->hasPermission("nickname.cmd.use")){
  		 		$this->NickForm($sender);
  		 	}
  		 }
  	}
  return true;
  }
  
  public function NickForm($player){
    $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
    $form = $api->createSimpleForm(function (Player $player, int $data = null){
      $result = $data;
      if($result === null){
        return true;
      }
      switch($result){
        case 0:
          
          $this->CustomNickForm($player);
          
          break;
          
        case 1:
          
          $this->ResetNick($player);
          
          break;
          
        case 2;
          break;
      }
    });
    $form->setTitle("§9§l« §r§9Menu §cNickName §9§l»§r");
    $form->addButton("§a§lĐổi Tên\n§r§8Bấm vào để tiếp tục", 0, "textures/ui/confirm");
    $form->addButton("§c§lReset Tên\n§r§8Bấm vào để reset", 0, "textures/ui/trash");
    $form->addButton("§c§lTHOÁT\n§r§8Bấm vào để thoát", 0, "textures/ui/cancel");
    $form->sendToPlayer($player);
    return $form;
  }
  
  public function CustomNickForm($player){
    $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
    $form = $api->createCustomForm(function (Player $player, array $data = null){
      if($data === null){
        return true;
      }
      if($data[0] == "reset"){
        $this->ResetNick($player); 
      }
      $player->setDisplayName($data[0]);
      $player->setNameTag($data[0]);
      $player->sendMessage("§6Bạn đã đổi tên thành §c" . $data[0]);
    });
    $form->setTitle("§9§l«§r §1Nickname Menu §9§l»§r");
    $form->addInput("§6Nhập tên bạn muốn đổi vào đây:", "§7Nickname...");
    $form->sendToPlayer($player);
    return $form;
  }
  
  private function ResetNick(Player $player){
  	$player->setDisplayName($player->getName());
  	$player->setNameTag($player->getName());
  	$player->sendMessage("§eTên của bạn đã được reset!");
  	return true;
  }
  
}
