<?php
/**
*
*░█████╗░░█████╗░██████╗░███████╗
*██╔══██╗██╔══██╗██╔══██╗██╔════╝
*██║░░╚═╝██║░░██║██║░░██║█████╗░░
*██║░░██╗██║░░██║██║░░██║██╔══╝░░
*╚█████╔╝╚█████╔╝██████╔╝███████╗
*░╚════╝░░╚════╝░╚═════╝░╚══════╝
*
*
*██████╗░██╗░░░██╗
*██╔══██╗╚██╗░██╔╝
*██████╦╝░╚████╔╝░
*██╔══██╗░░╚██╔╝░░
*██████╦╝░░░██║░░░
*╚═════╝░░░░╚═╝░░░
*
*

*░█████╗░██╗░░░░░██╗░█████╗░██╗░░██╗███████╗██████╗░████████╗██████╗░░█████╗░███╗░░██╗
*██╔══██╗██║░░░░░██║██╔══██╗██║░██╔╝██╔════╝██╔══██╗╚══██╔══╝██╔══██╗██╔══██╗████╗░██║
*██║░░╚═╝██║░░░░░██║██║░░╚═╝█████═╝░█████╗░░██║░░██║░░░██║░░░██████╔╝███████║██╔██╗██║
*██║░░██╗██║░░░░░██║██║░░██╗██╔═██╗░██╔══╝░░██║░░██║░░░██║░░░██╔══██╗██╔══██║██║╚████║
*╚█████╔╝███████╗██║╚█████╔╝██║░╚██╗███████╗██████╔╝░░░██║░░░██║░░██║██║░░██║██║░╚███║
*░╚════╝░╚══════╝╚═╝░╚════╝░╚═╝░░╚═╝╚══════╝╚═════╝░░░░╚═╝░░░╚═╝░░╚═╝╚═╝░░╚═╝╚═╝░░╚══╝
*/
namespace KingNightVN\Menu;

use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\command\{
    Command,
    CommandSender,
    ConsoleCommandSender
};
use pocketmine\math\Vector3;
use pocketmine\event\Listener;

use onebone\pointapi\PointAPI;
use onebone\coinapi\CoinAPI;
use onebone\economyapi\EconomyAPI;

use jojoe77777\FormAPI\{
	SimpleForm,
	CustomForm
};

class Main extends PluginBase implements Listener{

  //Info
  public function onEnable(): void{
  	//Plugin Cần Thiết!
    $form = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
    $this->point = $this->getServer()->getPluginManager()->getPlugin("PointAPI");
    $this->money = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
    $this->coin = $this->getServer()->getPluginManager()->getPlugin("CoinAPI");
    //Please do not copy the idea, do not change the creator's name and copyright!
    $this->getLogger()->info("Plugin đã được bật!");
    $this->getLogger()->info("§c Author By ClickedTran");
    $this->getLogger()->info("§c Copyright By KingNightVN");
    $this->getLogger()->info("§c Idea By CuongAnime2006");
    $this->getLogger()->info("§c Don't Copy Idea, Edit Author And Copyright! ");
  }

/**
*Command:——————————————————————————————————
*/
  public function onCommand(CommandSender $sender, Command $cmd, String $label, Array $args): bool{
    if($sender instanceof Player){
     switch($cmd->getName()){
       case "menuquydoi":
       $this->menuForm($sender);
       break;
    }
   }else{ 
    $sender->sendMessage("Only Use In-game!!");
 }
 return true;
}

/**
*Menu Form:——————————————————————————————————
*/

  public function menuForm($sender){
   $form = new SimpleForm (function (Player $sender, $data){
    $result = $data;
     if($result == null){
      return true;
     }
      switch($result){
       case 0:
       break;
       case 1:
        $this->giaquydoi($sender);
        break;
        case 2:
        $this->doiCoin($sender);
       break;
       case 3:
        $this->doiPoint($sender);
       break;
       case 4:
        $this->doiXu($sender);
       break;
     }
    });
    $form->setTitle("§l§a« §cMenu§9 Quy Đổi§a »");
    $form->setContent("§l§aKHUYẾN CÁO CÁC MEMBER CÓ DẤU CÁCH TRONG TÊN VUI LÒNG K DÙNG MENU QUY ĐỔI!");
    $form->addButton("§l§cTHOÁT\n§l§a• §bẤn vào để thoát§a •");
    $form->addButton("§l§eGiá Quy Đổi\n§e• §bẤn Vào Để Xem§e •");
    $form->addButton("§l§eĐổi §9Coin\n§l§a• §bẤn vào để đổi§a •");
    $form->addButton("§l§eĐổi §9Point\n§l§a• §bẤn vào để đổi§a •");
    $form->addButton("§l§eĐổi §9Xu\n§l§a• §bẤn vào để đổi§a •");
    $form->sendToPlayer($sender);
  }

/**
*Giá Quy Đổi:——————————————————————————————————
*/

  public function giaquydoi($sender){
   $form = new SimpleForm(function (Player $sender, $data){
    $result = $data;
     if($result == null){
     	$this->menuForm($sender);
     return true;
     }
      switch($result){
        case 0:
        $this->menuForm($sender);
        break;
     }
   });
   $form->setTitle("§l§e« §bGiá Quy Đổi§e »");
   $form->setContent("§eXin Chào ". $sender->getName(). "\n§eDưới đây sẽ là thông tin giá cả để quy đổi§b Coin, Point §evà§b Xu§e nha
                                      \n§91,000,000 §bXu §a= §91§c Point
                                      \n§9100 §bPoint §a= §91§c Coin
                                      \n§91§b Coin§a = §980,000,000§c Xu
                                      \n§91§b Point§a = §9500,000§c Xu");
   $form->addButton("§l§eQuay Lại\n§l§a• §fBấm Vào Để Quay Lại Menu§a •");
   $form->sendToPlayer($sender);
}

/**
*Đổi Coin Bằng Point——————————————————————————————————
*/

  public function doiCoin($sender){
  	$form = new CustomForm( function(Player $sender, $data){
  	if($data == null){
  	     $this->menuForm($sender);
          return true;
        }
  	 $data[0] >= 1;
       $tien = $this->point->myPoint($sender);
       if($tien >= $data[0]*100){
           $this->point->reducePoint($sender, $data[0]*100);
           $this->coin->addCoin($sender, $data[0]);
           $sender->sendMessage("§l§f[§9 Hệ Thống§f ]§e Bạn đã quy đổi thành công§b ". $data[0]. " §cCoin§e Cảm ơn bạn đã sử dụng!");
         }else{
             $sender->sendMessage("§l§f[§9 Hệ Thống§f ]§c Bạn không đủ§b Point§c để quy đổi sang §bCoin§c. Vui lòng thử lại sau!");
         return true;
       }
      });
      $form->setTitle("§l§e« Đổi §bCoin§e »");
      $form->addInput("§eNhập số coin cần đổi vào ô dưới đây!", "§oLưu ý phải lớn hơn 0!");
      $form->sendToPlayer($sender);
   }
 
/**
*Đổi Point Bằng Xu:——————————————————————————————————
*/
  
   public function doiPoint($sender){
  	$form = new CustomForm( function(Player $sender, $data){
  	 if($data == null){
  	     $this->menuForm($sender);
          return true;
        }
  	 $data[0] >= 1;
       $tien = $this->money->myMoney($sender);
       if($tien >= $data[0]*1000000){
           $this->money->reduceMoney($sender, $data[0]*1000000);
           $this->point->addPoint($sender, $data[0]);
           $sender->sendMessage("§l§f[§9 Hệ Thống§f ]§e Bạn đã quy đổi thành công§b ". $data[0]. " §cPoint§e Cảm ơn bạn đã sử dụng!");
         }else{
             $sender->sendMessage("§l§f[§9 Hệ Thống§f ]§c Bạn không đủ§b Money§c để quy đổi sang §bPoint§c. Vui lòng thử lại sau!");
         return true;
       }
      });
      $form->setTitle("§l§e« Đổi §bPoint§e »");
      $form->addInput("§eNhập số Point cần đổi vào ô dưới đây!", "§oLưu ý phải lớn hơn 0!");
      $form->sendToPlayer($sender);
   }
   
/**
*Menu Đổi Xu:——————————————————————————————————
*/

   public function doiXu($sender){
  	$form = new SimpleForm( function(Player $sender, $data){
  	  $result = $data;
        if($result == null){
        	$this->menuForm($sender);
            return true;
          }
         switch($result){
         	case 0:
             $this->menuForm($sender);
             break;
             case 1:
             $this->giaquydoi($sender);
             break;
             case 2:
             $this->doibangCoin($sender);
             break;
             case 3:
             $this->doibangPoint($sender);
             break;
       }
      });
      $form->setTitle("§l§e« Đổi §bXu§e »");
      $form->setContent("§l§eVui Lòng Chọn §b1 §eTrong §b2§e Ô Dưới Đây!
                                       \n§l§bbCoin§e Của Bạn ". $this->coin->myCoin($sender).  
                                       "\n§l§bPoint§e Của Bạn ". $this->point->myPoint($sender));
       $form->addButton("§l§eQuay Lại\n§e• §bẤn Vào Để Quay Lại Menu§e •");
      $form->addButton("§l§eGiá Quy Đổi\n§e• §bẤn Vào Để Xem§e •");
      $form->addButton("§l§eĐổi §bMoney§e Bằng §bCoin\n§e• §bẤn Vô Để Đổi§e •");
      $form->addButton("§l§eĐổi §bMoney§e Bằng §bPoint\n§e• §bẤn Vô Để Đổi§e •");
      $form->sendToPlayer($sender);
   }
 
/**
*Đổi Xu Bằng Coin:——————————————————————————————————
*/
  
  public function doibangCoin($sender){
   $form = new CustomForm( function(Player $sender, $data){
        if($data[0] == null){
        	$this->doiXu($sender);
            return true;
         }
          if($data[0] == 1){
            if(!$sender->sendMessage("§l§f[§9 Hệ Thống§f ]§e Bạn đã quy đổi thành công §91§b Coin§e ra §980,000,000§b Xu!")){
                  $coin = $this->coin->myCoin($sender);
                  $cost = 1;
                if($coin >= $cost){
                	$this->coin->reduceCoin($sender, $cost);	
                    $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "givemoney ".$sender->getName()." 80000000");
                    
               }else{
                    $sender->sendMessage("§l§f[§9 Hệ Thống§f ]§c Bạn không đủ §bCoin§c để quy đổi §bXu!!!");
                   }
                }
             }
             
             if($data[0] == 2){
            if(!$sender->sendMessage("§l§f[§9 Hệ Thống§f ]§e Bạn đã quy đổi thành công §92§b Coin§e ra §9160,000,000§b Xu!")){
                  $coin = $this->coin->myCoin($sender);
                  $cost = 2;
                if($coin >= $cost){
                	$this->coin->reduceCoin($sender, $cost);	
                    $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "givemoney ".$sender->getName()." 160000000");
                   
               }else{
                    $sender->sendMessage("§l§f[§9 Hệ Thống§f ]§c Bạn không đủ §bCoin§c để quy đổi §bXu!!!");
                   }
                }
             }
       
             if($data[0] == 3){
            if(!$sender->sendMessage("§l§f[§9 Hệ Thống§f ]§e Bạn đã quy đổi thành công §93 §bbCoin§e ra §9240,000,000 §bXu!")){
                  $coin = $this->coin->myCoin($sender);
                  $cost = 3;
                if($coin >= $cost){
                	$this->coin->reduceCoin($sender, $cost);	
                    $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "givemoney ".$sender->getName()." 240000000");
                    
               }else{
                    $sender->sendMessage("§l§f[§9 Hệ Thống§f ]§c Bạn không đủ §bCoin§c để quy đổi §bXu!!!");
                   }
                }
             }
             
             if($data[0] == 4){
            if(!$sender->sendMessage("§l§f[§9 Hệ Thống§f ]§e Bạn đã quy đổi thành công §94 §bCoin§e ra §9320,000,000 §bXu!")){
                  $coin = $this->coin->myCoin($sender);
                  $cost = 4;
                if($coin >= $cost){
                	$this->coin->reduceCoin($sender, $cost);	
                    $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "givemoney ".$sender->getName()." 320000000");
                    return true;
               }else{
                    $sender->sendMessage("§l§f[§9 Hệ Thống§f ]§c Bạn không đủ §bCoin§c để quy đổi §bXu!!!");
                   }
                }
             }
           });
           $name = $sender->getName();
           $coin = $this->coin->myCoin($sender);
           $goi1 = 1;
           $goi2 = 2;
           $goi3 = 3;
           $goi4 = 4;
           $form->setTitle("§l§a« §eĐổi §bMoney§r | §l§cCOIN§a »");
           $form->addDropdown("§l§eXin chào ". $name. "\n§l§eSố §bCoin§e Của Bạn ". $coin. "\n§l§eVui lòng chọn gói mà bạn muốn quy đổi:", [
                                                   "§l§a• §cQUAY LẠI§a •",
                                                   "§l§a« §eGói§c 1 §egiá §9 ". $goi1. " §l§bCoin§a »",
                                                   "§l§a« §eGói§c 2 §egiá§9 ". $goi2. " §l§bCoin§a »",
                                                   "§l§a« §eGói§c 3 §egiá§9 ". $goi3. " §l§bCoin§a »",
                                                   "§l§a« §eGói§c 4 §egiá ". $goi4. " §l§bCoin§a »"
                                                 ]);
            $form->sendToPlayer($sender);
     }
 
/**
*Đổi Xu Bằng Point:——————————————————————————————————
*/ 
     public function doibangPoint($sender){
     	$form = new CustomForm(function (Player $sender, $data){
     	if($data[0] == null){
     	    $this->doiXu($sender);
              return true;
           }
          if($data[0] == 1){
            if(!$sender->sendMessage("§l§f[§9 Hệ Thống§f ]§e Bạn đã quy đổi thành công §91 §bPoint§e ra §9500,000 §bXu!")){
                  $point = $this->point->myPoint($sender);
                  $cost = 1;
                if($point >= $cost){
                	$this->point->reducePoint($sender, $cost);	
                    $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "givemoney ".$sender->getName()." 500000");
                  
               }else{
                    $sender->sendMessage("§l§f[§9 Hệ Thống§f ]§c Bạn không đủ §bPoint§c để quy đổi §bXu!!!");
                   }
                }
             }
             
          if($data[0] == 2){
            if(!$sender->sendMessage("§l§f[§9 Hệ Thống§f ]§e Bạn đã quy đổi thành công §910 §bPoint§e ra §95,000,000 §bXu!")){
                  $point = $this->point->myPoint($sender);
                  $cost = 10;
                if($point >= $cost){
                	$this->point->reducePoint($sender, $cost);	
                    $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "givemoney ".$sender->getName()." 5000000");
                 
               }else{
                    $sender->sendMessage("§l§f[§9 Hệ Thống§f ]§c Bạn không đủ §bPoint§c để quy đổi §bXu!!!");
                   }
                }
             }
           
           if($data[0] == 3){
            if(!$sender->sendMessage("§l§f[§9 Hệ Thống§f ]§e Bạn đã quy đổi thành công §9100 §bPoint§e ra §950,000,000 §bXu!")){
                  $point = $this->point->myPoint($sender);
                  $cost = 100;
                if($point >= $cost){
                	$this->point->reducePoint($sender, $cost);	
                    $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "givemoney ".$sender->getName()." 50000000");
                  
               }else{
                    $sender->sendMessage("§l§f[§9 Hệ Thống§f ]§c Bạn không đủ §bPoint§c để quy đổi §bXu!!!");
                   }
                }
             }
             
           if($data[0] == 4){
            if(!$sender->sendMessage("§l§f[§9 Hệ Thống§f ]§e Bạn đã quy đổi thành công §91,000 §bPoint§e ra §9500,000,000 §bXu!")){
                  $point = $this->point->myPoint($sender);
                  $cost = 1000;
                if($point >= $cost){
                	$this->point->reducePoint($sender, $cost);	
                    $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "givemoney ".$sender->getName()." 500000000");
                
               }else{
                    $sender->sendMessage("§l§f[§9 Hệ Thống§f ]§c Bạn không đủ §bCoin§c để quy đổi §bXu!!!");
                   }
                }
             }
           });
           $name = $sender->getName();
           $point = $this->point->myPoint($sender);
           $goi1 = 1;
           $goi2 = 10;
           $goi3 = 100;
           $goi4 = 1000;
           $form->setTitle("§l§a«§e Đổi §bMoney §r| §l§cPOINT§a »");
           $form->addDropdown("§l§eXin chào ". $name. "\n§l§eSố §bPoint§e Của Bạn ". $point. "\n§eVui lòng chọn gói mà bạn muốn quy đổi:", [
                                                   "§l§a• §cQUAY LẠI§a •",
                                                   "§l§a« §eGói§c 1 §egiá §9 ". $goi1. " §l§bPoint§a »",
                                                   "§l§a« §eGói§c 2 §egiá§9 ". $goi2. " §l§bPoint§a »",
                                                   "§l§a« §eGói§c 3 §egiá§9 ". $goi3. " §l§bPoint§a »",
                                                   "§l§a« §eGói§c 4 §egiá ". $goi4. " §l§bPoint§a »"
                                                 ]);
            $form->sendToPlayer($sender);
     }
}

/**
*
*░█████╗░░█████╗░██████╗░███████╗
*██╔══██╗██╔══██╗██╔══██╗██╔════╝
*██║░░╚═╝██║░░██║██║░░██║█████╗░░
*██║░░██╗██║░░██║██║░░██║██╔══╝░░
*╚█████╔╝╚█████╔╝██████╔╝███████╗
*░╚════╝░░╚════╝░╚═════╝░╚══════╝
*
*
*██████╗░██╗░░░██╗
*██╔══██╗╚██╗░██╔╝
*██████╦╝░╚████╔╝░
*██╔══██╗░░╚██╔╝░░
*██████╦╝░░░██║░░░
*╚═════╝░░░░╚═╝░░░
*
*

*░█████╗░██╗░░░░░██╗░█████╗░██╗░░██╗███████╗██████╗░████████╗██████╗░░█████╗░███╗░░██╗
*██╔══██╗██║░░░░░██║██╔══██╗██║░██╔╝██╔════╝██╔══██╗╚══██╔══╝██╔══██╗██╔══██╗████╗░██║
*██║░░╚═╝██║░░░░░██║██║░░╚═╝█████═╝░█████╗░░██║░░██║░░░██║░░░██████╔╝███████║██╔██╗██║
*██║░░██╗██║░░░░░██║██║░░██╗██╔═██╗░██╔══╝░░██║░░██║░░░██║░░░██╔══██╗██╔══██║██║╚████║
*╚█████╔╝███████╗██║╚█████╔╝██║░╚██╗███████╗██████╔╝░░░██║░░░██║░░██║██║░░██║██║░╚███║
*░╚════╝░╚══════╝╚═╝░╚════╝░╚═╝░░╚═╝╚══════╝╚═════╝░░░░╚═╝░░░╚═╝░░╚═╝╚═╝░░╚═╝╚═╝░░╚══╝
*/