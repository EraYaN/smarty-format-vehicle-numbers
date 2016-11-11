<?php
/*if(!class_exists('EntityTypes')){
    abstract class EntityTypes
    {
        const coaches = 'c';
        const locomotives = 'l';
    }
}
if(!class_exists('VehicleNumberFormats')){
    abstract class VehicleNumberFormats
    {
        const long = 'l';
        const short = 's';
    }
}*/
function smarty_modifier_format_vehicle_numbers($num, $vehiclekeepermarking = 'NS', $country = 'NL', $type = EntityTypes::coaches, $format = VehicleNumberFormats::long){
    if(!isset($num)){
        trigger_error("format_vehicle_numbers: param 'num' missing");
        return;
    }
    $formats = array();
    $formats[EntityTypes::coaches][VehicleNumberFormats::long] = '%s %s %s %s %s-%s %s-%s';
    $formats[EntityTypes::locomotives][VehicleNumberFormats::long] = '%s %s %s %s %s %s-%s';
    $formats[EntityTypes::coaches][VehicleNumberFormats::short] = '%s-%s';
    $formats[EntityTypes::locomotives][VehicleNumberFormats::short] = 'E%s-%s';
	$len = strlen($num);
    $args = array();
    if($format==VehicleNumberFormats::short){
        if($type==EntityTypes::coaches){
            if($len!=5){
                //trigger_error("format: 'num' not properly formatted");
                return $vehiclekeepermarking.' '.$country.' '.$num;
            }
            $args[] = mb_substr($num,0,2);
            $args[] = mb_substr($num,2,3);
        } else if($type==EntityTypes::locomotives){
            if($len!=6||$len!=7){
                //trigger_error("format: 'num' not properly formatted");
                return $vehiclekeepermarking.' '.$country.' '.$num;
            }
			if($len==6){
				$args[] = mb_substr($num,0,3);
				$args[] = mb_substr($num,3,3);
			} elseif($len==7){
				$args[] = mb_substr($num,0,3);
				$args[] = mb_substr($num,3,4);
			}
        }
    } else if($format==VehicleNumberFormats::long){
        $args[] = $vehiclekeepermarking;
        $args[] = $country;
        if($len!=12){
            //trigger_error("format: 'num' not properly formatted");
            return $vehiclekeepermarking.' '.$country.' '.$num;
        }
        if($type==EntityTypes::coaches){
            $args[] = mb_substr($num,0,2);
            $args[] = mb_substr($num,2,2);
            $args[] = mb_substr($num,4,2);
            $args[] = mb_substr($num,6,2);
            $args[] = mb_substr($num,8,3);
            $args[] = mb_substr($num,11,1);
        } else if($type==EntityTypes::locomotives){
            $args[] = mb_substr($num,0,2);
            $args[] = mb_substr($num,2,2);
            $args[] = mb_substr($num,4,4);
            $args[] = mb_substr($num,8,3);
            $args[] = mb_substr($num,11,1);
        }
    }
    if(isset($formats[$type][$format])){
        return vsprintf($formats[$type][$format],$args);
    } else {
        return $vehiclekeepermarking.' '.$country.' '.$num;
    }

}