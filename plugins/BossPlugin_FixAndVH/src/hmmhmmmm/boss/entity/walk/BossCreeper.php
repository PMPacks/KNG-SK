<?php

namespace hmmhmmmm\boss\entity\walk;

use revivalpmmp\pureentities\entity\monster\walking\Creeper;
use slapper\entities\SlapperHuman;
use hmmhmmmm\boss\BossData;
use hmmhmmmm\boss\BossManager;

use pocketmine\Player;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\entity\Creature;

class BossCreeper extends Creeper{
   public $health = 20;
   public $boss_data = "0xAAA001";
  
   public function getName(): string{
      $name = $this->boss_data;
      if(BossData::isBoss($name)){
         return "BossCreeper";
      }else{
         return parent::getName();
      }
   }

   public function explode(){
      parent::explode();
      $name = $this->boss_data;
      if(BossData::isBoss($name)){
         BossData::setRespawnTime($name, 2);
      }
   }
   
   public function getMaxHealth(): int{
      $name = $this->boss_data;
      if(BossData::isBoss($name)){
         return BossData::getHealth($name);
      }else{
         return $this->health;
      }
   }
   
       public function entityBaseTick(int $tickDiff = 1): bool{
        $hasUpdate = parent::entityBaseTick($tickDiff);
        $name = $this->boss_data;
        if(BossData::isBoss($name)){
            $max = $this->getMaxHealth();         $hp = $this->getHealth();
            $hps= ""; 
            for($i = 1; $i <=10;$i++){
                $check = $max * ($i * 10) / 100; 
                if($i == 1){
                   $hps .= "§a▃";
                }else{
                    if($hp >= $check){
                       $hps .= "§a▃";
                    }else{
                        $hps .= "§c▃"; 
                    }
                }
               
            }
            $this->setNameTag($name."\n$hps");
            $this->setNameTagAlwaysVisible(true);
            $this->setNameTagVisible(true);
            if($this->isOnFire()){
                $this->extinguish();
            }
            return $hasUpdate;
        }else{
            return parent::entityBaseTick($tickDiff);
        }
    }
   
   public function targetOption(Creature $creature, float $distance) : bool{
      if(!($creature instanceof SlapperHuman)){
         if($creature instanceof Player){
            return parent::targetOption($creature, $distance);
         }
      }
      return false;
   }
   
}