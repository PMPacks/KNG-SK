<?php
namespace MinionShop;
use pocketmine\server\Server;
use pocketmine\plugin\PluginBase;
use pocketmine\command\{CommandSender, Command, ConsoleCommandSender};
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\item\Item;
use pocketmine\inventory\{Inventory, BaseInventory};
use pocketmine\utils\Config;
use onebone\coinapi\CoinAPI;
use CLADevs\Minion\Main;
use jojoe77777\FormAPI\{FormAPI, SimpleForm, CustomForm};
class MinionShop extends PluginBase implements Listener{
    public $config;
    public function onEnable(): void{
        foreach([
            "CoinAPI" => "CoinAPI",
            "Minion" => "Minion",
            "FormAPI" => "FormAPI"] as $plugins){
                if(!$this->getServer()->getPluginManager()->getPlugin($plugins)){
                    $this->getLogger()->error("Bạn chưa cài plugin ". $plugins .". Vui lòng cài đủ 3 plugin: FormAPI, CoinAPI, Minion để plugin có thể hoạt động trơn tru.");
                    $this->getServer()->getPluginManager()->disablePlugin($this);
                    return;
            }
        }
        $this->getLogger()->info("Plugin đã được bật!");
        $this->getLogger()->info("§c Author By ClickedTran");
        $this->getLogger()->info("§c Copyright By KingNightVN");
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        @mkdir($this->getDataFolder());
        $this->saveResource("config.yml");
        $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
        $this->saveDefaultConfig();
        $this->reloadConfig();
    }
    public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args): bool{
        if($sender instanceof Player){
        switch(strtolower($cmd->getName())){
                case "mshop":
                $this->mainForm($sender);
                break;
                case "ms":
                $this->mainForm($sender);
               break;
    }
        }else{
                $sender->sendMessage($this->config->get("prefix"). $this->config->get("error-consoleSender"));
    }
                return true;
    }
    public function mainForm(Player $player){
        $form = new SimpleForm(function(Player $player, int $data = null){
            if($data === null){
                return true;
            }
            switch($data){
                case 0:
                    $this->buyMinionMinerForm($player);
                break;
                case 1:
                    $this->buyMinionFarmerForm($player);
                break;
                case 2:
                    $this->sellMinionForm($player);
                break;
                case 3:
                break;
            }
        });
        $form->setTitle($this->config->get("title"));
        $money = CoinAPI::getInstance()->myCoin($player);
        $form->setContent("§l§fTài khoản: §a". $player->getName() ."\n§fCoin Của Bạn: §e". $money);
        $form->addButton("§l§e    • §cMUA MINION MINER§e •\n§f[§eGiá Mua: §c100 Coins§f]");
        $form->addButton("§l§e    • §cMUA MINION FARMER§e •\n§f[§eGiá Mua: §c100 Coin§f]");
        $form->addButton("§l§e  • §cBÁN MINION§e •\n§f[§eGiá Bán: §c50 Coins/Con§f]");
        $form->addButton("§l§e•§cTHOÁT§e•");
        $form->sendToPlayer($player);
        return $form;
    }
    public function buyMinionMinerForm(Player $player){
        $form = new CustomForm(function (Player $player, ?array $data){
        if($data === null){
        	$this->mainForm($player);
            return true;
        }
        $prices = $this->config->get("price") * $data[1];
        $money = CoinAPI::getInstance()->myCoin($player);
        if($money < $prices){
            $player->sendMessage($this->config->get("prefix"). $this->config->get("error-notEnoughMoney"));
        }else{
            CoinAPI::getInstance()->reduceCoin($player, $prices);
            $i = 0;
            while($i < $data[1]){
            $i++;
            $this->getServer()->dispatchCommand(new ConsoleCommandSender() , "minion miner " . $player->getName());
            }
            $amount = str_replace("{amount}", $data[1], $this->config->get("success-boughtMinion"));
            $buy = str_replace("{buy}", $prices, $this->config->get("success-deductionMoney"));
            $player->sendMessage($this->config->get("prefix"). $amount);
            $player->sendMessage($this->config->get("prefix"). $buy);
        }
    });
        $form->setTitle($this->config->get("title"));
        $money = CoinAPI::getInstance()->myCoin($player);
        $form->addLabel("§l§fTài khoản: §a". $player->getName() ."\n§fCoin CỦA BẠN: §e". $money);
        $form->addSlider("§lSố lượng", 1, $this->config->get("amountBuyMinion"), 1);
        $form->sendToPlayer($player);
        return $form;
    }
    
    public function buyMinionFarmerForm(Player $player){
        $form = new CustomForm(function (Player $player, ?array $data){
        if($data === null){
        	$this->mainForm($player);
            return true;
        }
        $prices = $this->config->get("price") * $data[1];
        $money = CoinAPI::getInstance()->myCoin($player);
        if($money < $prices){
            $player->sendMessage($this->config->get("prefix"). $this->config->get("error-notEnoughMoney"));
        }else{
            CoinAPI::getInstance()->reduceCoin($player, $prices);
            $i = 0;
            while($i < $data[1]){
            $i++;
            $this->getServer()->dispatchCommand(new ConsoleCommandSender() , "minion farmer " . $player->getName());
            }
            $amount = str_replace("{amount}", $data[1], $this->config->get("success-boughtMinion"));
            $buy = str_replace("{buy}", $prices, $this->config->get("success-deductionMoney"));
            $player->sendMessage($this->config->get("prefix"). $amount);
            $player->sendMessage($this->config->get("prefix"). $buy);
        }
    });
        $form->setTitle($this->config->get("title"));
        $money = CoinAPI::getInstance()->myCoin($player);
        $form->addLabel("§l§fTài khoản: §a". $player->getName() ."\n§fCoin Của Bạn: §e". $money. "\n§eBạn có chắc muốn mua §cMinion Farmer §ekhông? Vì nó chỉ đào đc 3x3 block\n(§fTính cả chỗ nó đứng)");
        $form->addSlider("§lSố lượng", 1, $this->config->get("amountBuyMinion"), 1);
        $form->sendToPlayer($player);
        return $form;
    }

    public function sellMinionForm(Player $player){
        $form = new CustomForm(function (Player $player, ?array $data){
        if($data === null){
        	$this->mainForm($player);
            return true;
        }
        if($player->getInventory()->contains(Item::get(399, 0, $data[1]))){
        $i = 0;
        while($i < $data[1]){
        $player->getInventory()->removeItem(Item::get(399, 0, 1));
        $i++;
        }
        $money = $this->config->get("sell") * $data[1];
        $this->getServer()->getPluginManager()->getPlugin("CoinAPI")->addCoin($player, $money);
        $amount = str_replace("{amount}", $data[1], $this->config->get("success-sellMinion"));
        $sell = str_replace(["{sell}", "{player}"], [$money, $player->getName()], $this->config->get("success-addMoney"));
        $player->sendMessage($this->config->get("prefix"). $amount);
        $player->sendMessage($this->config->get("prefix"). $sell);
        }else{
        $player->sendMessage($this->config->get("prefix"). $this->config->get("error-notFoundMinion"));
        }
        return true;
    });
        $form->setTitle($this->config->get("title"));
        $money = CoinAPI::getInstance()->myCoin($player);
        $form->addLabel("§l§fTài khoản: §a". $player->getName() ." §f| Coin CỦA BẠN: §e". $money ."$");
        $form->addSlider("§lSố lượng", 1, $this->config->get("amountSellMinion"), 1);
        $form->sendToPlayer($player);
        return $form;
    }
}
?>
