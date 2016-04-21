<?php
class Valuta {
	
	private $base;
	
	public function __construct($sql_connect) {
		$this->base = $sql_connect;
	}
	
		public function updatekurses(){
		$data=@file_get_contents('http://bnm.md/ru/official_exchange_rates?get_xml=1&date='.date("d.m.Y"));
		$data = new SimpleXMLElement($data);

		if(!isset($kurs)) $kurs = 1;
		
		for($i=0;$i<sizeof($data->Valute);$i++){
			$valid=$this->qr("select id from valute where code='".$data->Valute[$i]->CharCode."'",'id');
			$kurs=($data->Valute[$i]->Nominal>1)?$data->Valute[$i]->Value/$data->Valute[$i]->Nominal:$data->Valute[$i]->Value;
			//if(!is_numeric($kurs)) $kurs = 1;
			//if($kurs <= 0) $kurs == 1;
//echo '['.$kurs.']';			
			$rub=1/$kurs;
			if(!$valid){
				$sql = "insert into valute(name,code,status,kurs,rub)
								values('".$data->Valute[$i]->Name."','".$data->Valute[$i]->CharCode."','1','$kurs','$rub')";
				mysql_query($sql);
			}
			else{
				$sql = "update valute set kurs='$kurs',rub='$rub' where id=$valid";
				mysql_query($sql);
				
			}
		}
		
		mysql_query("update advaweb_configs set `value`='".date("Y-m-d")."' where secretkey='updatekursvalute'");
		return;
	}                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            

	
}