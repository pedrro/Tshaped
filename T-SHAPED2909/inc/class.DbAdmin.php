<?php
/**
 * Comentários sobre a classe e etc, etc.
 * @author 	
 * @version 2.0
 */
class DbAdmin
{
	//definição das variáveis e constantes (propriedades)
	private $tipo;
	private $conn;
	
	//m�todo que inicializa vari�veis (propriedades)
	//chamado de M�TODO CONSTRUTOR
	public function DbAdmin($tipo='mysql') 
	{
		if (empty($tipo)) 
			$this->tipo = 'mysql';
		$this->tipo = $tipo;
	}
	
	//m�todo que conecta e seleciona a base sem receber par�metros
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
	

	//m�todo que executa uma instru��o SQL
	public function query($sql)
	{
        	$res = mysql_query($sql, $this->conn);// or die('Bug: '.mysql_error());
		return $res;
	}
	
	//m�todo que recebe um "resultset" e retorna o nro de linhas
	public function rows($res)
	{
		$num = mysql_num_rows($res);
		return $num;
	}
	
	//m�todo que recebe "resultset", linha e coluna e retorna um valor
	public function result($res, $lin, $col)
	{
		$val = mysql_result($res, $lin, $col);
		return $val;
	}
	
	//m�todo que recebe o "resultset" e retorna um vetor
	public function fetch($res)
	{
		$vet = mysql_fetch_array($res);
		return $vet;
	}
	
}
?>