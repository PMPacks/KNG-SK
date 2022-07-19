<?php

namespace moch\TopMoneys;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\math\Vector3;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\Player;

class Main extends PluginBase{

    private $particles = [];

    public function onEnable(){
     $this->config = (new Config($this->getDataFolder()."config.yml", Config::YAML))->getAll();
     if(empty($this->config["positions"])){
      $this->getServer()->getLogger()->Info("Harap Set Lokasi Top Money");
      return;
     }
     $pos = $this->config["positions"];
     $this->particles[] = new FloatingText($this, new Vector3($pos[0], $pos[1], $pos[2]));
     $this->getScheduler()->scheduleRepeatingTask(new UpdateTask($this), 100);
     $this->getServer()->getLogger()->Info("Lokasi Top Money Berhasil Diload");
    }

    public function onCommand(CommandSender $p, Command $command, string $label, array $args):bool{
     if($command->getName() === "settopxu"){
      if(!$p instanceof Player) return false;
      if(!$p->isOp()) return false;
      $config = new Config($this->getDataFolder()."config.yml", Config::YAML);
      $config->set("positions", [round($p->getX()), round($p->getY()), round($p->getZ())]);
      $config->save();
      $p->sendMessage("§a* §oBerhasil menentukan lokasi daftar Top Moneys§r§f!");
     }
     return true;
    }

    public function getLeaderBoard():string{
     $data = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
     $money_top = $data->getAllMoney();
     $message = "";
     $topmoney = "§l§a❖§eϯℴ℘ §c✘ມ§a❖\n";
     if(count($money_top) > 0){
      arsort($money_top);
      $i = 0;
      foreach($money_top as $name => $money){
       $message .= "§l§a".($i+1).". §f".$name." §e".$money." §a$"."\n";
       if($i >= 10){
        break;
       }
       ++$i;
      }
     }
     $return = (string) $topmoney.$message;
     return $return;
    }

    public function getParticles():array{
     return $this->particles;
    }

}