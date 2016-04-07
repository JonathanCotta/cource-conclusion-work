<?php
header('Content-Type: text/html; charset=utf-8');
	class Plataforma{
		
		//Conexão com o banco 
		private $conexao;		
		
		//Propriedades do objeto
		private $idplataforma;
		private $nome;
		private $idgrupo; // id do grupo
		private $ativo;

		public function __construct($db){
			$this->conexao = $db;
		}
		
		public function selecionarTodos()
		{
			try{
				$stmt = $this->conexao->prepare("SELECT p.idPlataforma , p.nome , g.nome AS nomeGrupo , g.idGrupo 
												FROM plataforma AS p INNER JOIN grupo AS g 
												ON p.Grupo_idGrupo = g.idGrupo 
												WHERE p.ativo = :ativo");
				$stmt->execute(array("ativo" => $this->ativo));	
				while($linha = $stmt->fetch(PDO::FETCH_OBJ)){
					echo "<tr>";
					echo "<td>" . $linha->idPlataforma ."</td>" ;
					echo "<td>" . $linha->nome . "</td>" ;
					echo "<td id=".$linha->idGrupo.">" . $linha->nomeGrupo . "</td>" ;
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
				$stmt = $this->conexao->prepare("UPDATE plataforma SET nome = :nome , Grupo_idGrupo = :idgrupo WHERE idplataforma = :idplataforma");
				$stmt->execute(array("nome" => $this->nome , "idgrupo" => $this->idgrupo , "idplataforma" =>  $this->idplataforma));
				$this->conexao = null;
			}
			catch(PDOException $e){
				echo 'Erro: ' . $e->getMessage();
			}
		}
		
		public function excluir()
		{
			try{
				$stmt = $this->conexao->prepare("SELECT Plataforma_idPlataforma FROM  categoria WHERE Plataforma_idPlataforma = :id");
				$stmt->execute(array("id" => $this->idPlataforma));
				$numlinhas = $stmt->rowCount();
				if ($numlinhas == 0){
					$stmt = $this->conexao->prepare("DELETE FROM plataforma WHERE idPlataforma = :id");				
					$stmt->execute(array("id" => $this->idplataforma));
				}else{
					$stmt = $this->conexao->prepare("UPDATE plataforma SET ativo = :ativo WHERE idPlataforma = :id");				
					$stmt->execute(array(":ativo" => 0, "id" => $this->idplataforma));
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
				$stmt = $this->conexao->prepare("INSERT INTO plataforma(nome, Grupo_idGrupo) VALUES(:nome,:idgrupo)");
				$stmt->execute(array(':nome' => $this->nome , ':idgrupo' => $this->idgrupo )); 
				$this->conexao = null;
			}
			catch(PDOException $e){
				echo 'Erro: ' . $e->getMessage();
			}
		}
		
		public function reativar()
		{			
			try{
				$stmt = $this->conexao->prepare("UPDATE plataforma SET ativo = :ativo WHERE idPlataforma = :id");				
				$stmt->execute(array(":ativo" => 1, "id" => $this->idplataforma));
				$this->conexao = null;
			  
			}
			catch(PDOException $e){
				echo 'Erro: ' . $e->getMessage();
			}
		}
		
		public function gerarLista()
		{
			try{
				$stmt = $this->conexao->prepare("SELECT idPlataforma , nome FROM plataforma WHERE ativo= :ativo");
				$stmt->execute(array("ativo" => 1));
										
				echo "<option selected disabled hidden value=0></option>";	
				while($linha = $stmt->fetch(PDO::FETCH_OBJ)){				
					echo "<option value=". $linha->idPlataforma .">" . $linha->nome ."</option>" ;
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
				$stmt = $this->conexao->prepare("SELECT p.idPlataforma , p.nome , g.nome AS nomeGrupo , g.idGrupo 
												FROM plataforma AS p INNER JOIN grupo AS g 
												ON p.Grupo_idGrupo = g.idGrupo 
												WHERE p.ativo = :ativo 
												AND (p.nome LIKE :pesquisa OR p.idPlataforma LIKE :pesquisa OR g.nome LIKE :pesquisa)");
				$stmt->execute(array("ativo" => $this->ativo , "pesquisa" => $pesquisa));				
				while($linha = $stmt->fetch(PDO::FETCH_OBJ)){
					echo "<tr>";
					echo "<td>" . $linha->idPlataforma ."</td>" ;
					echo "<td>" . $linha->nome . "</td>" ;
					echo "<td id=".$linha->idGrupo.">" . $linha->nomeGrupo . "</td>" ;
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
				$stmt = $this->conexao->prepare("SELECT nome FROM plataforma WHERE nome = :nome");
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
		public function set_idplataforma($i){
			$this->idplataforma = $i;
		} 
		
		public function set_nome($n){ 
        	$this->nome = $n;  
    	}
		
		public function set_ativo($a){ 
        	$this->ativo = $a;  
    	}
		
		public function set_idgrupo($g){ 
        	$this->idgrupo = $g;  
    	}
		
		//gettes
		public function get_nome(){ 
        	return $this->nome;  
    	}
		
		public function get_idplataforma(){ 
        	return $this->idplataforma;  
    	}
		
		public function get_idgrupo(){ 
        	return $this->idgrupo;  
    	}
		
		public function get_ativo(){ 
        	return $this->ativo;  
    	}	
	}
?>