<?php
header('Content-Type: text/html; charset=utf-8');
	class Categoria{
		
		//Conexão com o banco 
		private $conexao;		
		
		//Propriedades do objeto
		private $idcategoria;
		private $plataforma; // id da plataforma
		private $nome;
		private $prioridade;
		private $ativo;

		public function __construct($db){
			$this->conexao = $db;
		}
				
		public function selecionarTodos()
		{
			try{
				$stmt = $this->conexao->prepare("SELECT c.idCategoria , C.nome , c.prioridadeCat , p.idPlataforma , p.nome AS nomePlataforma 
												FROM categoria AS c INNER JOIN  plataforma AS  p 
												ON c.Plataforma_idPlataforma = p.idPlataforma 
												WHERE c.ativo = :ativo");
				$stmt->execute(array("ativo" => $this->ativo));	
				while($linha = $stmt->fetch(PDO::FETCH_OBJ)){			
					echo "<tr>";
					echo "<td>" . $linha->idCategoria ."</td>" ;
					echo "<td>" . $linha->nome . "</td>" ;
					echo "<td id=".$linha->idPlataforma.">" . $linha->nomePlataforma . "</td>" ;
					switch ($linha->prioridadeCat) {
						case '1':
							echo "<td id=".$linha->prioridadeCat ."> Baixa </td>" ;
							break;
						case '2':
							echo "<td id=".$linha->prioridadeCat ."> Média </td>" ;
							break;
							break;
						case '3':
							echo "<td id=".$linha->prioridadeCat ."> Alta </td>" ;
							break;	
						default:
							break;
					}
					echo "<tr>";
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
				$stmt = $this->conexao->prepare("UPDATE categoria SET nome = :nome , prioridadeCat = :prioridade , Plataforma_idPlataforma = :plataforma WHERE idCategoria = :id");
				$stmt->execute(array("nome" => $this->nome , "prioridade" => $this->prioridade ,"plataforma" =>  $this->plataforma , "id" =>  $this->idcategoria));						  
				$this->conexao = null;
			}
			catch(PDOException $e){
				echo 'Erro: ' . $e->getMessage();
			}
		}
		
		public function excluir()
		{
			try{
				$stmt = $this->conexao->prepare("SELECT Categoria_idCategoria FROM chamado WHERE Categoria_idCategoria = :id");
				$stmt->execute(array("id" => $this->idcategoria));
				$numlinhas = $stmt->rowCount();
				if ($numlinhas == 0){
					$stmt = $this->conexao->prepare("DELETE FROM categoria  WHERE idCategoria = :id");				
					$stmt->execute(array("id" => $this->idcategoria));
				}else{
					$stmt = $this->conexao->prepare("UPDATE categoria SET ativo = :ativo WHERE idCategoria = :id");				
					$stmt->execute(array(":ativo" => 0, "id" => $this->idcategoria));
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
				$stmt = $this->conexao->prepare("UPDATE categoria SET ativo = :ativo WHERE idCategoria = :id");
				$stmt->execute(array("ativo" => 1, "id" =>  $this->idcategoria));						  
				$this->conexao = null;
			  
			}
			catch(PDOException $e){
				echo 'Erro: ' . $e->getMessage();
			}
		}
		
		public function inserir()
		{			
			try{
				$stmt = $this->conexao->prepare("INSERT INTO categoria(nome, prioridadeCat,Plataforma_idPlataforma) VALUES(:nome , :prioridade, :plataforma)");
				$stmt->execute(array(":nome" => $this->nome , ":prioridade" => $this->prioridade ,":plataforma" =>  $this->plataforma ));
				echo "chegou";
				$this->conexao = null;	
			}
			catch(PDOException $e){
				echo 'Erro: ' . $e->getMessage();
			}			
		}
		
		public function gerarLista()
		{
			try{
				$stmt = $this->conexao->prepare("SELECT idCategoria , nome FROM  categoria WHERE Plataforma_idPlataforma = :plataforma OR nome = :outra");
				$stmt->execute(array("plataforma" =>  $this->plataforma, "outra"=>"Outra" ));
								
				echo "<option selected disabled hidden value=0></option>";
											
				while($linha = $stmt->fetch(PDO::FETCH_OBJ)){				
					echo "<option value=". $linha->idCategoria .">" . $linha->nome ."</option>" ;
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
				$stmt = $this->conexao->prepare("SELECT c.idCategoria , C.nome , c.prioridadeCat , p.idPlataforma , p.nome AS nomePlataforma 
												FROM categoria AS c INNER JOIN  plataforma AS  p 
												ON c.Plataforma_idPlataforma = p.idPlataforma 
												WHERE c.ativo = :ativo
												AND (c.nome LIKE :pesquisa OR c.idCategoria LIKE :pesquisa OR c.prioridadeCat LIKE :pesquisa OR p.nome LIKE :pesquisa)");				
				$stmt->execute(array("ativo" => $this->ativo , "pesquisa" => $pesquisa));				
				while($linha = $stmt->fetch(PDO::FETCH_OBJ)){			
					echo "<tr>";
					echo "<td>" . $linha->idCategoria ."</td>" ;
					echo "<td>" . $linha->nome . "</td>" ;
					echo "<td id=".$linha->idPlataforma.">" . $linha->nomePlataforma . "</td>" ;					
					switch ($linha->prioridadeCat) {
						case '1':
							echo "<td id=".$linha->prioridadeCat ."> Baixa </td>" ;
							break;
						case '2':
							echo "<td id=".$linha->prioridadeCat ."> Média </td>" ;
							break;
							break;
						case '3':
							echo "<td id=".$linha->prioridadeCat ."> Alta </td>" ;
							break;	
						default:
							break;
					}
					echo "<tr>";
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
				$stmt = $this->conexao->prepare("SELECT nome FROM categoria WHERE nome = :nome AND Plataforma_idPlataforma = :plataforma");
				$stmt->execute(array("nome"=> $this->nome , "plataforma"=> $this->plataforma));
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
		public function set_nome($n){ 
        	$this->nome = $n;  
    	}
		
		public function set_ativo($a){ 
        	$this->ativo = $a;  
    	}
		
		public function set_prioridade($p){ 
        	$this->prioridade = $p;  
    	}
		
		public function set_idcategoria($i){ 
        	$this->idcategoria = $i;  
    	}
		
		public function set_plataforma($p){ 
        	$this->plataforma = $p;  
    	}
		//gettes
		public function get_nome(){ 
        	return $this->nome;  
    	}
		
		public function get_idcategoria(){ 
        	return $this->idcategoria;  
    	}
		
		public function get_prioridade(){ 
        	return $this->prioridade;  
    	}
		
		public function get_ativo(){ 
        	return $this->ativo;  
    	}
			
		public function get_plataforma(){ 
        	return $this->plataforma;  
    	}
	}
?>