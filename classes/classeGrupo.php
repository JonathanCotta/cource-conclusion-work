<?php
header('Content-Type: text/html; charset=utf-8');
	class Grupo{
		
		//Conexão com o banco 
		private $conexao;		
		
		//Propriedades do objeto
		private $idgrupo;
		private $nome;
		private $ativo;

		public function __construct($db){
			$this->conexao = $db;
		}		
		
		public function selecionarTodos()
		{
			try{
				$stmt = $this->conexao->prepare("SELECT idGrupo , nome FROM grupo WHERE ativo= :ativo");
				$stmt->execute(array("ativo" => $this->ativo));	
				while($linha = $stmt->fetch(PDO::FETCH_OBJ)){
					echo "<tr>";
					echo "<td>" . $linha->idGrupo ."</td>" ;
					echo "<td>" . $linha->nome . "</td>" ;				
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
				$stmt = $this->conexao->prepare("UPDATE grupo SET nome = :nome  WHERE idGrupo = :id");
				$stmt->execute(array("nome" => $this->nome , "id" =>  $this->idgrupo));
			  	$this->conexao = null;
			}
			catch(PDOException $e){
				echo 'Erro: ' . $e->getMessage();
			}
		}
		
		public function excluir()
		{
			try{
				$stmt = $this->conexao->prepare("SELECT Grupo_idGrupo FROM  atendente WHERE Grupo_idGrupo= :id");
				$stmt->execute(array("id" => $this->idgrupo));
				$numlinhas = $stmt->rowCount();
				if ($numlinhas == 0){
					$stmt = $this->conexao->prepare("DELETE FROM grupo WHERE idGrupo = :id");				
					$stmt->execute(array("id" => $this->idgrupo));
				}else{
					$stmt = $this->conexao->prepare("UPDATE grupo SET ativo = :ativo WHERE idGrupo = :id");				
					$stmt->execute(array(":ativo" => 0, "id" => $this->idgrupo));
				}
				$this->conexao = null;						  
			}
			catch(PDOException $e){
				echo 'Erro: ' . $e->getMessage();
			}
		}
		
		public function inserir()
		{
			try{
				$stmt = $this->conexao->prepare("INSERT INTO grupo(nome) VALUES(:nome)");
				$stmt->execute(array(':nome' => $this->nome)); 
				$this->conexao = null; 
			}
			catch(PDOException $e){
				echo 'Erro: ' . $e->getMessage();
			}	
		}
		
		public function reativar()
		{			
			try{
				$stmt = $this->conexao->prepare("UPDATE grupo SET ativo = :ativo WHERE idGrupo = :id");				
				$stmt->execute(array(":ativo" => 1, "id" => $this->idgrupo));
				$this->conexao = null; 
			  
			}
			catch(PDOException $e){
				echo 'Erro: ' . $e->getMessage();
			}
		}
		
		public function gerarLista()
		{
			try{
				$stmt = $this->conexao->prepare("SELECT idGrupo , nome FROM grupo WHERE ativo= :ativo");
				$stmt->execute(array("ativo" => 1));
				echo "<option selected disabled hidden value=0></option>";
				while($linha = $stmt->fetch(PDO::FETCH_OBJ)){				
					echo "<option value=". $linha->idGrupo .">" . $linha->nome ."</option>" ;
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
				$stmt = $this->conexao->prepare("SELECT * FROM grupo 
												WHERE ativo = :ativo 
												AND (nome LIKE :pesquisa OR idGrupo LIKE :pesquisa) ");
				$stmt->execute(array("ativo" => $this->ativo , "pesquisa" => $pesquisa));				
				while($linha = $stmt->fetch(PDO::FETCH_OBJ)){
					echo "<tr>";
					echo "<td>" . $linha->idGrupo ."</td>" ;
					echo "<td>" . $linha->nome . "</td>" ;						
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
				$stmt = $this->conexao->prepare("SELECT nome FROM grupo WHERE nome = :nome");
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
		public function set_idgrupo($i){
			$this->idgrupo = $i;
		} 
		
		public function set_nome($n){ 
        	$this->nome = $n;  
    	}
		
		public function set_ativo($a){ 
        	$this->ativo = $a;  
    	}
		
		//gettes
		public function get_nome(){ 
        	return $this->nome;  
    	}
		
		public function get_idgrupo(){ 
        	return $this->idgrupo;  
    	}
		
		public function get_ativo(){ 
        	return $this->ativo;  
    	}				
     }
?>