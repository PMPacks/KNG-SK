<?php

namespace AmGM\LVTM;

use pocketmine\Server;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\utils\TextFormat;
use pocketmine\event\Listener;
use onebone\economyapi\EconomyAPI;
use pocketmine\item\Item;
use pocketmine\event\player\{PlayerInteractEvent, PlayerItemHeldEvent, PlayerJoinEvent, PlayerChatEvent};
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\block\Block;
use pocketmine\utils\Config;
use pocketmine\entity\Effect;
use pocketmine\network\protocol\SetTitlePacket;

class Mine extends PluginBase implements Listener{
    
    public $data;
    private $config;
    	const RET_NOT_FOUND = -1;
    	 	const RET_SUCCESS = 1;
    
    public function onEnable(){
        if(!file_exists($this->getDataFolder() . "LevelToMine/")){
            @mkdir($this->getDataFolder() . "LevelToMine/");
        }
        
        $this->lv = new Config($this->getDataFolder() . "LevelToMine/" . "LevelToMine.yml", Config::YAML);
        $this->saveDefaultConfig();
        $this->config = $this->getConfig();
        $this->config->save();
        $this->getServer()->getPluginManager()->registerEvents($this,$this);
        $this->getLogger()->info("LevelToMine is enabled!");
    }
    
    public function onJoin(PlayerJoinEvent $ev){
        $p = $ev->getPlayer()->getName();
        if(!($this->lv->exists(strtolower($p)))){
            $this->lv->set(strtolower($p), [0,100,1]);
            $this->lv->save();
            return true;
        }
    }
    
    public function onBreak(BlockBreakEvent $ev){
		if(!$ev->isCancelled()){
        $block = $ev->getBlock();
        $p = $ev->getPlayer();
            $n = $this->lv->get(strtolower($p->getName()));
            $name = strtolower($p->getName());
            $n[0] = $this->getLevelExp($p) + 1;
            $this->lv->set(strtolower($p->getName()), $n);
            $this->lv->save();
            if($this->getLevelExp($p) >= $this->getLevelUpExp($p)){
                $n[0] = 0;
                $n[1] = $this->getNextLevelUpExp($p);
                $n[2] = $this->getNextLevel($p);
   //             $nchim = 1;
                $this->lv->set(strtolower($p->getName()), $n);
                $this->lv->save();
				}
    }
    }
    
/*    public function onHeld(PlayerItemHeldEvent $ev){
        
    }
    
    public function onTouch(PlayerInteractEvent $ev){
        
    } 
    */
    public function getNextLevel($player){
        if($player instanceof Player){
            $player = $player->getName();
        }
        
        $player = strtolower($player);
        $lv = $this->lv->get($player)[2] + 1;
        return $lv;
    }
    
    public function getLevelUpExp($player){
        if($player instanceof Player){
            $player = $player->getName();
        }
        
        $player = strtolower($player);
        $e = $this->lv->get($player)[1];
        return $e;
    }
    
    public function getLevel($player){
        if($player instanceof Player){
            $player = $player->getName();
        }
        
        $player = strtolower($player);
        $lv = $this->lv->get($player)[2];
        return $lv;
    }
    
    public function getLevelExp($player){
        if($player instanceof Player){
            $player = $player->getName();
        }
        
        $player = strtolower($player);
        $e = $this->lv->get($player)[0];
        return $e;
    }
    
    public function getNextLevelUpExp($player){
        if($player instanceof Player){
            $player = $player->getName();
        //}
        
        $player = strtolower($player);
        $e = $this->lv->get($player)[1];
        return $e + 100;
    }
}
	public function reduceLevel($player, $amount, $force = false, $issuer = "external"){
		if($amount <= 0 or !is_numeric($amount)){
			return self::RET_INVALID;
		}

		if($player instanceof Player){
			$player = $player->getName();
		}
		$player = strtolower($player);

		$amount = round($amount, 2);
		if(isset($this->level["level"][$player])){
			if($this->level["level"][$player] - $amount < 0){
				return self::RET_INVALID;
			}
			$event = new ReduceLevelEvent($this, $player, $amount, $issuer);
			$this->getServer()->getPluginManager()->callEvent($event);
			if($force === false and $event->isCancelled()){
				return self::RET_CANCELLED;
			}
			$this->level["level"][$player] -= $amount;
			$this->getServer()->getPluginManager()->callEvent(new LevelChangedEvent($this, $player, $this->level["level"][$player], $issuer));
			return self::RET_SUCCESS;
		}else{
			return self::RET_NOT_FOUND;
		}
	}
	
	public function onCommand(CommandSender $player, Command $command, String $label, array $args) : bool {
        switch($command->getName()){
            case "seeinforp":
            $exp = $this->getLevelExp($player);
            $level = $this->getLevel($player);
            $nextlevel = $this->getNextLevelUpExp($player);
            $player->sendMessage("§l§bLevel Roya Pass");
            $player->sendMessage("§l§aLevel§f: §r§b".$level);
            $player->sendMessage("§l§aExp§f: §r§b".$exp."§f/§b".$nextlevel);
            return true;
        }
        return true;
	}
	
}