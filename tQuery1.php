<?php

class tQuery //by Jader Turci 2012 //A.C.Polizelli 2020
{
	public $server;
	public $connectionArray;
	public $connected;
	public $connection;
	public $stmt;
	public $hasResult;
	public $r; //row
	public $eof;
	public $getColTypes; //pegar ou nao o tipo de cada coluna quando der open
	public $colId;  // array com key para cada coluna (esse vai nos arrays de cada linha)
	public $colLabel; // array com nome de cada coluna (esse vai no visual da table)
	public $colType; // array com tipo de cada coluna (int=1 varchar=12 char=3 datetime=93 float=6 )
	public $colSize; //array com size de cada coluna
	public $rowsAffected;
	public $tipoFetch; 

	function __construct()
	{
		$this->connected=false;
		$this->hasResult=false;
		$this->getColTypes=false; //padrao = nao pegar os tipos das colunas p/ nao perder tempo
		$this->tipoFetch=SQLSRV_FETCH_ASSOC;
	    $numargs = func_num_args(); //se veio parametro db
	    if ($numargs == 1)
	    {
	        $this->db(func_get_arg(0));
	    }
	}

	function connect()
	{
		//se nao está conectado, conecta agora
		if (!$this->connected)
		{
			for(;;)
			{
				$this->connection=sqlsrv_connect($this->server,$this->connectionArray);
				if ($this->connection === false)
				{
					$errors=sqlsrv_errors();
					print_r($errors);
					print_r($this->server);
					//print_r($this->connectionArray);
					exit();
					
					/*
					$errors=sqlsrv_errors();
					$erroPrelogin=false;
					foreach($errors as $error)
					{
						$msg=$error['message'];
						if (strpos($msg,'prelogin')!==false)//se o erro foi prelogin
						{
							$erroPrelogin=true;
							break;
						}
					}
					if ($erroPrelogin)
					{
						continue; //tenta novamente
					}
					else
					{
						print_r($errors);
						exit();
					}
					*/
				}
				else
				{
					break; //tudo ok
				}
			}
			$connected=true;
			//$this->connected=true;
		}
	}

	function errorDetails()
	{
		if( ($errors = sqlsrv_errors() ) != null) 
		{
			foreach( $errors as $error ) 
			{
				return "SQLSTATE: ".$error[ 'SQLSTATE']." code: ".$error[ 'code']." message: ".$error[ 'message'];
			}
		}
	}

	//----------------------------------------------------------------------
	public function getParams() {
		return $this->paramsArray;
	}
		
	public function setParams($params) {
		$this->paramsArray= $params;
	}
	
	function open($cmd)
	{
		$this->connect();
		//executa a query
		$this->stmt = sqlsrv_query( $this->connection, $cmd) or die(print_r( "<span id='msgErro'>Erro no comando [".$cmd."] " . $this->errorDetails() . "</span>", true));
		$this->hasResult=true; //indica que tem resultado
		$this->eof=false;
		if ($this->getColTypes)
		{
		  $this->colId=array();
		  $this->colLabel=array();
		  $this->colType=array();
		  $this->colSize=array();
		  foreach(sqlsrv_field_metadata($this->stmt) as $f) 
		  {
			//fieldData elements:
			//Name,Type,Size,Precision,Scale,Nullable
			$this->colId[]   =$f['Name'];
			$this->colLabel[]=$f['Name'];
			$this->colType[] =$f['Type'];
			$this->colSize[] =$f['Size'];
		  }
		}	
		
		$this->next();
		return $this->r;
	}
		
	function exec($cmd)
	{
		$this->connect();
		$this->stmt = sqlsrv_prepare( $this->connection, $cmd) or die(print_r( "<span id='msgErro'>Erro no comando [".$cmd."] " . $this->errorDetails() . "</span>", true));
		if(!sqlsrv_execute($this->stmt)) die(print_r( "<span id='msgErro'>Erro no comando [".$cmd."] " . $this->errorDetails() . "</span>", true));
		$this->rowsAffected = $this->rows_affected();
		sqlsrv_free_stmt( $this->stmt);
		sqlsrv_close( $this->connection);		
		//$this->hasResult=true; //indica que tem resultado
	}
	
	function next()
	{
		$this->r=sqlsrv_fetch_array( $this->stmt, $this->tipoFetch);
		if ($this->r ==false)
		{
			$this->eof=true;
		}
		return $this->r;
	}

	function rows_affected()
	{
		return sqlsrv_rows_affected($this->stmt);
	}

	function close()
	{
		if ($this->hasResult) sqlsrv_free_stmt($this->stmt);
		if ($this->connected) sqlsrv_close($this->connection);
	}

    function db($db)
	{
		//echo __LINE__ . "[db=$db]";
	    //$pwd="19682005";
	    //$usr="money";

		$usr="money";
		$pwd="19682005";

		/*if ($db=='dc3Rib')
		{
			$this->server="SRVDC3TTS01";
			$database=$db;
		}
		else if ($db=='cbaBra')
		{
			$this->server="10.12.26.25";
			$database="cbaBra";
		}
		else if ($db=='protheusCardinalTeste')
		{
			$this->server="10.10.0.75";
			$database="PROTHEUS12_TESTE";//_CARDINAL";
		}
		else if ($db=='protheusTeste')
		{
			$this->server="10.10.0.75";
			$database="PROTHEUS12_TESTE";
		}
		else if ($db=='cardTeste')
		{
			$this->server="10.10.0.75";
			$database="cardTeste";
		}
		else if ($db=='tec')
		{
			$this->server="10.10.0.75";
			$database="gm";
		}
		else if ($db=='gmTeste')
		{
			$this->server="10.10.0.75";
			$database="gm";
		}
		else if ($db=='protheusTecTeste')
		{
			$this->server="10.10.0.75";
			$database="PROTHEUS12_TOTVSIP";
		}
		else //demais bancos 
		{*/
			//$this->server="10.11.10.25";
			$this->server="10.11.10.25";
			$database=$db;
		//}
	        
		$this->connectionArray=array("UID"=>$usr, "PWD"=>$pwd, "Database"=>$database);    
	}

	//codifica em JSON em string
	function jflds($s)
    {
         return '"' . $s . '":"' . $this->r[$s] . '",'; 
    }
	
	//codifica em JSON em inteiro
    function jfldi($s)
    {
         $i=$this->r[$s]; 
         if ($i=='') $i='0';
         return '"' . $s . '":' . $i . ','; 
    }
	
	//codifica em JSON em float
    function jfldf($s)
    {
         $f=$this->r[$s]; 
         if ($f=='') $f='0';
         return '"' . $s . '":' . $f . ','; 
    }
	
	//codifica em JSON em data
    function jfldd($s)
    {
         $i=$this->r[$s]; 
         if ($i=='') $i='0';
         return '"' . $s . '":' . $i->format('d/m/Y') . ','; 
    }

} //fim de class tQuery


/************ exemplo de uso *************                                                                                                                                  */$dbpass="19682005";/*
$q=new tQuery();
$q->server="venus\sqlexpress";
$q->connectionArray=array( "UID"=>"money", "PWD"=>"19682005", "Database"=>"Our oBase");

$q->open("SELECT top 1 * from usuario");
while(!$q->eof)
{
    echo 'Nome='.$q->r['Nome'].' setor='.$q->r['CodSetor'].'<br/>';
	$q->next();
}

$q2=new tQuery();
$q2->connectionArray=$q->connectionArray;
$q2->server=$q->server;

$q2->open("SELECT top 1 * from usuario where codsetor='EXP'");
while(!$q2->eof)
{
    echo 'Nome='.$q2->r['Nome'].' setor='.$q2->r['CodSetor'].'<br/>';
	$q2->next();
}

echo 'updating<br>';

$q2->exec(
	"update usuario ".
	"set codsetor=case when codSetor='AAA' then 'BBB' else 'AAA' end ".
	"where username='jader'");
	
echo 'rows='.strval($q->rows_affected());

$q2->close();
**************/

?>
