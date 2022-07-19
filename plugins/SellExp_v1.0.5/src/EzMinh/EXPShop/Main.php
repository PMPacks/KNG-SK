<?php

namespace EzMinh\EXPShop;

use pocketmine\Player;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat as C;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase
{
    public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args): bool
    {
        if ($cmd->getName() == "sellexp") 
        {
            if (!$sender instanceof Player) 
            {
                $sender->sendMessage(C::RED . "You can't not use this command here!");
                return false;
            }
            $this->sellMain($sender);
		}
		return true;
    }
	
	public function sellMain($sender)
    {
        $formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $formapi->createSimpleForm(function (Player $sender, $data) {
			
            $result = $data;
            if ($result === null) 
            {
		    return false;
            }
			
            switch($result) 
            {
			    case '0':
                    $this->sellEXP($sender);
                break;
					
			    case '1':
                break; 
					
            }
        });
        $form->setTitle("§l§6♦§d Sell Exp §6♦");
	    $form->addButton("§l§3● §2Bán Exp §3●");
		$form->addButton("§l§3● §4Thoát §3●");
        $form->sendToPlayer($sender);
	}
    public function sellEXP($sender)
    {
        $formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $formapi->createCustomForm(function (Player $sender, $data){
			
			$result = $data;
            if ($result === null) 
            {
			$this->sellMain($sender);
			return;
            }
			
		    if(!isset($data[0]) || !is_numeric($data[0]) || $data[0] < 0 || !preg_match('/^[0-9]+$/', $data[0], $matches))
            {
                $sender->sendMessage("§l§6[§eKing§9Night§cVN§6]§e Số exp bán cần phải là chữ số.");
            }
            else 
            {
                $player_exp = $sender->getXpLevel();
                if($player_exp >= $data[0])
                {
                    $total_exp_level = $player_exp - $data[0];
                    $sender->setXpLevel($total_exp_level);
                    $sell_exp_cost = 500 * mt_rand(1,6);
                    $sell_exp_money = $data[0] * $sell_exp_cost;
                    $economyapi = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
                    $economyapi->addMoney($sender, $sell_exp_money);
					$sender->sendMessage("§l§6[§eKing§9Night§cVN§6]§e Bán exp thành công, nhận được§a " . $sell_exp_money . " xu");
                } else {
                    $sender->sendMessage("§l§6[§eKing§9Night§cVN§6]§e Không đủ exp để bán.");
                }
            }
        });
        $form->setTitle("§l§6♦§d Sell Exp §6♦");
        $form->addInput("§l§3●§e Nhập số exp bạn muốn bán:");
        $form->sendToPlayer($sender);
    }
}