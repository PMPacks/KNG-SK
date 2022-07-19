<?php

namespace RulesUI;

use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use jojoe77777\FormAPI;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\event\Listener;

class Main extends PluginBase implements Listener {
    
    public function onEnable(){
        $this->getLogger()->info("Enable V1.0...");
                $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->checkDepends();
    }

    public function checkDepends(){
        $this->formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        if(is_null($this->formapi)){
            $this->getLogger()->info("§4Please install FormAPI Plugin, disabling plugin...");
            $this->getPluginLoader()->disablePlugin($this);
        }
    }

    public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args):bool
    {
        switch($cmd->getName()){
        case "rules":
        if(!($sender instanceof Player)){
                $sender->sendMessage("§7Please use this command In-Game");
                return true;
        }
        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function (Player $sender, $data){
            $result = $data;
            if ($result == null) {
            }
            switch ($result) {
                    case 0:
                        break;
            }
        });
        $form->setTitle("§l§c•§e⊹⊱§aRules§e⊰⊹§c•");
        $form->setContent("§a§lSau đây là các luật của Server:
        	\n§c1. §eKhông Chia Sẻ Link 18+
        	\n§c2. §eKhông Spam, Chửi Tục, Hack, Phá Server
        	\n§c3. §eKhông được chửi Staff, cũng như Staff không được chửi Member
        	\n§c4. §eKhông được giới thiệu các Server Khác
        	\n\n§9§lLƯU Ý AI VI PHẠM 1 TRONG 4 LUẬT TRÊN SẼ BỊ BAND!");
        $form->addButton("§l§b•§e⊹⊱§cThoát§e⊰⊹§b•", 0);
        $form->sendToPlayer($sender);
        }
        return true;
    }

    public function onDisable(){
        $this->getLogger()->info("RulesUI V1.0 Disabled!");
    }
}
