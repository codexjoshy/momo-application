<?php
namespace Database\Factories\Helpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;

    class FactoryHelper
    {
        /**
         * Undocumented function
         *
         * @param string | HasFactory $model
         * @return string
         */
        public static function getRandomModelId(string|HasFactory $model)
        {
            $count = $model::query()->count();
            if($count == 0){
                $modelId = $model::factory()->create()->id;
            }else{
                $modelId = rand(1, $count);
            }
            return $modelId;
        }
    }

?>
