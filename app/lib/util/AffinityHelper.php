<?php
class AffinityHelper {

	//formula for first step
	public static function computeAffinityVolume($count){
		$result = 0;
		
		if($count > 0){
			$result = 1 - (1/sqrt($count));
		}
		
		return $result;
	}
	
	//formula for second step
	public static function computeAffinityRatio($count, $total_count, $category_count){
		$result = 0;
		
		if($total_count > 0 && $category_count > 0){
			$result = $count / $total_count;
			
			if($category_count > 1){
				$result = $result * (1 - (1/pow($category_count,2)));
			}
		}
		
		return $result;
	}
	
	//formula for third step
	public static function computeAffinityCategory($category_count){
		$result = 0;
		
		if($category_count > 0){
			$result = 1 - (1/$category_count);
		}
		
		return $result;
	}
	
	//formula for fourth step	(hour)
	public static function computeAffinityTime($time){
		$result = 0;
		
		if($time > 0){
			$result = 1 / sqrt($time);
		}
		
		return $result;
	}
	
	//compute user-category affinity by using categories count array
	public static function computeCategoryAffinity($tweets){
		$result = array();
		$total_count = 0;
		$category_count = 0;
		$count_array = array();
		
		$WEIGHT1 = 0.5;
		$WEIGHT2 = 0.2;
		$WEIGHT3 = 0.05;
		$WEIGHT3 = 0.25;
		
		foreach($tweets as $tweet){
			foreach($tweet['categories'] as $category=>$count){		//pass categories data array
				if(array_key_exists($category, $count_array)){
					$count_array[$category] = $count_array[$category] + $count;
				}
				else{
					$count_array[$category] = $count;
					$category_count++;
				}
				
				$total_count += $count;
			}
		}
		
		//echo "$total_count - $category_count\n";
		//echo print_r($count_array)."\n";
		
		foreach($count_array as $category=>$count){
			$affinity1 = self::computeAffinityVolume($count);
			$affinity2 = self::computeAffinityRatio($count, $total_count, $category_count);
			$affinity3 = self::computeAffinityCategory($category_count);
			$affinity4 = 1; //missing time consumption method
			
			$result[$category] = $affinity1 * $WEIGHT1 +
								 $affinity2 * $WEIGHT2 +
								 $affinity3 * $WEIGHT3 +
								 $affinity4 * $WEIGHT4;
		}
		
		//echo print_r($result)."\n";
		
		return $result;
	}
}	
	/*
	$tweet_a = array(
		'categories' => array(
			'sport' => 5,
			'music' => 3,
			'economic' => 2
		)
	);
	
	$tweet_b = array(
		'categories' => array(
			'sport' => 2,
			'music' => 9,
			'art' => 3
		)
	);
	
	$tweets = array();
	$tweets[] = $tweet_a;
	$tweets[] = $tweet_b;
	
	computeCategoryAffinity($tweets);
	*/
	//echo computeAffinityVolume(49)."\n";
	//echo computeAffinityRatio(100,200,100)."\n";
	//echo computeAffinityCategory(20)."\n";
	//echo computeAffinityTime(2)."\n";
?>
