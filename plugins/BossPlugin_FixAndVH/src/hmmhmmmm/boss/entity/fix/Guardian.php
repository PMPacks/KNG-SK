<?php

namespace hmmhmmmm\boss\entity\fix;

use revivalpmmp\pureentities\entity\monster\Monster;
use revivalpmmp\pureentities\data\Data;
use revivalpmmp\pureentities\entity\monster\WalkingMonster;
use slapper\entities\SlapperHuman;
use hmmhmmmm\boss\BossData;

use pocketmine\Player;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\entity\Creature;
use pocketmine\entity\Entity;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;

class Guardian extends WalkingMonster implements Monster{
   const NETWORK_ID = Data::NETWORK_IDS["guardian"];
   public $health = 30;
   public $boss_data = "0xAAA001";
  
   public function getName(): string{
      $name = $this->boss_data;
      if(BossData::isBoss($name)){
         return "BossGuardian";
      }else{
         return "Guardian";
      }
   }

   public function attackEntity(Entity $player){
      if($this->attackDelay > 10 && $this->distanceSquared($player) < 4){
         $this->attackDelay = 0;
         if($player instanceof Player){
            $damage = $this->getDamage();
            $ev = new EntityDamageByEntityEvent($this, $player, EntityDamageEvent::CAUSE_ENTITY_ATTACK, $damage);
            $player->attack($ev);
            $this->checkTamedMobsAttack($player);
         }
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