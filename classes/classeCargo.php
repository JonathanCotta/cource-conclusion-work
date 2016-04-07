<?php
header('Content-Type: text/html; charset=utf-8');
	class Cargo{
		
		//Conexão com o banco
		private $conexao;		
		
		//Propriedades do objeto
		private $idcargo;
		private $nome;
		private $prioridade;
		private $ativo;		

		public function __construct($db){
			$this->conexao = $db;
		}
					
		public function selecionarTodos()
		{
			try{
				$stmt = $this->conexao->prepare("SELECT idCargo , nome, prioridadeCargo FROM cargo WHERE ativo= :ativo");
				$stmt->execute(array("ativo" => $this->ativo));				
				
				while($linha = $stmt->fetch(PDO::FETCH_OBJ)){
					echo "<tr>";
					echo "<td id=lt>" . $linha->idCargo ."</td>" ;
					echo "<td>" . $linha->nome . "</td>" ;
					switch ($linha->prioridadeCargo) {
						case '1':
							echo "<td id=".$linha->prioridadeCargo ."> Baixa </td>" ;
							break;
						case '2':
							echo "<td id=".$linha->prioridadeCargo ."> Média </td>" ;
							break;
							break;
						case '3':
							echo "<td id=".$linha->prioridadeCargo ."> Alta </td>" ;
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
				$stmt = $this->conexao->prepare("UPDATE cargo SET nome = :nome , prioridadeCargo = :prioridade WHERE idCargo = :id");				
				$stmt->execute(array("nome" => $this->nome , "prioridade" => $this->prioridade , "id" =>  $this->idcargo));
				$this->conexao = null; 			  
			}
			catch(PDOException $e){
				echo 'Erro: ' . $e->getMessage();
			}			
		}
		
		public function excluir()
		{				
			try{
				$stmt = $this->conexao->prepare("SELECT Cargo_idCargo FROM  usuario WHERE Cargo_idCargo = :id");
				$stmt->execute(array("id" => $this->idcargo));
				$numlinhas = $stmt->rowCount();
				if ($numlinhas == 0){
					$stmt = $this->conexao->prepare("DELETE FROM cargo  WHERE idCargo = :id");				
					$stmt->execute(array("id" => $this->idcargo));
				}else{
					$stmt = $this->conexao->prepare("UPDATE cargo SET ativo = :ativo WHERE idCargo = :id");				
					$stmt->execute(array(":ativo" => 0, "id" => $this->idcargo));
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
				$stmt = $this->conexao->prepare("UPDATE cargo SET ativo = :ativo WHERE idCargo = :id");				
				$stmt->execute(array(":ativo" => 1 , "id" => $this->idcargo));
				$this->conexao = null;			  
			}
			catch(PDOException $e){
				echo 'Erro: ' . $e->getMessage();
			}
		}
		
		public function inserir()
		{				
			try{
				$stmt = $this->conexao->prepare("INSERT INTO cargo(nome, prioridadeCargo) VALUES(:nome,:prioridade)");		
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
				$stmt = $this->conexao->prepare("SELECT idCargo , nome FROM cargo WHERE ativo= :ativo");
				$stmt->execute(array("ativo" => 1));
				echo "<option selected disabled hidden value=0></option>";		
				while($linha = $stmt->fetch(PDO::FETCH_OBJ)){				
					echo "<option value=". $linha->idCargo .">" . $linha->nome ."</option>" ;
				}
				$this->conexao = null;
			}
			catch(PDOException $e){
				echo 'Erro: ' . $e->getMessage();
			}
			
		}
		
		public function pesquisar($p)
		{
			try{
				$pesquisa = "%".$p."%";
				$stmt = $this->conexao->prepare("SELECT idCargo , nome, prioridadeCargo 
												FROM cargo WHERE ativo = :ativo 
												AND (nome LIKE :pesquisa OR idCargo LIKE :pesquisa OR prioridadeCargo = :pesquisa) ");
				$stmt->execute(array("ativo" => $this->ativo , "pesquisa" => $pesquisa));				
				while($linha = $stmt->fetch(PDO::FETCH_OBJ)){
					echo "<tr>";
					echo "<td>" . $linha->idCargo ."</td>" ;
					echo "<td>" . $linha->nome . "</td>" ;
					switch ($linha->prioridadeCargo) {
						case '1':
							echo "<td id=".$linha->prioridadeCargo ."> Baixa </td>" ;
							break;
						case '2':
							echo "<td id=".$linha->prioridadeCargo ."> Média </td>" ;
							break;
							break;
						case '3':
							echo "<td id=".$linha->prioridadeCargo ."> Alta </td>" ;
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
				$stmt = $this->conexao->prepare("SELECT nome FROM cargo WHERE nome = :nome");
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
		
		
		
		// setters
		public function set_idcargo($i){
			$this->idcargo = $i;
		} 
		
		public function set_nome($n){ 
        	$this->nome = $n;  
    	}
		
		public function set_ativo($a){ 
        	$this->ativo = $a;  
    	}
		
		public function set_prioridade($p){ 
        	$this->prioridade = $p;  
    	}
		
		//gettes
		public function get_nome(){ 
        	return $this->nome;  
    	}
		
		public function get_idcargo(){ 
        	return $this->idcargo;  
    	}
		
		public function get_prioridade(){ 
        	return $this->prioridade;  
    	}
		
		public function get_ativo(){ 
        	return $this->ativo;  
    	}		
	}
?>