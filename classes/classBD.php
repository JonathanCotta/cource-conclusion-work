<?php
header('Content-Type: text/html; charset=utf-8');
class Banco {
	
	//Dados para acesso ao banco
	private $host = "localhost";
	private $nomeBd = "sgc";
	private $usuario = "root";
	private $senha = "";
	public $conexao;
		
	//Funçãoo de conexão ao banco
	public function conectarBd() {
	
		$this->conexao = null;
		
		try {
			$this->conexao = new PDO("mysql:host=" .$this->host . ";dbname=" . $this->nomeBd, $this->usuario, $this->senha);
			$this->conexao->exec("SET CHARACTER SET utf8"); 
		}catch(PDOException $excecao){
			echo "Erro de conexão: " . $excecao->getMessage();
		}
	
		return $this->conexao;
	}
}