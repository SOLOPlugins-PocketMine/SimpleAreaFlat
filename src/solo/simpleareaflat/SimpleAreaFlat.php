<?php

namespace solo\simpleareaflat;

use pocketmine\level\ChunkManager;
use pocketmine\level\generator\Generator;
use pocketmine\math\Vector3;
use pocketmine\utils\Random;

class SimpleAreaFlat extends Generator{

	const BASE_LAYER = [
		[7, 0],
		[7, 0],
		[1, 0],
		[1, 0],
		[1, 0],
		[1, 0],
		[1, 0],
		[1, 0],
		[1, 0],
		[1, 0],
		[1, 0],
		[1, 0],
		[3, 0],
		[3, 0],
		[3, 0],
		[3, 0]
	];
	const ROAD_BLOCK = [1, 4];
	const LAND_EDGE_BLOCK = [43, 0];
	const LAND_BLOCK = [2, 0];

	const ROAD_FLAG = 1;
	const LAND_EDGE_FLAG = 2;
	const LAND_FLAG = 3;

	public function __construct(array $options = []){
		$this->options = $options;
	}

	public function getName() : string{
		return "SimpleAreaFlat";
	}

	public function getSettings() : array{
		return [];
	}

	public function init(ChunkManager $level, Random $random) : void{
		$this->level = $level;
		$this->random = $random;
	}

	public function generateChunk(int $chunkX, int $chunkZ){
		$xOrder = array_pad([self::ROAD_FLAG, self::ROAD_FLAG, self::ROAD_FLAG, self::LAND_EDGE_FLAG], 16, self::LAND_FLAG);
		$zOrder = array_pad([self::ROAD_FLAG, self::ROAD_FLAG, self::ROAD_FLAG, self::LAND_EDGE_FLAG], 16, self::LAND_FLAG);

		if($chunkX % 2 != 0){
			$xOrder = array_reverse($xOrder);
		}
		if($chunkZ % 2 != 0){
			$zOrder = array_reverse($zOrder);
		}

		$chunk = $this->level->getChunk($chunkX, $chunkZ);

		// Create Chunk
		for($x = 0; $x < 16; $x++){
			for($z = 0; $z < 16; $z++){

				// Create base layer
				$y = 0;
				foreach(self::BASE_LAYER as $block){
					$chunk->setBlock($x, $y, $z, ...$block);
					$y++;
				}

				if($xOrder[$x] == self::ROAD_FLAG || $zOrder[$z] == self::ROAD_FLAG){
					$chunk->setBlock($x, $y, $z, ...self::ROAD_BLOCK);
				}else if($xOrder[$x] == self::LAND_EDGE_FLAG || $zOrder[$z] == self::LAND_EDGE_FLAG){
					$chunk->setBlock($x, $y, $z, ...self::LAND_EDGE_BLOCK);
				}else{
					$chunk->setBlock($x, $y, $z, ...self::LAND_BLOCK);
				}
			}
		}
	}

	public function populateChunk(int $chunkX, int $chunkZ){

	}

	public function getSpawn() : Vector3{
		return new Vector3(127.5, count(self::BASE_LAYER) + 1, 127.5);
	}
}
