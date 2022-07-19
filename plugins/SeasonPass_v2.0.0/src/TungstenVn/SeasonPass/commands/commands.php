<?php
namespace TungstenVn\SeasonPass\commands;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\Player;
use pocketmine\plugin\Plugin;
use pocketmine\event\Listener;


use TungstenVn\SeasonPass\SeasonPass;
use TungstenVn\SeasonPass\subCommands\addItem;
use TungstenVn\SeasonPass\menuHandle\menuHandle;
use TungstenVn\SeasonPass\subCommands\removeItem;
use TungstenVn\SeasonPass\subCommands\setItemInfo;

class commands extends Command implements PluginIdentifiableCommand, Listener
{

    /*  Main Class (SeasonPass) */
    public $ssp;

    public function __construct(SeasonPass $ssp)
    {
        parent::__construct("ssp", "Mở Season Pass", ("/ssp"), []);
        $this->ssp = $ssp;
    }

    public function execute(CommandSender $sender, $commandLabel, array $args)
    {
        if ($sender instanceof Player) {
            if (!isset($args[0])) {
                $a = new menuHandle($this, $sender);
                $this->ssp->getServer()->getPluginManager()->registerEvents($a, $this->ssp);
                return;
            }
            switch ($args[0]) {
                case 'a':
                case 'additem':
                    new addItem($this, $sender, $args);
                    break;
                case 'sl':
                case 'setlore':
                    new setItemInfo(1, $sender, $args);
                    break;
                case 'sn':
                case 'setname':
                    new setItemInfo(0, $sender, $args);
                    break;
                case 'r':
                case 'removeitem':
                    new removeItem($this, $sender, $args);
                    break;
                /*default:
                    $this->helpForm($sender,"");
                    break;*/
            }
        } else {
            $sender->sendMessage("Please run command in-game.");
        }
    }
    public function getPlugin(): Plugin
    {
        return $this->ssp;
    }
}
