<?php

declare(strict_types=1);

/**
 * @name PiggyFactionsAddon
 * @version 1.1.0
 * @main DaPigGuy\PiggyFactions\PiggyFactionsAddon
 * @depend PiggyFactions
 */

namespace DaPigGuy\PiggyFactions {

    use DaPigGuy\PiggyFactions\players\PlayerManager;
    use JackMD\ScoreHud\addon\AddonBase;
    use pocketmine\Player;

    class PiggyFactionsAddon extends AddonBase
    {
        public function getProcessedTags(Player $player): array
        {
            $member = PlayerManager::getInstance()->getPlayer($player);
            $faction = $member === null ? null : $member->getFaction();
            return [
                "{faction}" => $faction === null ? "N/A" : $faction->getName(),
                "{faction_power}" => $faction === null ? "N/A" : round($faction->getPower(), 2, PHP_ROUND_HALF_DOWN),
                "{faction_rank}" => $faction === null ? "N/A" : $member->getRole()
            ];
        }
    }
}