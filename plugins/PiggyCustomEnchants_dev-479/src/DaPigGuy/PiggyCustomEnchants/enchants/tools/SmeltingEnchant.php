<?php

declare(strict_types=1);

namespace DaPigGuy\PiggyCustomEnchants\enchants\tools;

use DaPigGuy\PiggyCustomEnchants\enchants\CustomEnchant;
use DaPigGuy\PiggyCustomEnchants\enchants\ReactiveEnchantment;
use DaPigGuy\PiggyCustomEnchants\PiggyCustomEnchants;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Event;
use pocketmine\inventory\Inventory;
use pocketmine\item\Item;
use pocketmine\Player;

class SmeltingEnchant extends ReactiveEnchantment
{
    /** @var string */
    public $name = "Smelting";
    /** @var int */
    public $rarity = CustomEnchant::RARITY_UNCOMMON;
    /** @var int */
    public $maxLevel = 10000;

    /** @var int */
    public $itemType = CustomEnchant::ITEM_TYPE_TOOLS;

    /** @var array */
    public $inputTable;
    /** @var array */
    public $outputTable;

    public function __construct(PiggyCustomEnchants $plugin, int $id)
    {
        parent::__construct($plugin, $id);
        foreach ($plugin->getServer()->getCraftingManager()->getFurnaceRecipes() as $furnaceRecipe) {
            $this->inputTable[] = $furnaceRecipe->getInput();
            $this->outputTable[] = $furnaceRecipe->getResult();
        }
    }

    public function getReagent(): array
    {
        return [BlockBreakEvent::class];
    }

    public function react(Player $player, Item $item, Inventory $inventory, int $slot, Event $event, int $level, int $stack): void
    {
        if ($event instanceof BlockBreakEvent) {
            $event->setDrops(array_map(function (Item $item) {
                $clonedItem = clone $item;
                if (($key = array_search($clonedItem, $this->inputTable)) || ($key = array_search($clonedItem->setDamage(-1), $this->inputTable))) {
                    return $this->outputTable[$key];
                }
                return $item;
            }, $event->getDrops()));
        }
    }

    public function getPriority(): int
    {
        return 2;
    }
}