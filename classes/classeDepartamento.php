<?php
header('Content-Type: text/html; charset=utf-8');
	class Departamento{
		
		//Conexão com o banco
		private $conexao;		
		
		//Propriedades do objeto
		private $iddepartamento;
		private $nome;
		private $prioridade;
		private $ativo;
		
		public function __construct($db){
			$this->conexao = $db;
		}
				
		public function selecionarTodos()
		{
			try{
				$stmt = $this->conexao->prepare("SELECT idDepartamento , nome , prioridadeDep FROM departamento WHERE ativo= :ativo");
				$stmt->execute(array("ativo" => $this->ativo));		
				while($linha = $stmt->fetch(PDO::FETCH_OBJ)){
					echo "<tr>";
					echo "<td>" . $linha->idDepartamento ."</td>" ;
					echo "<td>" . $linha->nome . "</td>" ;
					switch ($linha->prioridadeDep) {
						case '1':
							echo "<td id=".$linha->prioridadeDep ."> Baixa </td>" ;
							break;
						case '2':
							echo "<td id=".$linha->prioridadeDep ."> Média </td>" ;
							break;
							break;
						case '3':
							echo "<td id=".$linha->prioridadeDep ."> Alta </td>" ;
							break;	
						default:
							break;
					}				
					echo "</tr>";
				}
				$this->conexao = null;
			}
			catch(PDOException $e){
				echo 'Erro: ' . $e->getMessage();
			}
		}
		
		public function alterar()
		{
			try{
				$stmt = $this->conexao->prepare("UPDATE departamento SET nome = :nome , prioridadeDep = :prioridade WHERE idDepartamento = :id");
				$stmt->execute(array("nome" => $this->nome , "prioridade" => $this->prioridade , "id" =>  $this->iddepartamento));
				$this->conexao = null;			  
			}
			catch(PDOException $e){
				echo 'Erro: ' . $e->getMessage();
			}
		}
		
		public function excluir()
		{
			try{
				$stmt = $this->conexao->prepare("SELECT Departamento_idDepartamento FROM  usuario WHERE Departamento_idDepartamento = :id");
				$stmt->execute(array("id" => $this->iddepartamento));
				$numlinhas = $stmt->rowCount();
				if ($numlinhas == 0){
					$stmt = $this->conexao->prepare("DELETE FROM departamento  WHERE idDepartamento = :id");				
					$stmt->execute(array("id" => $this->iddepartamento));
				}else{
					$stmt = $this->conexao->prepare("UPDATE departamento SET ativo = :ativo WHERE idDepartamento = :id");				
					$stmt->execute(array(":ativo" => 0, "id" => $this->iddepartamento));
				}
				$this->conexao = null;	
			}
			catch(PDOException $e){
				echo 'Erro: ' . $e->getMessage();
			}
		}
		
		public function reativar()
		{			
			try{
				$stmt = $this->conexao->prepare("UPDATE departamento SET ativo = :ativo WHERE idDepartamento = :id");				
				$stmt->execute(array(":ativo" => 1, "id" => $this->iddepartamento));
				$this->conexao = null;			  
			}
			catch(PDOException $e){
				echo 'Erro: ' . $e->getMessage();
			}
		}
		
		public function inserir()
		{			
			try{
				$stmt = $this->conexao->prepare("INSERT INTO departamento(nome, prioridadeDep) VALUES(:nome,:prioridade)");
				$stmt->execute(array(':nome' => $this->nome , ':prioridade' => $this->prioridade )); 
				$this->conexao = null;   
			}
			catch(PDOException $e){
				echo 'Erro: ' . $e->getMessage();
			}			
		}
		
		public function gerarLista()
		{
			try{
				$stmt = $this->conexao->prepare("SELECT idDepartamento , nome FROM departamento WHERE ativo= :ativo");
				$stmt->execute(array("ativo" => 1));
				echo "<option selected disabled hidden value=0></option>";	
				while($linha = $stmt->fetch(PDO::FETCH_OBJ)){				
					echo "<option value=". $linha->idDepartamento .">" . $linha->nome ."</option>" ;
				}
			}
			catch(PDOException $e){
				echo 'Erro: ' . $e->getMessage();
			}
		}
		
		public function pesquisar($p)
		{
			try{
				$pesquisa = "%".$p."%";
				$stmt = $this->conexao->prepare("SELECT idDepartamento , nome , prioridadeDep 
												FROM departamento 
												WHERE ativo = :ativo 
												AND (nome LIKE :pesquisa OR idDepartamento LIKE :pesquisa OR prioridadeDep LIKE :pesquisa)");
				$stmt->execute(array("ativo" => $this->ativo , "pesquisa" => $pesquisa));				
				while($linha = $stmt->fetch(PDO::FETCH_OBJ)){
					echo "<tr>";
					echo "<td>" . $linha->idDepartamento ."</td>" ;
					echo "<td>" . $linha->nome . "</td>" ;
					switch ($linha->prioridadeDep) {
						case '1':
							echo "<td id=".$linha->prioridadeDep ."> Baixa </td>" ;
							break;
						case '2':
							echo "<td id=".$linha->prioridadeDep ."> Média </td>" ;
							break;
							break;
						case '3':
							echo "<td id=".$linha->prioridadeDep ."> Alta </td>" ;
							break;	
						default:
							break;
					}				
					echo "</tr>";
				}
				if($stmt->rowCount() == 0){
					echo "Nenhum registro encontrado!";
				}
				$this->conexao = null;
			}
			catch(PDOException $e){
				echo 'Erro: ' . $e->getMessage();
			}
		}
		
		//validação
		public function validaNome(){
			try{
				$stmt = $this->conexao->prepare("SELECT nome FROM departamento WHERE nome = :nome");
				$stmt->execute(array("nome"=> $this->nome));
				if($stmt->rowCount() != 0){
					$valNome = array("valido" => false);
				}
				else{
					$valNome = array("valido" => true);
				}
				echo json_encode($valNome);	
			}
			catch(PDOException $e){
				echo 'Erro: ' . $e->getMessage();
			}
		}
		
		//setters
		public function set_iddepartamento($i){
			$this->iddepartamento = $i;
		}
		
		public function set_nome($n){ 
        	$this->nome = $n;  
    	}
		
		public function set_prioridade($p){ 
        	$this->prioridade = $p;  
    	}
		
		public function set_ativo($a){ 
        	$this->ativo = $a;  
    	}
		
		//gettes
		public function get_nome(){ 
        	return $this->nome;  
    	}
		
		public function get_iddepartamento(){ 
        	return $this->iddepartamento;  
    	}
		
		public function get_prioridade(){ 
        	return $this->prioridade;  
    	}
		
		public function get_ativo(){ 
        	return $this->ativo;  
    	}	
	}
?>