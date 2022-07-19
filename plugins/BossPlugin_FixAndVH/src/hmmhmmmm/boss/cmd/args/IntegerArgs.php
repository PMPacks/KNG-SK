<?php

namespace hmmhmmmm\boss\cmd\args;

use hmmhmmmm\boss\libs\CortexPE\Commando\args\IntegerArgument;

class IntegerArgs extends IntegerArgument{

   public function getTypeName(): string{
      return "int";
   }
   
}