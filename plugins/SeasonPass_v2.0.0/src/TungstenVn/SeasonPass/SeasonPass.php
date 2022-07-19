<?php

namespace TungstenVn\SeasonPass;

use pocketmine\plugin\PluginBase;
use pocketmine\Player; 
use pocketmine\Server;
use pocketmine\event\Listener;
use pocketmine\event\Event;

use TungstenVn\SeasonPass\commands\commands;

use TungstenVn\SeasonPass\libs\muqsit\invmenu\InvMenu;
use TungstenVn\SeasonPass\libs\muqsit\invmenu\InvMenuHandler;

class SeasonPass extends PluginBase implements Listener {

    public $levelApi;
	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
        if(!class_exists(InvMenu::class)){
        	$this->getServer()->getLogger()->info("\n\n§cSeasonPass >lib missing\n");
            $this->getServer()->getPluginManager()->disablePlugin($this);
            return;
        }
        $this->levelApi = $this->getServer()->getPluginManager()->getPlugin("LevelToMine");
        if($this->levelApi == null){
            $this->getServer()->getLogger()->info("\n\n§cSeasonPass >Level API is missing, plugin cant be enabled\n");
            $this->getServer()->getPluginManager()->disablePlugin($this);
            return;
        }
        if(!method_exists($this->levelApi,"getLevel")){
            $this->getServer()->getLogger()->info("\n\n§cSeasonPass >The level plugin dont have getLevel() function,which returns a number,so this plugin cant be enabled\n");
            $this->getServer()->getPluginManager()->disablePlugin($this);
            return;
        }
        $this->saveDefaultConfig();
        $cmds = new commands($this);
        $this->getServer()->getCommandMap()->register("seasonpass", $cmds);
        $this->getServer()->getPluginManager()->registerEvents($cmds,$this);
        
        if(!InvMenuHandler::isRegistered()){
            InvMenuHandler::register($this);
        }
	}

}