<?php
/**
 * Classe de faz a conexão com o Banco de Dados
*/
class DbAdmin
{
    
	//definição das variáveis e constantes 
	private $tipo;
	private $conn;
	
	//Método que inicializa variáveis
	//Chamado de MÉTODO CONSTRUTOR
	public function DbAdmin($tipo='mysql') 
	{
		if (empty($tipo)) 
			$this->tipo = 'mysql';
		$this->tipo = $tipo;
	}
	
	//Método que conecta e seleciona a base sem receber parâmetros
	public function connectDefault() 
	{
		$host = 'localhost';
		$user = 'root';
		$pass = '';
		$base = 't-shaped';
		

                $this->conn = mysql_connect($host, $user, $pass);
                mysql_select_db($base);
                mysql_set_charset("UTF8", $this->conn);

	}
	

	//Método que executa uma instrução SQL
	public function query($sql)
	{ 
        	$res = mysql_query($sql, $this->conn);
		return $res;
	}
	
	//Método que recebe um "resultset" e retorna o número de linhas
	public function rows($res)
	{
		$num = mysql_num_rows($res);
		return $num;
	}
	
	//Método que recebe "resultset", linha e coluna e retorna um valor
	public function result($res, $lin, $col)
	{
		$val = mysql_result($res, $lin, $col);
		return $val;
	}
	
	//Método que recebe o "resultset" e retorna um vetor
	public function fetch($res)
	{
		$vet = mysql_fetch_array($res);
		return $vet;
	}
	
}
?>