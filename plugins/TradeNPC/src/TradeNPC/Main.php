<?php
declare(strict_types=1);

namespace TradeNPC;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\entity\Entity;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\item\Item;
use pocketmine\nbt\LittleEndianNBTStream;
use pocketmine\nbt\tag\ByteArrayTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\network\mcpe\protocol\ActorEventPacket;
use pocketmine\network\mcpe\protocol\ContainerClosePacket;
use pocketmine\network\mcpe\protocol\InventoryTransactionPacket;
use pocketmine\network\mcpe\protocol\LoginPacket;
use pocketmine\network\mcpe\protocol\types\NetworkInventoryAction;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\network\mcpe\protocol\types\inventory\UseItemOnEntityTransactionData;
use pocketmine\network\mcpe\protocol\types\inventory\NormalTransactionData;
use pocketmine\network\mcpe\protocol\types\inventory\TransactionData;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\network\mcpe\protocol\LevelEventPacket;
use pocketmine\network\mcpe\NetworkBinaryStream;
use function array_shift;
use function file_exists;
use function file_get_contents;
use function file_put_contents;
use function strtolower;
use function unlink;

class Main extends PluginBase implements Listener
{

	protected $deviceOSData = [];

	private static $instance = null;

	public function onLoad()
	{
		self::$instance = $this;
	}

	public static function getInstance(): Main
	{
		return self::$instance;
	}

	public function onEnable()
	{
		$this->saveResource("config.yml");
		Entity::registerEntity(TradeNPC::class, true, ["tradenpc"]);
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}

	public function loadData(TradeNPC $npc)
	{
		if (file_exists($this->getDataFolder() . $npc->getNameTag() . ".dat")) {
			$nbt = (new LittleEndianNBTStream())->read(file_get_contents($this->getDataFolder() . $npc->getNameTag() . ".dat"));
		} else {
			$nbt = new CompoundTag("Offers", [
				new ListTag("Recipes", [])
			]);
		}
		$npc->loadData($nbt);
	}

	public function onMove(PlayerMoveEvent $event)
	{
		$player = $event->getPlayer();
		if ($this->getConfig()->getNested("enable-see-player", false)){
			foreach ($player->getLevel()->getEntities() as $entity) {
				if ($entity instanceof TradeNPC) {
					if ($player->distance($entity) <= 5) {
						$entity->lookAt($player);
					}
				}
			}
		}
	}

	public function onInteract(PlayerInteractEvent $event)
	{
		$player = $event->getPlayer();
		if (isset(TradeDataPool::$editNPCData[$player->getName()])) {
			$m = (int)TradeDataPool::$editNPCData[$player->getName()] ["step"];
			$item = $event->getItem();
			if ($m === 1) {
				TradeDataPool::$editNPCData[$player->getName()] ["buyA"] = $item;
				TradeDataPool::$editNPCData[$player->getName()] ["step"] = 2;
				$player->sendMessage("Touch item that you want to sell.");
				return;
			}
			if ($m === 2) {
				TradeDataPool::$editNPCData[$player->getName()] ["buyB"] = $item;
				TradeDataPool::$editNPCData[$player->getName()] ["step"] = 3;
				$player->sendMessage("Touch item that you want to sell.");
				return;
			}
			if ($m === 3) {
				if (TradeDataPool::$editNPCData[$player->getName()] ["buyA"]->equals($item) and TradeDataPool::$editNPCData[$player->getName()] ["buyB"]->equals($item)) {
					$player->sendMessage("The sell item and buy item cannot be equals");
					return;
				}
				TradeDataPool::$editNPCData[$player->getName()] ["sell"] = $item;
				TradeDataPool::$editNPCData[$player->getName()] ["step"] = 4;
				$player->sendMessage("Please interact the npc.");
				return;
			}
		}
	}

	public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
	{
		if (!$sender instanceof Player) {
			return true;
		}
		if (!isset($args[0])) {
			$args[0] = "x";
		}
		switch ($args[0]) {
			case "create":
				array_shift($args);
				$name = array_shift($args);
				if (!isset($name)) {
					$sender->sendMessage("Input the entity's name.");
					break;
				}
				$nbt = Entity::createBaseNBT($sender->asVector3());
				$nbt->setTag(new CompoundTag("Skin", [
					new StringTag("Name", $sender->getSkin()->getSkinId()),
					new ByteArrayTag("Data", $sender->getSkin()->getSkinData()),
					new ByteArrayTag("CapeData", $sender->getSkin()->getCapeData()),
					new StringTag("GeometryName", $sender->getSkin()->getGeometryName()),
					new ByteArrayTag("GeometryData", $sender->getSkin()->getGeometryData())
				]));
				/** @var TradeNPC $entity */
				$entity = Entity::createEntity("tradenpc", $sender->getLevel(), $nbt);
				$entity->setNameTag($name);
				$entity->spawnToAll();
				break;
			case "setitem":
				TradeDataPool::$editNPCData[$sender->getName()] = [
					"buyA" => null,
					"buyB" => null,
					"sell" => null,
					"step" => 1
				];
				$sender->sendMessage("Cầm vật phẩm và gõ nhẹ vào đất");
				$sender->sendMessage("Bước 1: Vật phẩm cần trao đổi thứ 1");
				$sender->sendMessage("Bước 2: Vật phẩm cần trao đổi thứ 2");
				$sender->sendMessage("Bước 3: Vật phẩm trao đổi");
				$sender->sendMessage("Nếu đã hoàn thành các bước vui lòng ấn vào NPC!");
				break;
			case "remove":
				array_shift($args);
				$name = array_shift($args);
				if (!isset($name)) {
					$sender->sendMessage("Input the entity's name");
					break;
				}
				if (!file_exists($this->getDataFolder() . $name . ".dat")) {
					$sender->sendMessage("File Data của NPC này không tồn tại.");
					break;
				}
				unlink($this->getDataFolder() . $name . ".dat");
				$sender->sendMessage("Đã xoá thành công NPC.");
				foreach ($this->getServer()->getLevels() as $level) {
					foreach ($level->getEntities() as $entity) {
						if ($entity instanceof TradeNPC) {
							if ($entity->getNameTag() === $name) {
								$entity->close();
								break;
							}
						}
					}
				}
				break;
			default:
				foreach ([
							 ["/npc create", "Create an NPC"],
							 ["/npc setitem", "Add the item to NPC"],
							 ["/npc remove", "Remove an NPC"]
						 ] as $usage) {
					$sender->sendMessage($usage[0] . " - " . $usage[1]);
				}
		}
		return true;
	}

	/**
	 * @param DataPacketReceiveEvent $event
	 *
	 * @author
	 */
	public function handleDataPacket(DataPacketReceiveEvent $event)
	{
		$player = $event->getPlayer();
		$packet = $event->getPacket();
		if ($packet instanceof ActorEventPacket) {
			if ($packet->event === ActorEventPacket::COMPLETE_TRADE) {
				if (!isset(TradeDataPool::$interactNPCData[$player->getName()])) {
					return;
				}
				$data = TradeDataPool::$interactNPCData[$player->getName()]->getShopCompoundTag()->getListTag("Recipes")->get($packet->data);
				if ($data instanceof CompoundTag) {
					$buya = Item::nbtDeserialize($data->getCompoundTag("buyA"));
					$buyb = Item::nbtDeserialize($data->getCompoundTag("buyB"));
					$sell = Item::nbtDeserialize($data->getCompoundTag("sell"));
					if ($player->getInventory()->contains($buya) and $player->getInventory()->contains($buyb)) {// Prevents https://github.com/alvin0319/TradeNPC/issues/3
						$player->getInventory()->removeItem($buya);
						$player->getInventory()->removeItem($buyb);
						$player->getInventory()->addItem($sell);
						$volume = mt_rand();
						$player->getlevel()->broadcastLevelSoundEvent($player, LevelSoundEventPacket::SOUND_LEVELUP, (int) $volume);
					} else {
						$volume = mt_rand();
	        			$player->getLevel()->broadcastLevelEvent($player, LevelEventPacket::EVENT_SOUND_ANVIL_FALL, (int) $volume);
					}
				}
				// unset(TradeDataPool::$interactNPCData[$player->getName()]);
			}
		}
		if ($packet instanceof InventoryTransactionPacket) {
			//7: PC
			if($packet->trData instanceof NormalTransactionData){
				foreach ($packet->trData->getActions() as $action) {
					if ($action instanceof NetworkInventoryAction) {
						if (isset(TradeDataPool::$windowIdData[$player->getName()]) and $action->windowId === TradeDataPool::$windowIdData[$player->getName()]) {
							$player->getInventory()->addItem($action->oldItem);
							$player->getInventory()->removeItem($action->newItem);
						}
					}
				}
			} elseif($packet->trData instanceof UseItemOnEntityTransactionData) {
				$entity = $player->getLevel()->getEntity($packet->trData->getEntityRuntimeId());
				if ($entity instanceof TradeNPC) {
					if (isset(TradeDataPool::$editNPCData[$player->getName()]) and (int)TradeDataPool::$editNPCData[$player->getName()] ["step"] === 4) {
						/**
						 * @var Item $buy
						 * @var Item $sell
						 */
						$buya = TradeDataPool::$editNPCData[$player->getName()] ["buyA"];
						$buyb = TradeDataPool::$editNPCData[$player->getName()] ["buyB"];
						$sell = TradeDataPool::$editNPCData[$player->getName()] ["sell"];
						$entity->addTradeItem($buya, $buyb, $sell);
						unset(TradeDataPool::$editNPCData[$player->getName()]);
						$player->sendMessage("Đã thêm thành công vật phẩm vào NPC.");
					} else {
						if (!isset($this->deviceOSData[strtolower($player->getName())])) {
							$player->sendMessage("Please reconnect the server.");
							return;
						}
						$player->addWindow($entity->getTradeInventory());
					}
				}
			}
		}
		if ($packet instanceof LoginPacket) {
			$device = (int)$packet->clientData["DeviceOS"];
			$this->deviceOSData[strtolower($packet->username)] = $device;
		}
		if ($packet instanceof ContainerClosePacket) {
			if (isset(TradeDataPool::$windowIdData[$player->getName()])) {
				$pk = new ContainerClosePacket();
				$pk->windowId = 255; // ??
				$player->sendDataPacket($pk);
			}
		}
	}

	public function onQuit(PlayerQuitEvent $event)
	{
		$player = $event->getPlayer();
		if (isset($this->deviceOSData[strtolower($player->getName())])) unset($this->deviceOSData[strtolower($player->getName())]);
	}

	public function saveData(TradeNPC $npc)
	{
		file_put_contents($this->getDataFolder() . $npc->getNameTag() . ".dat", $npc->getSaveNBT());
	}

	public function onDisable()
	{
		foreach ($this->getServer()->getLevels() as $level) {
			foreach ($level->getEntities() as $entity) {
				if ($entity instanceof TradeNPC) {
					file_put_contents($this->getDataFolder() . $entity->getNameTag() . ".dat", $entity->getSaveNBT());
				}
			}
		}
	}
}