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

namespace KingNightVN\MenuPET;

use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\command\{
    Command,
    CommandSender
};
use pocketmine\math\Vector3;
use pocketmine\event\Listener;

use pocketmine\utils\Config;

use onebone\pointapi\PointAPI;
use onebone\economyapi\EnocomyAPI;

use jojoe77777\FormAPI\{
	SimpleForm,
	CustomForm
};
use pocketmine\item\Item;

class Main extends PluginBase implements Listener{
	 public $config;
	public function onEnable(): void{
    $this->pointapi = $this->getServer()->getPluginManager()->getPlugin("PointAPI");
    $this->form = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
    $this->money = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
    $this->getServer()->getPluginManager()->registerEvents($this, $this);
/**
*Please do not copy the idea, do not change the creator's name and copyright!
*INFO PLUGIN:———————————————————————————
*/
    $this->getLogger()->info("Plugin đã được bật!");
    $this->getLogger()->info("§c Author By ClickedTran");
    $this->getLogger()->info("§c Copyright By KingNightVN");
    $this->getLogger()->info("§c Idea By CuongAnime2006");
    $this->getLogger()->info("§c Don't Copy Idea, Edit Author And Copyright! ");
    //copyrightbyKingNightVN!
    
    //Config
    @mkdir($this->getDataFolder());
        $this->saveResource("config.yml");
        $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
        $this->saveDefaultConfig();
        $this->reloadConfig();
}
    public function onCommand(CommandSender $sender, Command $cmd, String $label, Array $args): bool
{
	 if($sender instanceof Player){
		 switch($cmd->getName()){
			case "menupet":
			 $this->baotriUI($sender);
			break;
	   }
	} else {
		 $sender->sendMessage("Only use Ingame!");
	}
	return true;
}

/**
*BẢO TRÌ:————————————————————————
*/
public function baotriUI($sender){
  $form = new SimpleForm(function(Player $sender, $data){
    $result = $data;
    if($result == null){
      return true;
    }
    switch($reult){
     case 0:
     break;
    }
   });
   $form->setTitle("§l§a« §cBẢO TRÌ §a»");
   $form->setContent("§eXin chào§b ". $sender->getName(). "\n§eHiện tại thì hệ thống PET của server đang bảo trì\n§enên các bạn vui lòng quay lại sau nhé\n§ekhi nào xong admin sẽ báo trên console!");
   $form->addButton("§l§cTHOÁT");
   $form->sendToPlayer($sender);
}

/**
*Menu:———————————————————————————
*/
  public function menuUI($sender){
  	$form = new SimpleForm(function (Player $sender, $data){
  	  $result = $data;
  	 if($result == null){
  	     return true;
          }
         switch($result){
         	case 0:
             break; 
             case 1:
             $this->shopPet($sender);
             break;
             case 2:
             $this->quanliPet($sender);
             break;
             case 3:
             $this->menuPhuKien($sender);
             break;
             case 4:
             $command = "petstop";
		     $this->plugin->getServer()->dispatchCommand(new ConsoleCommandSender, $command);
             break;
          }
          });
          $form->setTitle("§l§a« §9Menu §bPET§a »");
          $form->addButton("§l§a• §cTHOÁT §a•");
          $form->addButton("§l§a• §eSHOP PET§a •");
          $form->addButton("§l§a• §eQUẢN LÍ PET §a•");
          $form->addButton("§l§a §eMUA PHỤ KIỆN§a •");
          $form->addButton("§l§a• §cTOP §ePET§a •");
          $form->sendToPlayer($sender);
  }
 
/**
*CODE SHOP PET:——————————————————————
*/ 

  public function shopPet($sender){
      $form = new CustomForm(function (Player $sender, $data){
      if($data == null){
      	$this->menuUI($sender);
          return true;
        }
        if($data[0] == 1){
         if(!$sender->sendMessage("§l§f[§9 Hệ Thống§f ]§e Bạn đã mua thành công pet §bWolf§e với giá §b50§c Point!")){
             $name = $sender->getName();
             $point = $this->pointapi->myPoint($sender);
             $wolf = 50;
          if($point >= $wolf){
         	$this->getServer()->dispatchCommand(new ConsoleCommandSender, "setuperm ". $name. " blockpets.command.changepetname.use");
         	$this->getServer()->dispatchCommand(new ConsoleCommandSender, "setuperm ". $name. " blockpets.command.togglepet");
             $this->getServer()->dispatchCommand(new ConsoleCommandSender, "spawnpet wolf $data[1] 1 baby ". $name);
             $this->pointapi->reducePoint($name, $wolf);
            return true;
        }else{
            $sender->sendMessage("§l§f[§9 Hệ Thống§f ]§c Bạn không đủ §bPoint§e để mua pet này!!");
        }
     }
   }
     
   if($data[0] == 2){
         if(!$sender->sendMessage("§l§f[§9 Hệ Thống§f ]§e Bạn đã mua thành công pet §bOcelot§e với giá §b50§c Point!")){
             $name = $sender->getName();
             $point = $this->pointapi->myPoint($sender);
             $cat = 50;
          if($point >= $cat){
         	$this->getServer()->dispatchCommand(new ConsoleCommandSender, "setuperm ". $name. " blockpets.command.changepetname.use");
         	$this->getServer()->dispatchCommand(new ConsoleCommandSender, "setuperm ". $name. " blockpets.command.togglepet");
             $this->getServer()->dispatchCommand(new ConsoleCommandSender, "spawnpet Ocelot $data[1] 1 baby ". $name);
             $this->pointapi->reducePoint($name, $cat);
            return true;
        }else{
            $sender->sendMessage("§l§f[§9 Hệ Thống§f ]§c Bạn không đủ §bPoint§e để mua pet này!!");
        }
     }
   }

  if($data[0] == 3){
         if(!$sender->sendMessage("§l§f[§9 Hệ Thống§f ]§e Bạn đã mua thành công pet §bBlaze§e với giá §b50§c Point!")){
             $name = $sender->getName();
             $point = $this->pointapi->myPoint($sender);
             $balze = 50;
          if($point >= $blaze){
         	$this->getServer()->dispatchCommand(new ConsoleCommandSender, "setuperm ". $name. " blockpets.command.changepetname.use");
         	$this->getServer()->dispatchCommand(new ConsoleCommandSender, "setuperm ". $name. " blockpets.command.togglepet");
             $this->getServer()->dispatchCommand(new ConsoleCommandSender, "spawnpet blaze $data[1] 1 baby ". $name);
             $this->pointapi->reducePoint($name, $blaze);
            return true;
        }else{
            $sender->sendMessage("§l§f[§9 Hệ Thống§f ]§c Bạn không đủ §bPoint§e để mua pet này!!");
        }
     }
   }

   if($data[0] == 4){
         if(!$sender->sendMessage("§l§f[§9 Hệ Thống§f ]§e Bạn đã mua thành công pet §bCaveSpider§e với giá §b50§c Point!")){
             $name = $sender->getName();
             $point = $this->pointapi->myPoint($sender);
             $cspider = 50;
          if($point >= $cspider){
         	$this->getServer()->dispatchCommand(new ConsoleCommandSender, "setuperm ". $name. " blockpets.command.changepetname.use");
         	$this->getServer()->dispatchCommand(new ConsoleCommandSender, "setuperm ". $name. " blockpets.command.togglepet");
             $this->getServer()->dispatchCommand(new ConsoleCommandSender, "spawnpet cavespider $data[1] 1 baby ". $name);
             $this->pointapi->reducePoint($name, $cspider);
            return true;
        }else{
            $sender->sendMessage("§l§f[§9 Hệ Thống§f ]§c Bạn không đủ §bPoint§e để mua pet này!!");
        }
     }
   }

   if($data[0] == 5){
         if(!$sender->sendMessage("§l§f[§9 Hệ Thống§f ]§e Bạn đã mua thành công pet §bChicken§e với giá §b50§c Point!")){
             $name = $sender->getName();
             $point = $this->pointapi->myPoint($sender);
             $chicken = 50;
          if($point >= $chicken){
         	$this->getServer()->dispatchCommand(new ConsoleCommandSender, "setuperm ". $name. " blockpets.command.changepetname.use");
         	$this->getServer()->dispatchCommand(new ConsoleCommandSender, "setuperm ". $name. " blockpets.command.togglepet");
             $this->getServer()->dispatchCommand(new ConsoleCommandSender, "spawnpet chicken $data[1] 1 baby ". $name);
             $this->pointapi->reducePoint($name, $chicken);
            return true;
        }else{
            $sender->sendMessage("§l§f[§9 Hệ Thống§f ]§c Bạn không đủ §bPoint§e để mua pet này!!");
        }
     }
   }

   if($data[0] == 6){
         if(!$sender->sendMessage("§l§f[§9 Hệ Thống§f ]§e Bạn đã mua thành công pet §bCow§e với giá §b50§c Point!")){
             $name = $sender->getName();
             $point = $this->pointapi->myPoint($sender);
             $cow = 50;
          if($point >= $cow){
         	$this->getServer()->dispatchCommand(new ConsoleCommandSender, "setuperm ". $name. " blockpets.command.changepetname.use");
         	$this->getServer()->dispatchCommand(new ConsoleCommandSender, "setuperm ". $name. " blockpets.command.togglepet");
             $this->getServer()->dispatchCommand(new ConsoleCommandSender, "spawnpet cow $data[1] 1 baby ". $name);
             $this->pointapi->reducePoint($name, $cow);
            return true;
        }else{
            $sender->sendMessage("§l§f[§9 Hệ Thống§f ]§c Bạn không đủ §bPoint§e để mua pet này!!");
        }
     }
   }

  if($data[0] == 7){
         if(!$sender->sendMessage("§l§f[§9 Hệ Thống§f ]§e Bạn đã mua thành công pet §bDonkey§e với giá §b50§c Point!")){
             $name = $sender->getName();
             $point = $this->pointapi->myPoint($sender);
             $donkey = 50;
          if($point >= $donkey){
         	$this->getServer()->dispatchCommand(new ConsoleCommandSender, "setuperm ". $name. " blockpets.command.changepetname.use");
         	$this->getServer()->dispatchCommand(new ConsoleCommandSender, "setuperm ". $name. " blockpets.command.togglepet");
             $this->getServer()->dispatchCommand(new ConsoleCommandSender, "spawnpet donkey $data[1] 1 baby ". $name);
             $this->pointapi->reducePoint($name, $donkey);
            return true;
        }else{
            $sender->sendMessage("§l§f[§9 Hệ Thống§f ]§c Bạn không đủ §bPoint§e để mua pet này!!");
        }
     }
   }
   
   if($data[0] == 8){
         if(!$sender->sendMessage("§l§f[§9 Hệ Thống§f ]§e Bạn đã mua thành công pet §bDragon§e với giá §b50§c Point!")){
             $name = $sender->getName();
             $point = $this->pointapi->myPoint($sender);
             $dragon = 100;
          if($point >= $dragon){
         	$this->getServer()->dispatchCommand(new ConsoleCommandSender, "setuperm ". $name. " blockpets.command.changepetname.use");
         	$this->getServer()->dispatchCommand(new ConsoleCommandSender, "setuperm ". $name. " blockpets.command.togglepet");
             $this->getServer()->dispatchCommand(new ConsoleCommandSender, "spawnpet dragon $data[1] 1 baby ". $name);
             $this->pointapi->reducePoint($name, $dragon);
            return true;
        }else{
            $sender->sendMessage("§l§f[§9 Hệ Thống§f ]§c Bạn không đủ §bPoint§e để mua pet này!!");
        }
     }
   }

   if($data[0] == 9){
         if(!$sender->sendMessage("§l§f[§9 Hệ Thống§f ]§e Bạn đã mua thành công pet §bEnderMan§e với giá §b50§c Point!")){
             $name = $sender->getName();
             $point = $this->pointapi->myPoint($sender);
             $enderman = 50;
          if($point >= $enderman){
         	$this->getServer()->dispatchCommand(new ConsoleCommandSender, "setuperm ". $name. " blockpets.command.changepetname.use");
         	$this->getServer()->dispatchCommand(new ConsoleCommandSender, "setuperm ". $name. " blockpets.command.togglepet");
             $this->getServer()->dispatchCommand(new ConsoleCommandSender, "spawnpet enderman $data[1] 1 baby ". $name);
             $this->pointapi->reducePoint($name, $enderman);
            return true;
        }else{
            $sender->sendMessage("§l§f[§9 Hệ Thống§f ]§c Bạn không đủ §bPoint§e để mua pet này!!");
        }
     }
   }   
   
   if($data[0] == 10){
         if(!$sender->sendMessage("§l§f[§9 Hệ Thống§f ]§e Bạn đã mua thành công pet §bFox§e với giá §b50§c Point!")){
             $name = $sender->getName();
             $point = $this->pointapi->myPoint($sender);
             $fox = 75;
          if($point >= $fox){
         	$this->getServer()->dispatchCommand(new ConsoleCommandSender, "setuperm ". $name. " blockpets.command.changepetname.use");
         	$this->getServer()->dispatchCommand(new ConsoleCommandSender, "setuperm ". $name. " blockpets.command.togglepet");
             $this->getServer()->dispatchCommand(new ConsoleCommandSender, "spawnpet fox $data[1] 1 baby ". $name);
             $this->pointapi->reducePoint($name, $fox);
            return true;
        }else{
            $sender->sendMessage("§l§f[§9 Hệ Thống§f ]§c Bạn không đủ §bPoint§e để mua pet này!!");
        }
     }
   }
   
   if($data[0] == 11){
         if(!$sender->sendMessage("§l§f[§9 Hệ Thống§f ]§e Bạn đã mua thành công pet §bGhast§e với giá §b50§c Point!")){
             $name = $sender->getName();
             $point = $this->pointapi->myPoint($sender);
             $ghast = 50;
          if($point >= $ghast){
         	$this->getServer()->dispatchCommand(new ConsoleCommandSender, "setuperm ". $name. " blockpets.command.changepetname.use");
         	$this->getServer()->dispatchCommand(new ConsoleCommandSender, "setuperm ". $name. " blockpets.command.togglepet");
             $this->getServer()->dispatchCommand(new ConsoleCommandSender, "spawnpet ghast $data[1] 1 baby ". $name);
             $this->pointapi->reducePoint($name, $ghast);
            return true;
        }else{
            $sender->sendMessage("§l§f[§9 Hệ Thống§f ]§c Bạn không đủ §bPoint§e để mua pet này!!");
        }
     }
   }

   if($data[0] == 12){
         if(!$sender->sendMessage("§l§f[§9 Hệ Thống§f ]§e Bạn đã mua thành công pet §bHorse§e với giá §b50§c Point!")){
             $name = $sender->getName();
             $point = $this->pointapi->myPoint($sender);
             $horse = 50;
          if($point >= $horse){
         	$this->getServer()->dispatchCommand(new ConsoleCommandSender, "setuperm ". $name. " blockpets.command.changepetname.use");
         	$this->getServer()->dispatchCommand(new ConsoleCommandSender, "setuperm ". $name. " blockpets.command.togglepet");
             $this->getServer()->dispatchCommand(new ConsoleCommandSender, "spawnpet horse $data[1] 1 baby ". $name);
             $this->pointapi->reducePoint($name, $horse);
            return true;
        }else{
            $sender->sendMessage("§l§f[§9 Hệ Thống§f ]§c Bạn không đủ §bPoint§e để mua pet này!!");
        }
     }
   }

   if($data[0] == 13){
         if(!$sender->sendMessage("§l§f[§9 Hệ Thống§f ]§e Bạn đã mua thành công pet §bHusk§e với giá §b50§c Point!")){
             $name = $sender->getName();
             $point = $this->pointapi->myPoint($sender);
             $husk = 50;
          if($point >= $husk){
         	$this->getServer()->dispatchCommand(new ConsoleCommandSender, "setuperm ". $name. " blockpets.command.changepetname.use");
         	$this->getServer()->dispatchCommand(new ConsoleCommandSender, "setuperm ". $name. " blockpets.command.togglepet");
             $this->getServer()->dispatchCommand(new ConsoleCommandSender, "spawnpet husk $data[1] 1 baby ". $name);
             $this->pointapi->reducePoint($name, $husk);
            return true;
        }else{
            $sender->sendMessage("§l§f[§9 Hệ Thống§f ]§c Bạn không đủ §bPoint§e để mua pet này!!");
        }
     }
   }

   if($data[0] == 14){
         if(!$sender->sendMessage("§l§f[§9 Hệ Thống§f ]§e Bạn đã mua thành công pet §bIron Golem§e với giá §b50§c Point!")){
             $name = $sender->getName();
             $point = $this->pointapi->myPoint($sender);
             $irongolem = 50;
          if($point >= $irongolem){
         	$this->getServer()->dispatchCommand(new ConsoleCommandSender, "setuperm ". $name. " blockpets.command.changepetname.use");
         	$this->getServer()->dispatchCommand(new ConsoleCommandSender, "setuperm ". $name. " blockpets.command.togglepet");
             $this->getServer()->dispatchCommand(new ConsoleCommandSender, "spawnpet irongolem $data[1] 1 baby ". $name);
             $this->pointapi->reducePoint($name, $irongolem);
            return true;
        }else{
            $sender->sendMessage("§l§f[§9 Hệ Thống§f ]§c Bạn không đủ §bPoint§e để mua pet này!!");
        }
     }
   }

   if($data[0] == 15){
         if(!$sender->sendMessage("§l§f[§9 Hệ Thống§f ]§e Bạn đã mua thành công pet §bLlama§e với giá §b50§c Point!")){
             $name = $sender->getName();
             $point = $this->pointapi->myPoint($sender);
             $llama = 50;
          if($point >= $llama){
         	$this->getServer()->dispatchCommand(new ConsoleCommandSender, "setuperm ". $name. " blockpets.command.changepetname.use");
         	$this->getServer()->dispatchCommand(new ConsoleCommandSender, "setuperm ". $name. " blockpets.command.togglepet");
             $this->getServer()->dispatchCommand(new ConsoleCommandSender, "spawnpet llama $data[1] 1 baby ". $name);
             $this->pointapi->reducePoint($name, $llama);
            return true;
        }else{
            $sender->sendMessage("§l§f[§9 Hệ Thống§f ]§c Bạn không đủ §bPoint§e để mua pet này!!");
        }
     }
   }

   if($data[0] == 16){
         if(!$sender->sendMessage("§l§f[§9 Hệ Thống§f ]§e Bạn đã mua thành công pet §bMoosh Room§e với giá §b50§c Point!")){
             $name = $sender->getName();
             $point = $this->pointapi->myPoint($sender);
             $moshroom = 50;
          if($point >= $moshroom){
         	$this->getServer()->dispatchCommand(new ConsoleCommandSender, "setuperm ". $name. " blockpets.command.changepetname.use");
         	$this->getServer()->dispatchCommand(new ConsoleCommandSender, "setuperm ". $name. " blockpets.command.togglepet");
             $this->getServer()->dispatchCommand(new ConsoleCommandSender, "spawnpet moshroom $data[1] 1 baby ". $name);
             $this->pointapi->reducePoint($name, $moshroom);
            return true;
        }else{
            $sender->sendMessage("§l§f[§9 Hệ Thống§f ]§c Bạn không đủ §bPoint§e để mua pet này!!");
        }
     }
   }

  if($data[0] == 17){
         if(!$sender->sendMessage("§l§f[§9 Hệ Thống§f ]§e Bạn đã mua thành công pet §bPanda§e với giá §b50§c Point!")){
             $name = $sender->getName();
             $point = $this->pointapi->myPoint($sender);
             $panda = 75;
          if($point >= $panda){
         	$this->getServer()->dispatchCommand(new ConsoleCommandSender, "setuperm ". $name. " blockpets.command.changepetname.use");
         	$this->getServer()->dispatchCommand(new ConsoleCommandSender, "setuperm ". $name. " blockpets.command.togglepet");
             $this->getServer()->dispatchCommand(new ConsoleCommandSender, "spawnpet panda $data[1] 1 baby ". $name);
             $this->pointapi->reducePoint($name, $panda);
            return true;
        }else{
            $sender->sendMessage("§l§f[§9 Hệ Thống§f ]§c Bạn không đủ §bPoint§e để mua pet này!!");
        }
     }
   }
 
   if($data[0] == 18){
         if(!$sender->sendMessage("§l§f[§9 Hệ Thống§f ]§e Bạn đã mua thành công pet §bPig§e với giá §b50§c Point!")){
             $name = $sender->getName();
             $point = $this->pointapi->myPoint($sender);
             $pig = 50;
          if($point >= $pig){
         	$this->getServer()->dispatchCommand(new ConsoleCommandSender, "setuperm ". $name. " blockpets.command.changepetname.use");
         	$this->getServer()->dispatchCommand(new ConsoleCommandSender, "setuperm ". $name. " blockpets.command.togglepet");
             $this->getServer()->dispatchCommand(new ConsoleCommandSender, "spawnpet pig $data[1] 1 baby ". $name);
             $this->pointapi->reducePoint($name, $pig);
            return true;
        }else{
            $sender->sendMessage("§l§f[§9 Hệ Thống§f ]§c Bạn không đủ §bPoint§e để mua pet này!!");
        }
     }
   }
 
   if($data[0] == 19){
         if(!$sender->sendMessage("§l§f[§9 Hệ Thống§f ]§e Bạn đã mua thành công pet §bPolar Bear§e với giá §b50§c Point!")){
             $name = $sender->getName();
             $point = $this->pointapi->myPoint($sender);
             $polarbear = 50;
          if($point >= $polarbear){
         	$this->getServer()->dispatchCommand(new ConsoleCommandSender, "setuperm ". $name. " blockpets.command.changepetname.use");
         	$this->getServer()->dispatchCommand(new ConsoleCommandSender, "setuperm ". $name. " blockpets.command.togglepet");
             $this->getServer()->dispatchCommand(new ConsoleCommandSender, "spawnpet polarbear $data[1] 1 baby ". $name);
             $this->pointapi->reducePoint($name, $polarbear);
            return true;
        }else{
            $sender->sendMessage("§l§f[§9 Hệ Thống§f ]§c Bạn không đủ §bPoint§e để mua pet này!!");
        }
     }
   }

  if($data[0] == 20){
         if(!$sender->sendMessage("§l§f[§9 Hệ Thống§f ]§e Bạn đã mua thành công pet §bRabbit§e với giá §b50§c Point!")){
             $name = $sender->getName();
             $point = $this->pointapi->myPoint($sender);
             $rabbit = 50;
          if($point >= $wolf){
         	$this->getServer()->dispatchCommand(new ConsoleCommandSender, "setuperm ". $name. " blockpets.command.changepetname.use");
         	$this->getServer()->dispatchCommand(new ConsoleCommandSender, "setuperm ". $name. " blockpets.command.togglepet");
             $this->getServer()->dispatchCommand(new ConsoleCommandSender, "spawnpet rabbit $data[1] 1 baby ". $name);
             $this->pointapi->reducePoint($name, $rabbit);
            return true;
        }else{
            $sender->sendMessage("§l§f[§9 Hệ Thống§f ]§c Bạn không đủ §bPoint§e để mua pet này!!");
        }
     }
   }
   if($data[0] == 21){
         if(!$sender->sendMessage("§l§f[§9 Hệ Thống§f ]§e Bạn đã mua thành công pet §bSheep§e với giá §b50§c Point!")){
             $name = $sender->getName();
             $point = $this->pointapi->myPoint($sender);
             $sheep = 50;
          if($point >= $sheep){
         	$this->getServer()->dispatchCommand(new ConsoleCommandSender, "setuperm ". $name. " blockpets.command.changepetname.use");
         	$this->getServer()->dispatchCommand(new ConsoleCommandSender, "setuperm ". $name. " blockpets.command.togglepet");
             $this->getServer()->dispatchCommand(new ConsoleCommandSender, "spawnpet sheep $data[1] 1 baby ". $name);
             $this->pointapi->reducePoint($name, $sheep);
            return true;
        }else{
            $sender->sendMessage("§l§f[§9 Hệ Thống§f ]§c Bạn không đủ §bPoint§e để mua pet này!!");
        }
     }
   }
   
   if($data[0] == 22){
         if(!$sender->sendMessage("§l§f[§9 Hệ Thống§f ]§e Bạn đã mua thành công pet §bSkeleton§e với giá §b50§c Point!")){
             $name = $sender->getName();
             $point = $this->pointapi->myPoint($sender);
             $skeleton = 50;
          if($point >= $skeleton){
         	$this->getServer()->dispatchCommand(new ConsoleCommandSender, "setuperm ". $name. " blockpets.command.changepetname.use");
         	$this->getServer()->dispatchCommand(new ConsoleCommandSender, "setuperm ". $name. " blockpets.command.togglepet");
             $this->getServer()->dispatchCommand(new ConsoleCommandSender, "spawnpet skeleton $data[1] 1 baby ". $name);
             $this->pointapi->reducePoint($name, $skeleton);
            return true;
        }else{
            $sender->sendMessage("§l§f[§9 Hệ Thống§f ]§c Bạn không đủ §bPoint§e để mua pet này!!");
        }
     }
   }
  
   if($data[0] == 23){
         if(!$sender->sendMessage("§l§f[§9 Hệ Thống§f ]§e Bạn đã mua thành công pet §bSnow Golem§e với giá §b50§c Point!")){
             $name = $sender->getName();
             $point = $this->pointapi->myPoint($sender);
             $snowgolem = 50;
          if($point >= $snowgolem){
         	$this->getServer()->dispatchCommand(new ConsoleCommandSender, "setuperm ". $name. " blockpets.command.changepetname.use");
         	$this->getServer()->dispatchCommand(new ConsoleCommandSender, "setuperm ". $name. " blockpets.command.togglepet");
             $this->getServer()->dispatchCommand(new ConsoleCommandSender, "spawnpet snowgolem $data[1] 1 baby ". $name);
             $this->pointapi->reducePoint($name, $snowgolem);
            return true;
        }else{
            $sender->sendMessage("§l§f[§9 Hệ Thống§f ]§c Bạn không đủ §bPoint§e để mua pet này!!");
        }
     }
   }
   
   if($data[0] == 24){
         if(!$sender->sendMessage("§l§f[§9 Hệ Thống§f ]§e Bạn đã mua thành công pet §bSpider§e với giá §b50§c Point!")){
             $name = $sender->getName();
             $point = $this->pointapi->myPoint($sender);
             $spider = 50;
          if($point >= $spider){
         	$this->getServer()->dispatchCommand(new ConsoleCommandSender, "setuperm ". $name. " blockpets.command.changepetname.use");
         	$this->getServer()->dispatchCommand(new ConsoleCommandSender, "setuperm ". $name. " blockpets.command.togglepet");
             $this->getServer()->dispatchCommand(new ConsoleCommandSender, "spawnpet spider $data[1] 1 baby ". $name);
             $this->pointapi->reducePoint($name, $spider);
            return true;
        }else{
            $sender->sendMessage("§l§f[§9 Hệ Thống§f ]§c Bạn không đủ §bPoint§e để mua pet này!!");
        }
     }
   }
   
   if($data[0] == 25){
         if(!$sender->sendMessage("§l§f[§9 Hệ Thống§f ]§e Bạn đã mua thành công pet §bVex§e với giá §b50§c Point!")){
             $name = $sender->getName();
             $point = $this->pointapi->myPoint($sender);
             $vex = 50;
          if($point >= $vex){
         	$this->getServer()->dispatchCommand(new ConsoleCommandSender, "setuperm ". $name. " blockpets.command.changepetname.use");
         	$this->getServer()->dispatchCommand(new ConsoleCommandSender, "setuperm ". $name. " blockpets.command.togglepet");
             $this->getServer()->dispatchCommand(new ConsoleCommandSender, "spawnpet vex $data[1] 1 baby ". $name);
             $this->pointapi->reducePoint($name, $vex);
            return true;
        }else{
            $sender->sendMessage("§l§f[§9 Hệ Thống§f ]§c Bạn không đủ §bPoint§e để mua pet này!!");
        }
     }
   }

   if($data[0] == 26){
         if(!$sender->sendMessage("§l§f[§9 Hệ Thống§f ]§e Bạn đã mua thành công pet §bVillage§e với giá §b50§c Point!")){
             $name = $sender->getName();
             $point = $this->poitapi->myPoint($sender);
             $village = 50;
          if($point >= $village){
         	$this->getServer()->dispatchCommand(new ConsoleCommandSender, "setuperm ". $name. " blockpets.command.changepetname.use");
         	$this->getServer()->dispatchCommand(new ConsoleCommandSender, "setuperm ". $name. " blockpets.command.togglepet");
             $this->getServer()->dispatchCommand(new ConsoleCommandSender, "spawnpet village $data[1] 1 baby ". $name);
             $this->pointapi->reducePoint($name, $village);
            return true;
        }else{
            $sender->sendMessage("§l§f[§9 Hệ Thống§f ]§c Bạn không đủ §bPoint§e để mua pet này!!");
        }
     }
   }
   
   if($data[0] == 27){
         if(!$sender->sendMessage("§l§f[§9 Hệ Thống§f ]§e Bạn đã mua thành công pet §bWitch§e với giá §b50§c Point!")){
             $name = $sender->getName();
             $point = $this->pointapi->myPoint($sender);
             $witch = 50;
          if($point >= $witch){
         	$this->getServer()->dispatchCommand(new ConsoleCommandSender, "setuperm ". $name. " blockpets.command.changepetname.use");
         	$this->getServer()->dispatchCommand(new ConsoleCommandSender, "setuperm ". $name. " blockpets.command.togglepet");
             $this->getServer()->dispatchCommand(new ConsoleCommandSender, "spawnpet witch $data[1] 1 baby ". $name);
             $this->pointapi->reducePoint($name, $witch);
            return true;
        }else{
            $sender->sendMessage("§l§f[§9 Hệ Thống§f ]§c Bạn không đủ §bPoint§e để mua pet này!!");
        }
     }
   }

   if($data[0] == 28){
         if(!$sender->sendMessage("§l§f[§9 Hệ Thống§f ]§e Bạn đã mua thành công pet §bWither§e với giá §b50§c Point!")){
             $name = $sender->getName();
             $point = $this->pointapi->myPoint($sender);
             $wither = 50;
          if($point >= $wither){
         	$this->getServer()->dispatchCommand(new ConsoleCommandSender, "setuperm ". $name. " blockpets.command.changepetname.use");
         	$this->getServer()->dispatchCommand(new ConsoleCommandSender, "setuperm ". $name. " blockpets.command.togglepet");
             $this->getServer()->dispatchCommand(new ConsoleCommandSender, "spawnpet wither $data[1] 1 baby ". $name);
             $this->pointapi->reducePoint($name, $wither);
            return true;
        }else{
            $sender->sendMessage("§l§f[§9 Hệ Thống§f ]§c Bạn không đủ §bPoint§e để mua pet này!!");
        }
     }
   }
 
   if($data[0] == 29){
         if(!$sender->sendMessage("§l§f[§9 Hệ Thống§f ]§e Bạn đã mua thành công pet §bZombie§e với giá §b50§c Point!")){
             $name = $sender->getName();
             $point = $this->pointapi->myPoint($sender);
             $zombie = 50;
          if($point >= $zombie){
         	$this->getServer()->dispatchCommand(new ConsoleCommandSender, "setuperm ". $name. " blockpets.command.changepetname.use");
         	$this->getServer()->dispatchCommand(new ConsoleCommandSender, "setuperm ". $name. " blockpets.command.togglepet");
             $this->getServer()->dispatchCommand(new ConsoleCommandSender, "spawnpet zombie $data[1] 1 baby ". $name);
             $this->pointapi->reducePoint($name, $zombie);
            return true;
        }else{
            $sender->sendMessage("§l§f[§9 Hệ Thống§f ]§c Bạn không đủ §bPoint§e để mua pet này!!");
        }
     }
   }

  if($data[0] == 30){
         if(!$sender->sendMessage("§l§f[§9 Hệ Thống§f ]§e Bạn đã mua thành công pet §bZombie Villager§e với giá §b50§c Point!")){
             $name = $sender->getName();
             $point = $this->pointapi->myPoint($sender);
             $zombievillager = 50;
          if($point >= $zombievillager){
         	$this->getServer()->dispatchCommand(new ConsoleCommandSender, "setuperm ". $name. " blockpets.command.changepetname.use");
         	$this->getServer()->dispatchCommand(new ConsoleCommandSender, "setuperm ". $name. " blockpets.command.togglepet");
             $this->getServer()->dispatchCommand(new ConsoleCommandSender, "spawnpet zombievillager $data[1] 1 baby ". $name);
             $this->pointapi->reducePoint($name, $zombievillager);
            return true;
        }else{
            $sender->sendMessage("§l§f[§9 Hệ Thống§f ]§c Bạn không đủ §bPoint§e để mua pet này!!");
        }
     }
   }

  if($data[0] == 31){
         if(!$sender->sendMessage("§l§f[§9 Hệ Thống§f ]§e Bạn đã mua thành công pet §bZombie PigMan§e với giá §b50§c Point!")){
             $name = $sender->getName();
             $point = $this->pointapi->myPoint($sender);
             $zombiepigman = 50;
          if($point >= $zombiepigman){
         	$this->getServer()->dispatchCommand(new ConsoleCommandSender, "setuperm ". $name. " blockpets.command.changepetname.use");
         	$this->getServer()->dispatchCommand(new ConsoleCommandSender, "setuperm ". $name. " blockpets.command.togglepet");
             $this->getServer()->dispatchCommand(new ConsoleCommandSender, "spawnpet zombiepigman $data[1] 1 baby ". $name);
             $this->pointapi->reducePoint($name, $zombiepigman);
            return true;
        }else{
            $sender->sendMessage("§l§f[§9 Hệ Thống§f ]§c Bạn không đủ §bPoint§e để mua pet này!!");
        }
     }
   }

   if($data[0] == 32){
         if(!$sender->sendMessage("§l§f[§9 Hệ Thống§f ]§e Bạn đã mua thành công pet §bAxolotl§e với giá §b50§c Point!")){
             $name = $sender->getName();
             $point = $this->pointapi->myPoint($sender);
             $axolotl = 75;
          if($point >= $axolotl){
         	$this->getServer()->dispatchCommand(new ConsoleCommandSender, "setuperm ". $name. " blockpets.command.changepetname.use");
         	$this->getServer()->dispatchCommand(new ConsoleCommandSender, "setuperm ". $name. " blockpets.command.togglepet");
             $this->getServer()->dispatchCommand(new ConsoleCommandSender, "spawnpet axolotl $data[1] 1 baby ". $name);
             $this->pointapi->reducePoint($name, $axolotl);
            return true;
        }else{
            $sender->sendMessage("§l§f[§9 Hệ Thống§f ]§c Bạn không đủ §bPoint§e để mua pet này!!");
        }
     }
   }
 });
/**
*GIÁ:——————————————————————————
*/
  $wolf = 50;
  $ocelot = 50;
  $blaze = 50;
  $cspider = 50;
  $chicken = 50;
  $cow = 50;
  $donkey = 50;
  $dragon = 100;
  $enderman = 50;
  $fox = 75;
  $ghast = 50;
  $horse = 50;
  $husk = 50;
  $irongolem = 50;
  $llama = 50;
  $moshroom = 50;
  $panda = 75;
  $pig = 50;
  $polarbear = 50;
  $rabbit = 50;
  $sheep = 50;
  $skeleton = 50;
  $snowgolem = 50;
  $spider = 50;
  $vex = 50;
  $villager = 50;
  $witch = 50;
  $wither = 100;
  $zombie = 50;
  $zombievillager = 50;
  $zombiepigman = 50;
  $axolotl = 75;
/**
*Menu shop pet————————————————————————
*/
  $form->setTitle("§l§a«§c SHOP§c PET§a »");
  $name = $sender->getName();
  $point = $this->pointapi->myPoint($sender);
  $form->addDropdown("§l§eXin chào §b". $name. "\n§l§eSố Point Của Bạn §b" .$point. "\n§l§fVui lòng chọn pet mà bạn muốn mua: ", [
  "§l§a• §cQuay Lại §a•",
  "§l§a• §eDog §9Giá§b ". $wolf. " §l§cPoint§a •",
  "§l§a• §eCat §9Giá§b ". $ocelot. " §l§cPoint§a •",
  "§l§a• §eBlaze §9Giá§b ". $blaze. " §l§cPoint§a •",
  "§l§a• §eCave Spider §9Giá§b ". $cspider. " §l§cPoint§a •",
  "§l§a• §eChicken §9Giá§b ". $chicken. " §l§cPoint§a •",
  "§l§a• §eCow §9Giá§b ". $cow. " §l§cPoint§a •",
  "§l§a• §eDonkey §9Giá§b ". $donkey. " §l§cPoint§a •",
  "§l§a• §eDragon §9Giá§b ". $dragon. " §l§cPoint§a •",
  "§l§a• §eEnderMan §9Giá§b ". $enderman. " §l§cPoint§a •",
  "§l§a• §eFox §9Giá§b ". $fox. " §l§cPoint§a •",
  "§l§a• §eGhast §9Giá§b ". $ghast. " §l§cPoint§a •",
  "§l§a• §eHorse §9Giá§b ". $horse. " §l§cPoint§a •",
  "§l§a• §eHusk §9Giá§b ". $husk. " §l§cPoint§a •",
  "§l§a• §eIron Golem §9Giá§b ". $irongolem. " §l§cPoint§a •",
  "§l§a• §eLlama §9Giá§b ". $llama. " §l§cPoint§a •",
  "§l§a• §eMoosh Room §9Giá§b ". $moshroom. " §l§cPoint§a •",
  "§l§a• §ePanda §9Giá§b ". $panda. " §l§cPoint§a •",
  "§l§a• §ePig §9Giá§b ". $pig. " §l§cPoint§a •",
  "§l§a• §ePolar Bear §9Giá§b ". $polarbear. " §l§cPoint§a •",
  "§l§a• §eRabbit §9Giá§b ". $rabbit. " §l§cPoint§a •",
  "§l§a• §eSheep §9Giá§b ". $sheep. " §l§cPoint§a •",
  "§l§a• §eSkeleton §9Giá§b ". $skeleton. " §l§cPoint§a •",
  "§l§a• §eSnow Golem §9Giá§b ". $snowgolem. " §l§cPoint§a •",
  "§l§a• §eSpider §9Giá§b ". $spider. " §l§cPoint§a •",
  "§l§a• §eVex §9Giá§b ". $vex. " §l§cPoint§a •",
  "§l§a• §eVillager §9Giá§b ". $villager. " §l§cPoint§a •",
  "§l§a• §eWitch §9Giá§b ". $wolf. " §l§cPoint§a •",
  "§l§a• §eWither §9Giá§b ". $wolf. " §l§cPoint§a •",
  "§l§a• §eZombie §9Giá§b ". $zombie. " §l§cPoint§a •",
  "§l§a• §eZombie Villager §9Giá§b ". $zombievillager. " §l§cPoint§a •",
  "§l§a• §eZombie PigMan §9Giá§b ". $zombiepigman. " §l§cPoint§a •",
  "§l§a• §eAxolotl §9Giá§b ". $wolf. " §l§cPoint§a •"
  ]);
  $form->addInput("§o§eNhập tên pet của bạn vô đây");
  $form->sendToPlayer($sender);
 }
 
/**
*Quản Lí PET:—————————————————————————
*/
   public function quanliPet($sender){
     $form = new SimpleForm(function (Player $sender, $data){
      if($data === null){
        return true;
      }
      switch($data){
        case 0:
        $this->menuUI($sender);
        break;
        case 1:
        $this->changeNamePet($sender);
        break;
        case 2:
        $this->togglePet($sender);
        break;
       case 3:
       $this->levelUP($sender);
       break;
    }
   });
   $form->setTitle("§l§a« §eQUẢN LÍ PET§a »");
   $form->addButton("§l§a• §cQUAY LẠI§a •");
   $form->addButton("§l§b➼ §aĐỔI TÊN PET");
   $form->addButton("§l§b➼ §aTẮT§f/§aBẬT PET");
   $form->addButton("§l§b➼ §aNÂNG CẤP PET");
   $form->sendToPlayer($sender);
   return $form;
  }
/**
*ĐỔI TÊN CHO PET:————————————————————
*/
  public function changeNamePet($sender){
   $form = new CustomForm (function (Player $sender, $data){
    $result = $data;
    if($result == null){
  	$this->menuUI($sender);
    return true;
    }
    if(!$sender->sendMessage("§l§f[§9 HỆ THỐNG§f ]§e Bạn đã đổi tên pet thành công!")){
     $money = $this->money->myMoney($sender);
     $cost = 100000;
     if($money >= $cost){
     	$this->money->reduceMoney($sender, $cost);
         $cmd = "changepetname $data[0] $data[1]";
         $this->getServer()->getCommandMap()->dispatch($sender, $cmd);
         return true;
       }else{
       	$sender->sendMessage("§l§f[§9 HỆ THỐNG§f ]§c Bạn không có đủ tiền để đổi tên cho pet");
      }
    }
 });
 $form->setTitle("§l§a«§b ĐỔI TÊN PET§a »");
 $form->addInput("§l§eNhập tên cũ vô đây", "§oVí dụ: Ahihi");
 $form->addInput("§l§eNhập tên mới vô đây", "§oVí dụ: ItachiDoSatCaGiaToc");
 $form->sendToPlayer($sender);
  }        

/**
*TẮT BẬT PET:——————————————————————————
*/
   
   public function  togglePet($sender){
    $form = new CustomForm (function (Player $sender, $data){
    if($data == null){
    	$this->menuUI($sender);
        return true;
      }
      $cmd = "togglepet $data[0]";
      $this->getServer()->getCommandMap()->dispatch($sender, $cmd);
      });
      $form->setTitle("§l§a« §eTẮT §b/§e BẬT PET§a »");
      $form->addInput("§l§eNhập tên pet mà bạn muốn tắt / bật vào ô dưới đây", "§oVí dụ: KokomiDaiNgonTungSoi");
      $form->addToggle("§l§eTẮT§b / §eBẬT");
      $form->sendToPlayer($sender);
      }
/**
*NÂNG CẤP PET:————————————————————
*/
    public function levelUP($sender){
     $form = new CustomForm(function (Player $sender, $data){
      if($data === null){
      	$this->menuUI($sender);
       return true;
      }
      $point = $this->pointapi->myPoint($sender);
      if($point >= 5){
       $this->pointapi->reducePoint($sender, $point);
       $cmd = "leveluppet ". $data[0]. " 1 ". $sender->getName();
       $this->getServer()->dispatchCommand(new CommandSender, $cmd);
      $sender->sendMessage("§l§f[§9 HỆ THỐNG§f]§e Bạn đã nâng cấp pet thành công!");
      }else{
      $sender->sendMessage("§l§f[§9 HỆ THỐNG§f ]§c Bạn không đủ point để nâng cấp. Vui lòng thử lại sau!");
      }
    });
    $form->setTitle("§l§a« §bNÂP CẤP PET§a »");
    $form->addInput("§l§eNhập tên pet mà bạn muốn nâng cấp vào ô dưới đây", "§oVí dụ: AnhNhaODauThe");
    $form->sendToPlayer($sender);
  }
/**
*MENU PHỤ KIỆN:———————————————
*/
     
     public function menuPhuKien($sender){
      $form = new SimpleForm(function (Player $sender, $data){
   	$result = $data;
        if($result == null){
           $this->menuUI($sender);
           return true;
       }
       switch($result){
         case 0:
         $this->menuUI($sender);
         break;
         case 1:
         $this->muaThucAn($sender);
         break;
         case 2:
         $this->muaYenCuoi($sender);
         break;
     }
   });
   $form->setTitle("§l§a«§a MENU PHỤ KIỆN§a »");
   $form->addButton("§l§cTHOÁT");
   $form->addButton("§l§a• §eThức Ăn §a•\n§l§a«§b Bấm Vào Để Mua§a »");
   $form->addButton("§l§a• §eYên Cưỡi§a •\n§l§a« §bBấm Vào Để Mua§a »");
   $form->sendToPlayer($sender);
   }
   
/**
*MUA THỨC ĂN:———————————————————
*/
   public function muaThucAn($sender){
    $form = new CustomForm(function( Player $sender, $data){
     if($data == null){
     	$this->menuPhuKien($sender);
        return true;
     }
        $data[1] >= 1;
        $money = $this->money->myMoney($sender);
        $name = $sender->getName();
     if($money >= $data[1]*150){
     	$this->getServer()->dispatchCommand(new ConsoleCommandSender, "give ". $name. " steak ". $data[1]);
         $this->money->reduceMoney($name, $data[1]*150);
         $sender->sendMessage("§l§f[§9 HỆ THỐNG §f] §eBạn đã mua thành công§c ". $data[1]. " §ethức ăn");
      } else { 
      	$sender->sendMessage("§l§f[§9 HỆ THỐNG§f ]§c Bạn không đủ tiền mua thức ăn!");
         return true;
      }
  });
  $name = $sender->getName();
  $money = $this->money->myMoney($sender);
  $form->setTitle("§l§a« §cTHỨC ĂN§a »");
  $form->addLabel("§l§eXin chào ". $name. "\n§l§eSố tiền của bạn là: §b" .$money. "\n§l§eBạn có chắc muốn mua thức ăn cho pet không?\n§l§eMỗi thức ăn 150 money!");
  $form->addInput("§oVui lòng nhập số lượng vào đây!");
  $form->sendToPlayer($sender);
   }
   
/**
*MUA YÊN CƯỡI:————————————————————————
*/
  public function muaYenCuoi($sender){
   $form = new SimpleForm( function( Player $sender, $data){
    $result = $data;
   if($result == null){
   	$this->menuPhuKien($sender);
      return true;
      }
      switch($result){
      	case 0:
          $this->menuPhuKien($sender);
          break;
          case 1:
          $money = $this->money->myMoney($sender);
          $name = $sender->getName();
          $cost = 100000;
          if($money >= $cost){
          	$item = Item::get(329, 0, 1);
              $item->setCustomName("§l§aYên Cưỡi");
              $sender->getInventory()->addItem($item);
              $sender->sendMessage("§l§f[§9 HỆ THỐNG§f ]§e Bạn đã mua thành công yên cưỡi, vui lòng kiểm tra lại túi đồ!");
          } else {
          	$sender->sendMessage("§l§f[§9 HỆ THỐNG§f ]§c Bạn không đủ tiền mua yên cưỡi, vui lòng quay lại sau!");
          }
         }
       });
       $money = $this->money->myMoney($sender);
       $name = $sender->getName();
       $form->setTitle("§l§a« Mua Yên Cưỡi »");
       $form->setContent("§l§eXin chào§b ". $name. "\n§eBạn có muốn mua yên cưỡi với giá 100k money không?");
       $form->addButton("§l§a• §cKhông§a •\n§l§a«§b Cho tôi quay lại§a »");
       $form->addButton("§l§a• §eCó§a •\n§l§f(§c không thể hoàn trả khi xác nhận§f )");
       $form->sendToPlayer($sender);
    }
}