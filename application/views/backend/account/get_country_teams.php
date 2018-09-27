<?php 
// $option = "<option value=''>".get_phrase('select')."</option>"; 
foreach($teams as $team){

	$option .= "<option value='".$team->team_id."'>".$team->name."</option>";
   	
}

echo $option;

?>