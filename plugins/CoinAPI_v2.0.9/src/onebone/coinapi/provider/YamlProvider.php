<?php

/*
 * PointS, the massive point plugin with many features for PocketMine-MP
 * Copyright (C) 2013-2017  onebone <jyc00410@gmail.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace onebone\coinapi\provider;


use onebone\coinapi\CoinAPI;
use pocketmine\Player;
use pocketmine\utils\Config;

class YamlProvider implements Provider{
	/**
	 * @var Config
	 */
	private $config;

	/** @var CoinAPI */
	private $plugin;

	private $coin = [];

	public function __construct(CoinAPI $plugin){
		$this->plugin = $plugin;
	}

	public function open(){
		$this->config = new Config($this->plugin->getDataFolder() . "Coin.yml", Config::YAML, ["version" => 2, "coin" => []]);
		$this->coin = $this->config->getAll();
	}

	public function accountExists($player){
		if($player instanceof Player){
			$player = $player->getName();
		}
		$player = strtolower($player);

		return isset($this->coin["coin"][$player]);
	}

	public function createAccount($player, $defaultCoin = 1000){
		if($player instanceof Player){
			$player = $player->getName();
		}
		$player = strtolower($player);

		if(!isset($this->coin["coin"][$player])){
			$this->coin["coin"][$player] = $defaultCoin;
			return true;
		}
		return false;
	}

	public function removeAccount($player){
		if($player instanceof Player){
			$player = $player->getName();
		}
		$player = strtolower($player);

		if(isset($this->coin["coin"][$player])){
			unset($this->coin["coin"][$player]);
			return true;
		}
		return false;
	}

	public function getCoin($player){
		if($player instanceof Player){
			$player = $player->getName();
		}
		$player = strtolower($player);

		if(isset($this->coin["coin"][$player])){
			return $this->coin["coin"][$player];
		}
		return false;
	}

	public function setCoin($player, $amount){
		if($player instanceof Player){
			$player = $player->getName();
		}
		$player = strtolower($player);

		if(isset($this->coin["coin"][$player])){
			$this->coin["coin"][$player] = $amount;
			$this->coin["coin"][$player] = round($this->coin["coin"][$player], 2);
			return true;
		}
		return false;
	}

	public function addCoin($player, $amount){
		if($player instanceof Player){
			$player = $player->getName();
		}
		$player = strtolower($player);

		if(isset($this->coin["coin"][$player])){
			$this->coin["coin"][$player] += $amount;
			$this->coin["coin"][$player] = round($this->coin["coin"][$player], 2);
			return true;
		}
		return false;
	}

	public function reduceCoin($player, $amount){
		if($player instanceof Player){
			$player = $player->getName();
		}
		$player = strtolower($player);

		if(isset($this->coin["coin"][$player])){
			$this->coin["coin"][$player] -= $amount;
			$this->coin["coin"][$player] = round($this->coin["coin"][$player], 2);
			return true;
		}
		return false;
	}

	public function getAll(){
		return isset($this->coin["coin"]) ? $this->coin["coin"] : [];
	}

	public function save(){
		$this->config->setAll($this->coin);
		$this->config->save();
	}

	public function close(){
		$this->save();
	}

	public function getName(){
		return "Yaml";
	}
}
