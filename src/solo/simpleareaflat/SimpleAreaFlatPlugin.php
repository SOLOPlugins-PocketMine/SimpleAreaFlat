<?php

namespace solo\simpleareaflat;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\level\ChunkPopulateEvent;
use pocketmine\scheduler\PluginTask;
use pocketmine\level\Level;
use pocketmine\level\generator\Generator;
use AreaLoader;

class SimpleAreaFlatPlugin extends PluginBase implements Listener{

	public function onLoad(){
		Generator::addGenerator(SimpleAreaFlat::class, "SimpleAreaFlat");
	}

	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}

	public function onChunkPopulate(ChunkPopulateEvent $event){
		$property = (new \ReflectionClass(Level::class))->getProperty("generatorInstance");
		$property->setAccessible(true);

		if($property->getValue($event->getLevel()) instanceof SimpleAreaFlat){
			$chunk = $event->getChunk();
			if($chunk->getX() % 2 == 0 && $chunk->getZ() % 2 == 0){
				$area = \ifteam\SimpleArea\database\area\AreaProvider::getInstance()->addArea(
					$event->getLevel(),
					($chunk->getX() * 16) + 3,
					(($chunk->getX() + 2) * 16) - 4,
					($chunk->getZ() * 16) + 3,
					(($chunk->getZ() + 2) * 16) - 4,
					"",
					true,
					false
				);
			}
		}
	}
}