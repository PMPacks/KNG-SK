<?php
declare(strict_types = 1);

namespace NovaStark1245\GCPClanAPI;

//use FactionsPro\FactionMain;
use NovaStark1245\GCPClanAPI\listeners\TagResolveListener;
use Ifera\ScoreHud\event\PlayerTagUpdateEvent;
use Ifera\ScoreHud\scoreboard\ScoreTag;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\ClosureTask;
use function strval;

class Main extends PluginBase{

	/** @var FactionMain */
	private $owningPlugin;

	public function onEnable(){
		$this->saveDefaultConfig();
		$this->owningPlugin = $this->getServer()->getPluginManager()->getPlugin("GCPClan");
		$this->getServer()->getPluginManager()->registerEvents(new TagResolveListener($this), $this);

		$this->getScheduler()->scheduleRepeatingTask(new ClosureTask(function(int $_): void{
			foreach($this->getServer()->getOnlinePlayers() as $player){
				if(!$player->isOnline()){
					continue;
				}

				(new PlayerTagUpdateEvent($player, new ScoreTag("bedrock.clan", strval($this->getPlayerFaction($player)))))->call();
				//(new PlayerTagUpdateEvent($player, new ScoreTag("factionsproscore.power", strval($this->getFactionPower($player)))))->call();
			}
		}), 20);
	}

	public function getPlayerFaction(Player $player) :string {
		$faction = $this->owningPlugin::getInstance();

		if($faction->haveClan($player)) {
			$factionName = $faction->getClanName($player);
		} else {
			$factionName = 'Không có';
		}

		return $factionName;
	}
}