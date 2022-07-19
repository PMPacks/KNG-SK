<?php

namespace hmmhmmmm\boss\cmd\args;

use hmmhmmmm\boss\libs\CortexPE\Commando\args\RawStringArgument;

class StringArgs extends RawStringArgument{

   public function getTypeName(): string{
      return "string";
   }
   
}