<?php
declare(strict_types = 1);

/**
 * @name CoinAPIAddon
 * @version 1.0.0
 * @main JackMD\ScoreHud\Addons\CoinAPIAddon
 * @depend CoinAPI
 */
namespace JackMD\ScoreHud\Addons
{
	use JackMD\ScoreHud\addon\AddonBase;
	use onebone\coinapi\CoinAPI;
	use pocketmine\Player;

	class CoinAPIAddon extends AddonBase{

		/** @var CoinAPI */
		private $coinAPI;

		public function onEnable(): void{
			$this->coin = $this->getServer()->getPluginManager()->getPlugin("CoinAPI");
		}

		/**
		 * @param Player $player
		 * @return array
		 */
		public function getProcessedTags(Player $player): array{
			return [
				"{coin}" => $this->coin->myCoin($player)
			];
		}
	}
}
