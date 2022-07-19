<?php

namespace Electro\BankUI;

use Electro\BankUI\InterestTask;
use onebone\economyapi\EconomyAPI;
use jojoe77777\FormAPI\SimpleForm;
use jojoe77777\FormAPI\CustomForm;

use pocketmine\block\Block;
use pocketmine\command\utils\InvalidCommandSyntaxException;
use pocketmine\Server;
use pocketmine\Player;

use pocketmine\plugin\PluginBase;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

use pocketmine\utils\Config;


class BankUI extends PluginBase implements Listener{

    private static $instance;
    public $player;
    public $playerList = [];

    public function onEnable()
    {
    	//Infor Plugin
   //Please do not copy the idea, do not change the creator's name and copyright!
    $this->getLogger()->info("Plugin đã được bật!");
    $this->getLogger()->info("§l-§e-§c-§d-§a-§f-§r-");
    $this->getLogger()->info("§cPlugin được Việt Hoá 100% bởi ClickedTran!");
    $this->getLogger()->info("§l-§e-§c-§d-§a-§f-§r-");
        $this->saveDefaultConfig();
        self::$instance = $this;
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        if (!file_exists($this->getDataFolder() . "Players")){
            mkdir($this->getDataFolder() . "Players");
        }
        date_default_timezone_set($this->getConfig()->get("timezone"));
        if ($this->getConfig()->get("enable-interest") == true) {
            $this->getScheduler()->scheduleRepeatingTask(new InterestTask($this), 1100);
        }
    }

    public function dailyInterest(){
        if (date("H:i") === "12:00"){
            foreach (glob($this->getDataFolder() . "Players/*.yml") as $players) {
                $playerBankMoney = new Config($players);
                //$player = basename($players, ".yml");
                $interest = ($this->getConfig()->get("interest-rates") / 100 * $playerBankMoney->get("Money"));
                $playerBankMoney->set("Money", round($playerBankMoney->get("Money") + $interest));
                $playerBankMoney->save();
                if ($playerBankMoney->get('Transactions') === 0){
                    $playerBankMoney->set('Transactions', date("§b[d/m/y]") . "§e - Đã §9cộng: §f" . round($interest) . " §atừ §blãi §cxuất" . "\n");
                }
                else {
                    $playerBankMoney->set('Transactions', $playerBankMoney->get('Transactions') . date("§b[d/m/y]") . "§e - §cĐã §bcộng: §f" . round($interest) . " §atừ §6lãi §bxuất" . "\n");
                }
                $playerBankMoney->save();
            }
            foreach ($this->getServer()->getOnlinePlayers() as $onlinePlayers){
                $playerBankMoney = new Config($this->getDataFolder() . "Players/" . $onlinePlayers->getName() . ".yml", Config::YAML);
                $onlinePlayers->sendMessage("§aBạn §bđã §ekiếm §cđược: " . round(($this->getConfig()->get("interest-rates") / 100) * $playerBankMoney->get("Money")) . " §cVNĐ§a từ §bãi §cxuất §6ngân §5hàng!");
            }
        }
    }

    public function onJoin(PlayerJoinEvent $event){
        $player = $event->getPlayer();
        if (!file_exists($this->getDataFolder() . "Players/" . $player->getName() . ".yml")) {
            new Config($this->getDataFolder() . "Players/" . $player->getName() . ".yml", Config::YAML, array(
                "Money" => 0,
                "Transactions" => 0,
            ));
        }
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool{
        switch($command->getName()){
            case "nganhang":
                if($sender instanceof Player){
                    if (isset($args[0]) && $sender->hasPermission("bankui.admin") || isset($args[0]) && $sender->isOp()){
                        if (!file_exists($this->getDataFolder() . "Players/" . $args[0] . ".yml")){
                            $sender->sendMessage("§c§lError: §r§aThis player does not have a bank account");
                            return true;
                        }
                        $this->otherTransactionsForm($sender, $args[0]);
                        return true;
                    }
                    $this->bankForm($sender);
                }
        }
        return true;
    }

    public function bankForm($player)
    {
        $playerBankMoney = new Config($this->getDataFolder() . "Players/" . $player->getName() . ".yml", Config::YAML);
        $playerMoney = EconomyAPI::getInstance()->myMoney($player);
//        $api = Server::getInstance()->getPluginManager()->getPlugin("FormAPI");
//        $form = $api->createSimpleForm(function (Player $player, int $data = null) {
        $form = new SimpleForm(function (Player $player, int $data = null){
            $result = $data;
            if ($result === null) {
                return true;
            }
            switch ($result) {
                case 0;
                    $this->withdrawForm($player);
            }
            switch ($result) {
                case 1;
                    $this->depositForm($player);
            }
            switch ($result) {
                case 2;
                    $this->transferCustomForm($player);
            }
            switch ($result) {
                case 3;
                    $this->transactionsForm($player);
            }
        });

        $form->setTitle("§c♦§eNGÂN HÀNG§c♦");
        $form->setContent("§eSố §5tiền §6mà §abạn §dđang §egửi: " . $playerBankMoney->get("Money") . " §cXu");
        $form->addButton("§lRút Tiền\n§r§dBấm Vào Để Rút",0,"textures/ui/icon_book_writable");
        $form->addButton("§lGửi Tiền\n§r§dBấm Vào Để Gửi",0,"textures/items/map_filled");
        $form->addButton("§lChuyển Tiền\n§r§dBấm Vào Để Chuyển",0,"textures/ui/FriendsIcon");
//        $form->addButton("§lTransactions\n§r§dClick to transfer...",0,"textures/ui/inventory_icon");
//        $form->addButton("§lTransactions\n§r§dClick to transfer...",0,"textures/ui/invite_base");
        $form->addButton("§lTổng Giao Dịch\n§r§dBấm Vào Để Coi",0,"textures/ui/lock_color");
        $form->addButton("§l§cTHOÁT\n§r§dBấm Để Thoát.",0,"textures/ui/cancel");
        $form->sendtoPlayer($player);
        return $form;
    }

    public function withdrawForm($player)
    {
        $playerBankMoney = new Config($this->getDataFolder() . "Players/" . $player->getName() . ".yml", Config::YAML);
        $playerMoney = EconomyAPI::getInstance()->myMoney($player);
//        $api = Server::getInstance()->getPluginManager()->getPlugin("FormAPI");
//        $form = $api->createSimpleForm(function (Player $player, int $data = null) {
        $form = new SimpleForm(function (Player $player, int $data = null){
            $result = $data;
            if ($result === null) {
                return true;
            }
            switch ($result) {
                case 0;
                    $playerBankMoney = new Config($this->getDataFolder() . "Players/" . $player->getName() . ".yml", Config::YAML);
                    if ($playerBankMoney->get("Money") == 0){
                        $player->sendMessage("§aBạn hiện không có tiền trong ngân hàng để rút");
                        return true;
                    }
                    EconomyAPI::getInstance()->addMoney($player, $playerBankMoney->get("Money"));
                    $player->sendMessage("§aBạn đã rút số tiền: " . $playerBankMoney->get("Money") . " §cVNĐ§e từ ngân hàng");
                    if ($playerBankMoney->get('Transactions') === 0){
                        $playerBankMoney->set('Transactions', date("§b[d/m/y]") . "§e - §aSố tiền đã rút: " . $playerBankMoney->get("Money") . " §cVNĐ\n");
                    }
                    else {
                        $playerBankMoney->set('Transactions', $playerBankMoney->get('Transactions') . date("§b[d/m/y]") . "§e - §aSố tiền đã rút: " . $playerBankMoney->get("Money") . " §cVNĐ\n");
                    }
                    $playerBankMoney->set("Money", 0);
                    $playerBankMoney->save();
            }
            switch ($result) {
                case 1;
                    $playerBankMoney = new Config($this->getDataFolder() . "Players/" . $player->getName() . ".yml", Config::YAML);
                    if ($playerBankMoney->get("Money") == 0){
                        $player->sendMessage("§aBạn hiện không có tiền trong ngân hàng để rút");
                        return true;
                    }
                    EconomyAPI::getInstance()->addMoney($player, $playerBankMoney->get("Money") / 2);
                    $player->sendMessage("§aBạn đã rút số tiền: " . $playerBankMoney->get("Money") /2 . " §cVNĐ§e từ ngân hàng");
                    if ($playerBankMoney->get('Transactions') === 0){
                        $playerBankMoney->set('Transactions', date("§b[d/m/y]") . "§e - §aSố tiền đã rút: " . $playerBankMoney->get("Money") / 2 . " §cVNĐ\n");
                    }
                    else {
                        $playerBankMoney->set('Transactions', $playerBankMoney->get('Transactions') . date("§b[d/m/y]") . "§e - §aSố tiền đã rút: " . $playerBankMoney->get("Money") / 2 . " §cVNĐ\n");
                    }
                    $playerBankMoney->set("Money", $playerBankMoney->get("Money") / 2);
                    $playerBankMoney->save();
            }
            switch ($result) {
                case 2;
                    $this->withdrawCustomForm($player);
            }
        });

        $form->setTitle("§c♦§eRÚT TIỀN§c♦");
        $form->setContent("Số tiền mà bạn đang gửi: " . $playerBankMoney->get("Money") . " §cVNĐ");
        $form->addButton("§lRút Toàn Bộ\n§r§dBấm Để Rút Tiền",0,"textures/ui/icon_book_writable");
        $form->addButton("§lRút Một Nửa\n§r§dBấm Để Rút Tiền",0,"textures/ui/icon_book_writable");
        $form->addButton("§lRút Một Ít\n§r§dBấm Để Rút Tiền",0,"textures/ui/icon_book_writable");
        $form->addButton("§l§cTHOÁT\n§r§dBấm Để Thoát",0,"textures/ui/cancel");
        $form->sendtoPlayer($player);
        return $form;
    }

    public function withdrawCustomForm($player)
    {
        $playerBankMoney = new Config($this->getDataFolder() . "Players/" . $player->getName() . ".yml", Config::YAML);
        $playerMoney = EconomyAPI::getInstance()->myMoney($player);
//        $api = Server::getInstance()->getPluginManager()->getPlugin("FormAPI");
//        $form = $api->createCustomForm(function (Player $player, array $data = null) {
        $form = new CustomForm(function (Player $player, $data) {
            $result = $data;
            if ($result === null) {
                return true;
            }

            $playerBankMoney = new Config($this->getDataFolder() . "Players/" . $player->getName() . ".yml", Config::YAML);
            if ($playerBankMoney->get("Money") == 0){
                $player->sendMessage("§aBạn hiện không có tiền trong ngân hàng để rút");
                return true;
            }
            if ($playerBankMoney->get("Money") < $data[1]){
                $player->sendMessage("§aBạn không có đủ tiền trong ngân hàng để rút" . $data[1]);
                return true;
            }
            if (!is_numeric($data[1])){
                $player->sendMessage("§aVui lòng nhập số tiền hợp lệ");
                return true;
            }
            if ($data[1] <= 0){
                $player->sendMessage("§aVui lòng nhập số tiền cần rút lớn hơn 0");
                return true;
            }
            EconomyAPI::getInstance()->addMoney($player, $data[1]);
            $player->sendMessage("§aBạn đã rút số tiền: " . $data[1] . " §cVNĐ§e từ ngân hàng");
            if ($playerBankMoney->get('Transactions') === 0){
                $playerBankMoney->set('Transactions', date("§b[d/m/y]") . "§e - §aSố tiền đã rút: " . $data[1] . " §cVNĐ\n");
            }
            else {
                $playerBankMoney->set('Transactions', $playerBankMoney->get('Transactions') . date("§b[d/m/y]") . "§e - §aSố tiền đã rút: " . $data[1] . " §cVNĐ\n");
            }
            $playerBankMoney->set("Money", $playerBankMoney->get("Money") - $data[1]);
            $playerBankMoney->save();
        });

        $form->setTitle("§c♦RÚT TIỀN§c♦");
        $form->addLabel("Số tiền mà bạn đang gửi: " . $playerBankMoney->get("Money") . " §cVNĐ");
        $form->addInput("§rNhập số tiền mà bạn muốn rút", "0");
        $form->sendtoPlayer($player);
        return $form;
    }


    public function depositForm($player)
    {
        $playerBankMoney = new Config($this->getDataFolder() . "Players/" . $player->getName() . ".yml", Config::YAML);
        $playerMoney = EconomyAPI::getInstance()->myMoney($player);
//        $api = Server::getInstance()->getPluginManager()->getPlugin("FormAPI");
//        $form = $api->createSimpleForm(function (Player $player, int $data = null) {
        $form = new SimpleForm(function (Player $player, int $data = null){
            $result = $data;
            if ($result === null) {
                return true;
            }
            switch ($result) {
                case 0;
                    $playerMoney = EconomyAPI::getInstance()->myMoney($player);
                    $playerBankMoney = new Config($this->getDataFolder() . "Players/" . $player->getName() . ".yml", Config::YAML);
                    if ($playerMoney == 0){
                        $player->sendMessage("§aBạn hiện không đủ tiền để gửi vào ngân hàng");
                        return true;
                    }
                    if ($playerBankMoney->get('Transactions') === 0){
                        $playerBankMoney->set('Transactions', date("§b[d/m/y]") . "§e - §aSố tiền đã gửi: " . $playerMoney . " §cVNĐ\n");
                    }
                    else {
                        $playerBankMoney->set('Transactions', $playerBankMoney->get('Transactions') . date("§b[d/m/y]") . "§e - §aSố tiền đã gửi: " . $playerMoney . " §cVNĐ\n");
                    }
                    $playerBankMoney->set("Money", $playerBankMoney->get("Money") + $playerMoney);
                    $player->sendMessage("§aBạn đã gửi số tiền: " . $playerMoney . " §cVNĐ§a vào ngân hàng thành công");
                    EconomyAPI::getInstance()->reduceMoney($player, $playerMoney);
                    $playerBankMoney->save();
            }
            switch ($result) {
                case 1;
                    $playerMoney = EconomyAPI::getInstance()->myMoney($player);
                    $playerBankMoney = new Config($this->getDataFolder() . "Players/" . $player->getName() . ".yml", Config::YAML);
                    if ($playerMoney == 0){
                        $player->sendMessage("§aBạn hiện không đủ tiền để gửi vào ngân hàng");
                        return true;
                    }
                    if ($playerBankMoney->get('Transactions') === 0){
                        $playerBankMoney->set('Transactions', date("§b[d/m/y]") . "§e - §aSố tiền đã gửi: " . $playerMoney / 2 . " §cVNĐ\n");
                    }
                    else {
                        $playerBankMoney->set('Transactions', $playerBankMoney->get('Transactions') . date("§b[d/m/y]") . "§e - §aSố tiền đã gửi: " . $playerMoney / 2 . " §cVNĐ\n");
                    }
                    $playerBankMoney->set("Money", $playerBankMoney->get("Money") + ($playerMoney / 2));
                    $player->sendMessage("§aBạn đã gửi số tiền: " . $playerMoney / 2 . " §cVNĐ§a vào ngân hàng thành công");
                    EconomyAPI::getInstance()->reduceMoney($player, $playerMoney / 2);
                    $playerBankMoney->save();
            }
            switch ($result) {
                case 2;
                    $this->depositCustomForm($player);
            }
        });

        $form->setTitle("§c♦§eGỬI TIỀN§c♦");
        $form->setContent("Số tiền mà bạn đang gửi: " . $playerBankMoney->get("Money") . " §cVNĐ");
        $form->addButton("§lGửi Toàn Bộ\n§r§dBấm Vào Để Gửi",0,"textures/items/map_filled");
        $form->addButton("§lGửi Một Nửa\n§r§dBấm Vào Để Gửi",0,"textures/items/map_filled");
        $form->addButton("§lGửi Một Ít\n§r§dBấm Vào Để Gửi",0,"textures/items/map_filled");
        $form->addButton("§l§cTHOÁT\n§r§dBấm Vào Để Thoát",0,"textures/ui/cancel");
        $form->sendtoPlayer($player);
        return $form;
    }

    public function depositCustomForm($player)
    {
        $playerBankMoney = new Config($this->getDataFolder() . "Players/" . $player->getName() . ".yml", Config::YAML);
        $playerMoney = EconomyAPI::getInstance()->myMoney($player);
//        $api = Server::getInstance()->getPluginManager()->getPlugin("FormAPI");
//        $form = $api->createCustomForm(function (Player $player, array $data = null) {
        $form = new CustomForm(function (Player $player, $data) {
            $result = $data;
            if ($result === null) {
                return true;
            }
            $playerMoney = EconomyAPI::getInstance()->myMoney($player);
            $playerBankMoney = new Config($this->getDataFolder() . "Players/" . $player->getName() . ".yml", Config::YAML);
//            if ($playerMoney == 0){
//                $player->sendMessage("§aYou do not have enough money to deposit into the bank");
//                return true;
//            }
            if ($playerMoney < $data[1]){
                $player->sendMessage("§aBạn hiện không có đủ số tiền: " . $data[1] . " §cVNĐ§a để gửi vào ngân hàng");
                return true;
            }
            if (!is_numeric($data[1])){
                $player->sendMessage("§aVui lòng nhập số");
                return true;
            }
            if ($data[1] <= 0){
                $player->sendMessage("§aSố tiền mà bạn muốn gửi phải lớn hơn 0");
                return true;
            }
            $player->sendMessage("§aBạn đã gửi số tiền: " . $data[1] . " §cVNĐ§a vào ngân hàng");
            if ($playerBankMoney->get('Transactions') === 0){
                $playerBankMoney->set('Transactions', date("§b[d/m/y]") . "§e - §aSố tiền đã gửi: " . $data[1] . " §cVNĐ\n");
            }
            else {
                $playerBankMoney->set('Transactions', $playerBankMoney->get('Transactions') . date("§b[d/m/y]") . "§e - §aSố tiền đã gửi: " . $data[1] . " §cVNĐ\n");
            }
            $playerBankMoney->set("Money", $playerBankMoney->get("Money") + $data[1]);
            EconomyAPI::getInstance()->reduceMoney($player, $data[1]);
            $playerBankMoney->save();
        });

        $form->setTitle("§c♦§eGỬI TIỀN§c♦");
        $form->addLabel("Số tiền mà bạn đang gửi: " . $playerBankMoney->get("Money") . " §cVNĐ");
        $form->addInput("§rNhập số tiền muốn gửi", "0");
        $form->sendtoPlayer($player);
        return $form;
    }

    public function transferCustomForm($player)
    {

        $list = [];
        foreach ($this->getServer()->getOnlinePlayers() as $players){
            if ($players->getName() !== $player->getName()) {
                $list[] = $players->getName();
            }
        }
        $this->playerList[$player->getName()] = $list;

        $playerBankMoney = new Config($this->getDataFolder() . "Players/" . $player->getName() . ".yml", Config::YAML);
        $playerMoney = EconomyAPI::getInstance()->myMoney($player);
//        $api = Server::getInstance()->getPluginManager()->getPlugin("FormAPI");
//        $form = $api->createCustomForm(function (Player $player, array $data = null) {
        $form = new CustomForm(function (Player $player, $data) {
            $result = $data;
            if ($result === null) {
                return true;
            }

            if (!isset($this->playerList[$player->getName()][$data[1]])){
                $player->sendMessage("§aBạn phải chọn người chơi cần chuyển");
                return true;
            }

            $index = $data[1];
            $playerName = $this->playerList[$player->getName()][$index];

            $playerBankMoney = new Config($this->getDataFolder() . "Players/" . $player->getName() . ".yml", Config::YAML);
            $otherPlayerBankMoney = new Config($this->getDataFolder() . "Players/" . $playerName . ".yml", Config::YAML);
            if ($playerBankMoney->get("Money") == 0){
                $player->sendMessage("§aBạn không thể chuyển tiền vì bạn không đủ tiền trong ngân hàng");
                return true;
            }
            if ($playerBankMoney->get("Money") < $data[2]){
                $player->sendMessage("§aBạn không đủ tiền trong ngân hàng chuyển" . $data[2]);
                return true;
            }
            if (!is_numeric($data[2])){
                $player->sendMessage("§aVui lòng nhập số cần chuyển");
                return true;
            }
            if ($data[2] <= 0){
                $player->sendMessage("§aBạn cần phải chuyển tiền trên lớn hơn 1 VNĐ");
                return true;
            }
            $player->sendMessage("§aBạn đã chuyển số tiền: " . $data[2] . " §cVNĐ §avào ngân hàng của người chơi " . $playerName);
            if ($this->getServer()->getPlayer($playerName)) {
                $otherPlayer = $this->getServer()->getPlayer($playerName);
                $otherPlayer->sendMessage("§a" . $player->getName() . " đã chuyển số tiền: " . $data[2] . " §cVNĐ§a tới ngân hàng của bạn");
            }
            if ($playerBankMoney->get('Transactions') === 0){
                $playerBankMoney->set('Transactions', date("§b[d/m/y]") . "§e - §a đã chuyển số tiền: " . $data[2] . " §cVNĐ§a tới ngân hàng của: " . $playerName . "\n");
                $otherPlayerBankMoney->set('Transactions', date("§b[d/m/y]") . "§e - §a" . $player->getName() . " đã chuyển số tiền: " . $data[2] . " §cVNĐ§a tới ngân hàng của bạn" . "\n");
            }
            else {
                $otherPlayerBankMoney->set('Transactions', $playerBankMoney->get('Transactions') . date("§b[d/m/y]") . "§e - §a" . $player->getName() . " đã chuyển số tiền: " . $data[2] . " §cVNĐ§a tới ngân hàng của bạn" . "\n");
                $playerBankMoney->set('Transactions', $playerBankMoney->get('Transactions') . date("§b[d/m/y]") . "§e - §ađã chuyển số tiền: " . $data[2] . " §cVNĐ§a tới ngân hàng của: " . $playerName . "\n");
            }
            $playerBankMoney->set("Money", $playerBankMoney->get("Money") - $data[2]);
            $otherPlayerBankMoney->set("Money", $otherPlayerBankMoney->get("Money") + $data[2]);
            $playerBankMoney->save();
            $otherPlayerBankMoney->save();
            });


        $form->setTitle("§c♦§eCHUYỂN TIỀN§c♦");
        $form->addLabel("Số tiền mà bạn đang gửi: " . $playerBankMoney->get("Money") . "§cVNĐ");
        $form->addDropdown("Chọn người chơi", $this->playerList[$player->getName()]);
        $form->addInput("§rNhập số tiền cần chuyển", "0");
        $form->sendtoPlayer($player);
        return $form;
    }

    public function transactionsForm($player)
    {
        $playerBankMoney = new Config($this->getDataFolder() . "Players/" . $player->getName() . ".yml", Config::YAML);
        $playerMoney = EconomyAPI::getInstance()->myMoney($player);
        $form = new SimpleForm(function (Player $player, int $data = null){
            $result = $data;
            if ($result === null) {
                return true;
            }
        });

        $form->setTitle("§c♦§eMenu Giao Dịch§c♦");
        if ($playerBankMoney->get('Transactions') === 0){
            $form->setContent("Bạn chưa thực hiện bất kỳ chuyển khoản nào");
        }
        else {
            $form->setContent($playerBankMoney->get("Transactions"));
        }
        $form->addButton("§l§cTHOÁT\n§r§dBấm Để Thoát",0,"textures/ui/cancel");
        $form->sendtoPlayer($player);
        return $form;
    }

    public function otherTransactionsForm($sender, $player)
    {
        $playerBankMoney = new Config($this->getDataFolder() . "Players/" . $player . ".yml", Config::YAML);
        $form = new SimpleForm(function (Player $player, int $data = null){
            $result = $data;
            if ($result === null) {
                return true;
            }
        });

        $form->setTitle("§l" . $player . "'giao dịch");
        if ($playerBankMoney->get('Transactions') === 0){
            $form->setContent($player . " chưa thực hiện bất kì chuyển khoản nào");
        }
        else {
            $form->setContent($playerBankMoney->get("Transactions"));
        }
        $form->addButton("§l§cTHOÁT\n§r§dBấm Để Thoát",0,"textures/ui/cancel");
        $form->sendtoPlayer($sender);
        return $form;
    }

    public static function getInstance(): BankUI {
        return self::$instance;
    }

}
