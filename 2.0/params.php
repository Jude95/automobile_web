<?php

//车身结构
$structure = array(
	"Targa","敞篷轿车","车厢/客货两用车","大客车","大空间轿式小汽车","哥伦比亚比索","溜背式","跑车","皮卡车","三厢车","箱形","越野车","载货平台/底盘"
);
//驱动方式
$drive = array(
	"后驱动","前驱动","全轮驱动","直接"
);
//燃料
$fuel = array(
	"柴油","柴油/电子","汽油","汽油/电子","汽油/汽车用燃气(液化石油气)","汽油/天然气 (压缩天然气)","汽油/乙醇","双燃料","替代燃料","天然气","压缩天然气","液化天然气"
);
//供油方式
$fuel_feed = array(
	"进气管内喷射","进气管内喷射/汽化器","进气管内喷射/直接喷射","预燃室发动机","直接喷射"
);
//发动机类型
$engine = array(
	"柴油","点燃式发动机","电机","混合动力","混合动力（汽油机/电动机）"
);
//配件类型
$part = array(
	"起动机","发电机","压缩机"
);
$data = array(
	"structure"=>$structure,
	"drive"=>$drive,
	"fuel"=>$fuel,
	"fuel_feed"=>$fuel_feed,
	"engine"=>$engine,
	"part"=>$part
);
echo json_encode($data);

?>