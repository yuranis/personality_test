<?php

require_once(dirname(__FILE__) . '/lib.php');

require_login();
$courseid = optional_param('cid', 0, PARAM_INT);
$personality_test_a = array();

$extra = [5,7,10,13,23,25,61,68,71];
$intra = [2,9,49,54,63,65,67,69,72];
$sensi = [15,45,45,51,53,56,59,66,70];
$intui = [37,39,41,44,47,52,57,62,64];
$ratio = [1,4,6,18,20,48,50,55,58];
$emoti = [3,8,11,14,27,31,33,35,40];
$estru = [19,21,24,26,29,34,36,42,46];
$perce = [12,16,17,22,28,30,32,38,60];

$extra_res = 0;
$intra_res = 0;
$sensi_res = 0;
$intui_res = 0;
$ratio_res = 0;
$emoti_res = 0;
$estru_res = 0;
$perce_res = 0;

for ($i=1;$i<=72;$i++){
    $personality_test_a[$i] = optional_param("personality_test:q".$i, 0, PARAM_INT);
}

//var_dump($personality_test_a);

foreach($extra as $index => $value){
    $extra_res = $extra_res + $personality_test_a[$value];
}
foreach($intra as $index => $value){
    $intra_res = $intra_res + $personality_test_a[$value];
}
foreach($sensi as $index => $value){
    $sensi_res = $sensi_res + $personality_test_a[$value];
}
foreach($intui as $index => $value){
    $intui_res = $intui_res + $personality_test_a[$value];
}
foreach($ratio as $index => $value){
    $ratio_res = $ratio_res + $personality_test_a[$value];
}
foreach($emoti as $index => $value){
    $emoti_res = $emoti_res + $personality_test_a[$value];
}
foreach($estru as $index => $value){
    $estru_res = $estru_res + $personality_test_a[$value];
}
foreach($perce as $index => $value){
    $perce_res = $perce_res + $personality_test_a[$value];
}
//echo "$extra_res -- $intra_res -- $sensi_res -- $intui_res -- $ratio_res -- $emoti_res -- $estru_res -- $perce_res";

if ($courseid == SITEID && !$courseid) {
    redirect($CFG->wwwroot);
}


/*if( $accept != 1 ){
    //No accept, redirect
    $redirect = new moodle_url('/blocks/personality_test/view.php', array('cid'=>$courseid,'error'=>'1'));
    redirect($redirect);
}
*/
$redirect = new moodle_url('/course/view.php', array('id'=>$courseid));

if(save_personality_test($courseid,$extra_res,$intra_res,$sensi_res,$intui_res,$ratio_res,$emoti_res,$estru_res,$perce_res)){
    redirect($redirect, get_string('redirect_accept_success', 'block_personality_test') );
}else{
    redirect($redirect, get_string('redirect_accept_exist', 'block_personality_test') );
}
?>
