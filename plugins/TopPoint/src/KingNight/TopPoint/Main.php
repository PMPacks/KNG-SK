<?php

namespace KingNight\TopPoint;

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
      $this->getServer()->getLogger()->Info("Vui lòng đặt một vị trí cho TopPonit");
      return;
     }
     $pos = $this->config["positions"];
     $this->particles[] = new FloatingText($this, new Vector3($pos[0], $pos[1], $pos[2]));
     $this->getScheduler()->scheduleRepeatingTask(new UpdateTask($this), 100);
     $this->getServer()->getLogger()->Info("Vị Trí TopPoint Ở Trên Cao Đặt Thành Công");
    }

    public function onCommand(CommandSender $p, Command $command, string $label, array $args):bool{
     if($command->getName() === "settoppoint"){
      if(!$p instanceof Player) return false;
      if(!$p->isOp()) return false;
      $config = new Config($this->getDataFolder()."config.yml", Config::YAML);
      $config->set("positions", [round($p->getX()), round($p->getY()), round($p->getZ())]);
      $config->save();
      $p->sendMessage("§l§f[§eKing§bNight§f] §a§oĐã đặt vị trí TopPoint thành công,stop sv để bật TopPoint§c!");
     }
     return true;
    }

    public function getLeaderBoard():string{
     $data = $this->getServer()->getPluginManager()->getPlugin("PointAPI");
     $point_top = $data->getAllPoint();
     $message = "";
     $toppoint = "§l§a❖§cϯℴ℘§e ℘ℴ¡ทϯ§a❖\n";
     if(count($point_top) > 0){
      arsort($point_top);
      $i = 0;
      foreach($point_top as $name => $point){
       $message .= "§l§a".($i+1).". §f".$name." §e".$point." §c$"."\n";
       if($i >= 10){
        break;
       }
       ++$i;
      }
     }
     $return = (string) $toppoint.$message;
     return $return;
    }

    public function getParticles():array{
     return $this->particles;
    }

}