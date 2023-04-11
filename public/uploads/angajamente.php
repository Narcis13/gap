<?php


class Angajamente extends ApplicationController {
    /**
     * view
     * Retrieves rows from database.
     */
    public function viewcap() {
	
	$res = new Response();
        $res->success = true;
        $res->message = "Capitole bugetare";
		$sql="SELECT *\n
				FROM capbugetare c\n

				WHERE c.idClient =$this->clid and c.stare<>'inactiv' ";
				
		global $dbh;
        $rez=$dbh->select($sql);		
		$res->data = $rez;
        return $res->to_json();
    }
	
	public function stergcap(){
	
	  	/*$sql="DELETE FROM capbugetare c\n

				WHERE c.id =".$this->params->data->idcap;
		
	  	$sqlb="DELETE FROM artbugete c\n

				WHERE c.idCap =".$this->params->data->idcap;		
		global $dbh;
        $rez=$dbh->select($sql);	
        $rezb=$dbh->select($sqlb);	*/
		global $dbh;
     $dbh->setCurrent('capbugetare');		
	 $dbh->locateforDelete("WHERE id =".$this->params->data->idcap);
	      $dbh->setCurrent('artbugete');		
	 $dbh->locateforDelete("WHERE idCap =".$this->params->data->idcap);
		
		 echo "gata";
	
	}
	
	
	
	 public function update() {
        $res = new Response();
		global $dbh;
		$dbh->setCurrent('artbugete');
		 if (is_array($this->params)) {
            $res->data = array();
            foreach ($this->params as $data) {
			//$data->domeniu=null;
				
                if ($rec = User::update($data->id, $data)) {
                  //  array_push($res->data, $rec->to_hash());
                }
            }
            $res->success = true;
            $res->message = "Updated  records";
        } else {
			
           

            if ($rec = User::update($this->params->id, $this->params)) {
              //  $res->data = $rec->to_hash();


                    $res->success = true;
                    $res->message = "Updated record";
              
            } else {
                $res->message = "Failed to updated record " . $this->params->id;
                $res->success = false;
            }

        }
	
        return $res->to_json();
    }
	
	
	public function cquery(){
					
        $res = new Response();
        $res->success = true;
        $res->message = "cquery ";
		$sql="SELECT * FROM artbugete a\n

				WHERE a.idClient =$this->clid and a.idCap=" .$this->clause;
			
		global $dbh;
        $rez=$dbh->select($sql);		
				
        $res->data = $rez;
        return $res->to_json();
	   //echo $this->clause;
	}
	
	public function query(){
					
        $res = new Response();
        $res->success = true;
       
		/*$sql="SELECT aa.id, aa.dataang dataang,aa.nrdoc nrdoc,aa.dataprop dataprop, f.denumire numefurnizor, aa.detalii descriere, aa.suma suma, IF(aa.viza=1 , true,false) viza, CONCAT( co.numar,  ' din ', co.dindata ) ctrdetalii, comp.denumire compartiment\n
FROM anteteangajamente aa\n
INNER JOIN furnizori f ON aa.furnizorID = f.id\n
INNER JOIN contracte co ON aa.contractID = co.id\n
INNER JOIN compartimente comp ON aa.compID = comp.id\n
				WHERE aa.idClient =$this->clid and aa.stare='activ' ";*/
				
$sql="	SELECT aa.id,b.numecap numecap,b.artbug artbug, aa.dataang dataang, aa.nrdoc nrdoc,aut.obs numesefcomp, aa.dataprop dataprop,aa.dataang dataang,  aa.detalii descriere, aa.suma suma, IF( aa.viza =1,TRUE ,FALSE ) viza, comp.denumire compartiment, b.articole bugdetalii\n
FROM anteteangajamente aa\n
INNER JOIN compartimente comp ON aa.compID = comp.id\n
inner join authentication aut on aut.id=comp.idsef\n
INNER JOIN (\n
SELECT idAntet AS a,ang.numecap numecap,ang.artbug artbug, GROUP_CONCAT( artbug\n
ORDER BY artbug ASC\n 
SEPARATOR  ', ' ) AS articole\n
FROM angajamente ang\n
GROUP BY a\n
)b ON b.a = aa.id\n 
WHERE aa.idClient =$this->clid and aa.stare='activ' ".$this->clause;
			
		global $dbh;
        $rez=$dbh->select($sql);		
			 $res->message = $sql;	
        $res->data = $rez;
        return $res->to_json();
	   //echo $this->clause;
	}
	public function gest(){
	    $res = new Response();
        $res->success = true;
        $res->message = "query gestiuni ".$this->clause;

				
$sql="	SELECT id, concat(gestiune, ' > ',loc) gestiune FROM `structuri` \n
WHERE receptii=1 and idComp=".$this->clause;

			
		global $dbh;
        $rez=$dbh->select($sql);		
				
        $res->data = $rez;
        return $res->to_json();
	
	}
	
	
	public function viewc(){
					
        $res = new Response();
        $res->success = true;
        $res->message = "query angajamente c ";

				
$sql="	SELECT aa.id,b.numecap numecap,b.artbug artbug, aa.dataang dataang, aa.nrdoc nrdoc, aa.dataprop dataprop,aa.dataang dataang, f.denumire numefurnizor, aa.detalii descriere, aa.suma suma, IF( aa.viza =1,TRUE ,FALSE ) viza, CONCAT( co.numar,  ' din ', co.dindata ) ctrdetalii, comp.denumire compartiment, b.articole bugdetalii\n
FROM anteteangajamente aa\n
INNER JOIN furnizori f ON aa.furnizorID = f.id\n
INNER JOIN contracte co ON aa.contractID = co.id\n
INNER JOIN compartimente comp ON aa.compID = comp.id\n
INNER JOIN (\n
SELECT idAntet AS a,ang.numecap numecap,ang.artbug artbug, GROUP_CONCAT( artbug\n
ORDER BY artbug ASC\n 
SEPARATOR  ', ' ) AS articole\n
FROM angajamente ang\n
GROUP BY a\n
)b ON b.a = aa.id\n
WHERE aa.idClient =$this->clid and aa.stare='activ' and aa.viza=1 and comp.denumire='".$this->clause."'";
			
		global $dbh;
        $rez=$dbh->select($sql);		
				
        $res->data = $rez;
        return $res->to_json();
	   //echo $this->clause;
	}
	
	
	public function angsold(){
	global $dbh;
	        $res = new Response();
        $res->success = true;
        $res->message = "query angajamente sold ".$dbh->ancur;
	
	$sql="select ang.id, ang.dataang dataang, ang.nrdoc nrdoc, ang.detalii descriere, ang.suma suma,comp.denumire compartiment, c.denumire categorie, IF(sum(rec.suma)IS NULL ,ang.suma,(ang.suma-sum(rec.suma))) sold\n
     from anteteangajamente ang\n
	 INNER JOIN compartimente comp ON ang.compID = comp.id\n
     left join (select * from antetereceptii where stare='activ')  rec ON ang.id = rec.idAng\n
     left join angajamente a on a.idAntet=ang.id\n
     left join caategorii c on a.idcateg = c.id\n
	 WHERE ang.stare='activ' and ang.viza=1 and comp.denumire='$this->clause'\n
     group by ang.id\n
	 HAVING sold>0";
//	 left join receptii r on r.idAntet = rec.id\n am scos-o din queryul de mai sus
		
        $rez=$dbh->select($sql);	
	
		/*
select ang.id, ang.dataang dataang, ang.nrdoc nrdoc, ang.detalii descriere, ang.suma suma,comp.denumire compartiment , c.denumire categorie
     from anteteangajamente ang
	 INNER JOIN compartimente comp ON ang.compID = comp.id
     left join antetereceptii  rec ON ang.id = rec.idAng
     left join receptii r on r.idAntet = rec.id
     left join caategorii c on r.idcateg = c.id
	 WHERE ang.stare='activ' and ang.viza=1 and comp.denumire='SAMT'
     group by ang.id

		*/
	//print_r($rez);
        $res->data = $rez;
        return $res->to_json();
		
		
	}
	
	
		public function create() {
        $res = new Response();
		
		
        // Ugh, php...check if !hash
       if (is_array($this->params) && !empty($this->params) && preg_match('/^\d+$/', implode('', array_keys($this->params)))) {
            foreach ($this->params as $data) {
			$data->idClient=$this->clid;
			$azi=date("Y-m-d");
			
			//$data->dindata=$azi;
			$data->numecat=null;
            array_push($res->data, User::create($data)->to_hash());
            }
            $res->success = true;
            $res->message = "Created " . count($res->data) . ' records';
        } else {
				$this->params->idClient=$this->clid;
				$azi=date("Y-m-d");
			//	$numesef=$this->params->numesef;
				$this->params->numecat=null;
				
				//$firephp->log($this->params,'creez f nou');
            if ($rec =  User::create($this->params)) {
                $res->success = true;
				//$rec['numesef']=$numesef;
                $res->data = $rec->to_hash();
                $res->message = "Created record";
            } else {
                $res->success = false;
                $res->message = "Failed to create record";
            }
        }
        return $res->to_json();
    }
	
	public function angnou() {
			
			
		global $dbh;
		$res = new Response();
        $res->success = true;
        $sqlbis="SELECT nrdoc FROM anteteangajamente ORDER BY id DESC LIMIT 1";
       $rezb=$dbh->select($sqlbis);
	   
		if(count($rezb)>0){
	        $nrdoc=$rezb[0]['nrdoc']+1;
	    }
	    else
	    {
	       $nrdoc=1;
	    }
		
		$sql="INSERT INTO  `adata`.`anteteangajamente` (`dataprop` ,`tip` ,`detalii`  ,`dataang` ,`compID` ,`viza` ,`aprob` ,`suma` ,`stare` ,`idClient`,`nrdoc`) VALUES ( '".$this->params->data->dataprop."', ".$this->params->data->tip.",  '".$this->params->data->detalii."', '".$this->params->data->dataprop."', ".$this->params->data->compID.", 0, 0, ".$this->params->data->suma.", 'activ', ".$this->clid.", ".$nrdoc.");";
		$rez=$dbh->select($sql);		
		
        
		//$idcap=$rez;
	
       $res->data = $rez;	

	   $res->nrdoc = $nrdoc;
	   $res->message = $nrdoc;
        return $res->to_json();
		
			//echo $this->params->data->cod.$this->params->data->nume.$this->params->data->numesursa;
    }
	
	public function inactivang(){
	global $dbh;
	     $dbh->setCurrent('anteteangajamente');		
		 $a=array('stare'=>'inactiv');
	 $dbh->locateforEdit("WHERE id =".$this->params->angid,$a);
	echo $this->params->angid;
	
	}

	public function vizeaza(){
	global $dbh;
	
	$sqlbis="SELECT nrviza FROM regviza where userid=".$this->params->userid." and year(dataviza)='".$dbh->ancur."' ORDER BY id DESC LIMIT 1";
       $rezb=$dbh->select($sqlbis);
	   
		if(count($rezb)>0){
	        $nrviza=$rezb[0]['nrviza']+1;
	    }
	    else
	    {
	       $nrviza=1;
	    }
		
	if(strlen(strval($nrviza))==1) $nrvizac=$this->params->sig.'000'.$nrviza;
	if(strlen(strval($nrviza))==2) $nrvizac=$this->params->sig.'00'.$nrviza;
	if(strlen(strval($nrviza))==3) $nrvizac=$this->params->sig.'0'.$nrviza;
	if(strlen(strval($nrviza))>3) $nrvizac=$this->params->sig.$nrviza;
	
	 $dbh->setCurrent('regviza');	
	 $d=array('userid'=>$this->params->userid,
	          'nume'=>$this->params->obs,
			  'nrviza'=>$nrviza,
			  'nrvizac'=>$nrvizac,
			  'dataviza'=>date("Y-m-d H:i:s"),
			  'document'=>$this->params->doc,
			  'explicatii'=>$this->params->explic,
			  'compartiment'=>$this->params->comp,
			  'valoare'=>$this->params->valoare,
			  'stare'=>'activ');
			  
			  $dbh->Append($d);
	//var_dump($nrvizac);
	     $dbh->setCurrent('anteteangajamente');		
		 $a=array('viza'=>1,'numeviza'=>$nrvizac,'dataviza'=>date("Y-m-d"),'codang'=>$this->params->codang,'indic'=>$this->params->indic);
	 $dbh->locateforEdit("WHERE id =".$this->params->angid,$a);
	echo $this->params->codang;
	
	}
	
	public function calculcang(){
	global $dbh;
   // var_dump($dbh->ancur);	
	$sqlbis="SELECT SUM(a.suma) AS suma, ant.dataang dataang FROM angajamente a inner join anteteangajamente ant on ant.id = a.idAntet	WHERE a.numecap='".$this->params->cap."' and a.artbug='".$this->params->art."' and a.stare='activ' and year(ant.dataang)='".$dbh->ancur."'";
    $rezb=$dbh->select($sqlbis);
	   if($rezb[0]['suma']){
	        echo $rezb[0]['suma'];;
	    }
	    else
	    {
	       echo '0';
	    }
	
	}
	
	public function calcrecang(){
	global $dbh;
//var_dump($this->params);	
	$sqlbis="SELECT SUM(suma) suma  FROM `antetereceptii` WHERE idAng=".$this->params->idang." and stare = 'activ'";
    $rezb=$dbh->select($sqlbis);
	   if($rezb[0]['suma']){
	        echo $rezb[0]['suma'];;
	    }
	    else
	    {
	       echo '0';
	    }
	
	}
	
    public function updaterecang(){
	global $dbh;

//	$sqlbis="SELECT SUM(suma) suma  FROM `antetereceptii` WHERE idAng=".$this->params->idang." and stare = 'activ'";
  //  $rezb=$dbh->select($sqlbis);
  
  $sql1="update `anteteangajamente` SET `suma`=".$this->params->sumanoua."  WHERE id=".$this->params->idang;
  $sql2="update `angajamente` SET `suma`=".$this->params->sumanoua." ,`restdisp`=`disp`-".$this->params->sumanoua." WHERE idAntet=".$this->params->idang ;
  $rezb=$dbh->select($sql1);
  $rezb=$dbh->select($sql2);
		$res = new Response();
        $res->success = true;
	         $res->message = 'ok';
        return $res->to_json();
	    
	
	}
	
	public function printang(){
	
         $s=$this->clid.'#'.$this->params->aid;
		$ss=encrypt($s,'mobiz');
		echo $ss;
	}
  
}

