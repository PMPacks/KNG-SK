<?php

namespace kingofblock;

use pocketmine\Server;
use pocketmine\Player;

use pocketmine\plugin\PluginBase;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;

use pocketmine\event\Listener;

use pocketmine\item\Item;
use pocketmine\item\Pickaxe;

use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;

use pocketmine\event\player\PlayerJoinEvent;

use pocketmine\event\block\BlockBreakEvent;

use onebone\pointapi\PointAPI;

use jojoe77777\FormAPI\ModalForm;

class Main extends PluginBase implements Listener {
  
  public $kingofblock = [];
  
  public function onEnable(){
    $this->getLogger()->info("KingOfBlock made by pmmdst");
    $this->getServer()->getPluginManager()->registerEvents($this, $this);
    $this->point = $this->getServer()->getPluginManager()->getPlugin("PointAPI");
  }
  
  public function onDisable(){
    $this->getLogger()->info("KingOfBlock disable");
  }
  
  public function onCommand(CommandSender $sender, Command $cmd, String $label, array $args): bool{
    switch($cmd->getName()){
      case "kingofblock":
        if($sender instanceof Player){
          if($sender->hasPermission("kingofblock.command")){
            $this->KingOfBlockUi($sender);
          }else{
            $this->BuyBlock($sender);
          }
        }else{
          $sender->sendMessage("Pls use in game");
        }
    }
    return true;
  }
  
  public function KingOfBlockUi($player){
    $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
    $form = $api->createSimpleForm(function(Player $player, int $data = null){
      
      if($data === null){
        return true;
      }
      switch($data){
        case 0:
          if(!isset($this->kingofblock[strtolower($player->getName())])){
            $this->kingofblock[strtolower($player->getName())] = "on";
            $player->sendMessage("§aĐã bật thành công King Of Block");
          }else{
            $player->sendMessage("§cBạn đã bật King Of Block từ trước rồi!");
          }
          break;
          
          case 1:
            if(isset($this->kingofblock[strtolower($player->getName())])){
              unset($this->kingofblock[strtolower($player->getName())]);
              $player->sendMessage("§aĐã tắt King Of Block");
            }else{
              $player->sendMessage("§cBạn chưa bật King Of Block nên không thể tắt được");
            }
            break;
      }
    });
    $form->setTitle("§c【 KING OF BLOCK 】");
    $form->addButton("§aBật");
    $form->addButton("§cTắt");
    $form->sendToPlayer($player);
    return $form;
  }
  
  public function BuyBlock($player){
    $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
    $form = $api->createSimpleForm(function(Player $player, int $data = null){
      
      if($data === null){
        return true;
      }
      switch($data){
        case 0:
          $this->Buy($player);
          break;
      }
    });
    $form->setTitle("§c【 MUA KING OF BLOCK 】");
    $form->setContent("§cBạn chưa mua tính năng King Of Block:");
    $form->addButton("§eMUA NGAY\n§bGiá: 50 Point");
    $form->sendToPlayer($player);
    return $form;
  }
  
  public function Buy($player){
    $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
    $form = $api->createModalForm(function(Player $player, $data){
      
      if($data === null){
        return true;
      }
      switch($data){
        case 1:
          $point = PointAPI::getInstance()->myPoint($player);
          if($point >= 50){
            $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "setuperm " . $player->getName() . " kingofblock.command");
            $this->point->reducePoint($player, "50");
            $player->sendMessage("§aMua thành công King Of Block");
          }else{
            $player->sendMessage("§cBạn không đủ Point");
          }
          break;
          
          case 2:
            
            break;
      }
    });
    $form->setTitle("§e【 CHẤP NHẬN MUA 】");
    $form->setContent("§b»» Bạn có chắc muốn mua tính năng King Of Block với giá 50 Point?");
    $form->setButton1("§aChấp nhận");
    $form->setButton2("§cThoát");
    $form->sendToPlayer($player);
    return $form;
  }
  
  public function onJoin(PlayerJoinEvent $event){
    $player = $event->getPlayer();
    unset($this->kingofblock[strtolower($player->getName())]);
  }
  
  public function onBreak(BlockBreakEvent $event){
    $player = $event->getPlayer();
    $block = $event->getBlock();
    $item = $event->getItem();
    $pos = $block->add(0, 0, 0);
    $level = $block->getLevel();
    if(isset($this->kingofblock[strtolower($player->getName())])){
      if($item instanceof Pickaxe){
        if($block->getId() === 56){ //Diamond_Ore
          $diamond = Item::get(57, 0, 1);
          $level->dropItem($pos, $diamond);
          $event->setDrops([]);
          if($item->getEnchantment(18)){
            $levelec = $item->getEnchantment(18)->getLevel();
            $diamond2 = Item::get(57, 0, 1*$levelec);
            $level->dropItem($pos, $diamond2);
            $event->setDrops([]);
          }
        }
        if($block->getId() === 16){ //Coal_Ore
          $coal = Item::get(173, 0, 1);
          $level->dropItem($pos, $coal);
          $event->setDrops([]);
          if($item->getEnchantment(18)){
            $levelec = $item->getEnchantment(18)->getLevel();
            $coal2 = Item::get(173, 0, 1*$levelec);
            $level->dropItem($pos, $coal2);
            $event->setDrops([]);
          }
        }
        if($block->getId() === 129){ //Emerald_Ore
                  $eme = Item::get(133, 0, 1);
                  $level->dropItem($pos, $eme);
                  $event->setDrops([]);
                  if($item->getEnchantment(18)){
                    $levelec = $item->getEnchantment(18)->getLevel();
                    $eme2 = Item::get(133, 0, 1*$levelec);
                    $level->dropItem($pos, $eme2);
                    $event->setDrops([]);
                  }
                }
           if($block->getId() === 14){ //Gold_Ore
                     $gold = Item::get(41, 0, 1);
                     $level->dropItem($pos, $gold);
                     $event->setDrops([]);
                     if($item->getEnchantment(18)){
                       $levelec = $item->getEnchantment(18)->getLevel();
                       $gold2 = Item::get(41, 0, 1*$levelec);
                       $level->dropItem($pos, $gold2);
                       $event->setDrops([]);
                     }
                   }
             if($block->getId() === 15){ //Iron_Ore
                       $iron = Item::get(42, 0, 1);
                       $level->dropItem($pos, $iron);
                       $event->setDrops([]);
                       if($item->getEnchantment(18)){
                         $levelec = $item->getEnchantment(18)->getLevel();
                         $iron2 = Item::get(42, 0, 1*$levelec);
                         $level->dropItem($pos, $iron2);
                         $event->setDrops([]);
                       }
                     }     
             if($block->getId() === 21){ //Lapis_Ore
                       $lapis = Item::get(22, 0, 1);
                       $level->dropItem($pos, $lapis);
                       $event->setDrops([]);
                       if($item->getEnchantment(18)){
                         $levelec = $item->getEnchantment(18)->getLevel();
                         $lapis2 = Item::get(22, 0, 1*$levelec);
                         $level->dropItem($pos, $lapis2);
                         $event->setDrops([]);
                       }
                     }      
              if($block->getId() === 73){ //Redstone_Ore
                        $red = Item::get(152, 0, 1);
                        $level->dropItem($pos, $red);
                        $event->setDrops([]);
                        if($item->getEnchantment(18)){
                          $levelec = $item->getEnchantment(18)->getLevel();
                          $red2 = Item::get(152, 0, 1*$levelec);
                          $level->dropItem($pos, $red2);
                          $event->setDrops([]);
                        }
                      }
                if($block->getId() === 74){ //Redstone_Ore_2
          $redp = Item::get(152, 0, 1);
          $level->dropItem($pos, $redp);
          $event->setDrops([]);
          if($item->getEnchantment(18)){
            $levelec = $item->getEnchantment(18)->getLevel();
            $redp2 = Item::get(152, 0, 1*$levelec);
            $level->dropItem($pos, $redp2);
            $event->setDrops([]);
          }
        }      
      }
    }
  }
}