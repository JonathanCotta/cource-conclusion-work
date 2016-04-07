<?php
header('Content-Type: text/html; charset=utf-8');
require '../classes/classeUsuario.php';
	class Atendente extends Usuario{
						
		//Propriedades do objeto			
		private $idgrupo; // id do grupo

		public function __construct($db){
			$this->conexao = $db;
		}
							
		public function inserirNoGrupo(){
			try{				
				$stmt = $this->conexao->prepare("INSERT INTO atendente(Usuario_idUsuario , Grupo_idGrupo, ativo) VALUES(:idusuario , :idgrupo, :ativo)");				
				$stmt->execute(array("idusuario" => $this->idusuario , ":ativo" => 1, ":idgrupo" => $this->idgrupo ));
				$this->conexao = null;
			}
			catch(PDOException $e){
				echo 'Erro: ' . $e->getMessage();
			}
		}
		
		public function removerDoGrupo(){
			try{
				$stmt = $this->conexao->prepare("UPDATE atendente SET ativo = :ativo  WHERE Usuario_idUsuario = :id AND Grupo_idGrupo = :idgrupo");				
				$stmt->execute(array(":ativo" => 0,":id" => $this->idusuario,":idgrupo" => $this->idgrupo));
				$this->conexao = null;
			}
			catch(PDOException $e){
				echo 'Erro: ' . $e->getMessage();
			}
		}
		
		public function reativarAtendente(){
			try{
				$stmt = $this->conexao->prepare("UPDATE atendente SET ativo = :ativo  WHERE Usuario_idUsuario = :id AND Grupo_idGrupo = :idgrupo");				
				$stmt->execute(array(":ativo" => 1,":id" => $this->idusuario,":idgrupo" => $this->idgrupo));
				$this->conexao = null;
			}
			catch(PDOException $e){
				echo 'Erro: ' . $e->getMessage();
			}
		}
		
		public function selecionarTodos(){
			try{
				$stmt = $this->conexao->prepare("SELECT  u.nome, u.ramal, c.nome nomeCargo , u.idUsuario 
												FROM usuario  u 												
												INNER JOIN atendente a ON u.idUsuario = a.Usuario_idUsuario
												INNER JOIN cargo c ON  c.idCargo = u.Cargo_idCargo
												WHERE u.ativo = :ativo AND Grupo_idGrupo = :idgrupo AND a.ativo = :ativat");
				$stmt->execute(array(":ativo" => 1 , ":idgrupo" => $this->idgrupo, "ativat"=> 1));
				while($linha = $stmt->fetch(PDO::FETCH_OBJ)){				
					echo "<tr>";
					echo "<td>" . $linha->idUsuario . "</td>";
					echo "<td>" . $linha->nome . "</td>";					
					echo "<td>" . $linha->ramal . "</td>";
					echo "<td>" . $linha->nomeCargo . "</td>";													
					echo "</tr>";
				}
				$this->conexao = null;
			}
			catch(PDOException $e){
				echo 'Erro: ' . $e->getMessage();
			}
		}
		
		public function atenderChamado(){
			try{
				
			}
			catch(PDOException $e){
				echo 'Erro: ' . $e->getMessage();
			}
		}
		
		public function pesquisar($p){
			try{
				$pesquisa = "%".$p."%";
				$stmt = $this->conexao->prepare("SELECT  u.nome, u.ramal, c.nome nomeCargo,  u.idUsuario 
												FROM usuario  u 												
												INNER JOIN atendente a ON u.idUsuario = a.Usuario_idUsuario
												INNER JOIN cargo c ON  c.idCargo = u.Cargo_idCargo
												WHERE u.ativo = :ativo AND Grupo_idGrupo = :idgrupo AND (u.nome LIKE :pesquisa OR u.ramal LIKE :pesquisa)");
				$stmt->execute(array(":ativo" => 1 , ":idgrupo" => $this->idgrupo ,":pesquisa" => $pesquisa ));
				while($linha = $stmt->fetch(PDO::FETCH_OBJ)){				
					echo "<tr>";
					echo "<td>" . $linha->idUsuario . "</td>";
					echo "<td>" . $linha->nome . "</td>";					
					echo "<td>" . $linha->ramal . "</td>";
					echo "<td>" . $linha->nomeCargo . "</td>";												
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
				
		public function gerarLista($a)
		{
			try{	
				switch ($a) {
					case 'adicionar':
						$stmt = $this->conexao->prepare("SELECT u.idUsuario, u.nome
														FROM usuario u 
														WHERE (u.perfil = :perfil OR u.perfil = :perfil2) AND u.ativo = :ativo AND u.idUsuario NOT IN
														(SELECT a.Usuario_idUsuario FROM atendente a WHERE a.Grupo_idGrupo  = :idgrupo)");
						$stmt->execute(array("ativo" => 1 , "perfil" => "Atendente", "perfil2" => "Administrador","idgrupo" => $this->idgrupo));
						echo "<option selected disabled hidden value=></option>";			
						while($linha = $stmt->fetch(PDO::FETCH_OBJ)){					
									echo "<option value=". $linha->idUsuario .">" . $linha->nome ."</option>" ;	
						}
						break;
						
					case 'inativos':
						$stmt = $this->conexao->prepare("SELECT u.idUsuario, u.nome
														FROM usuario u 
														WHERE (u.perfil = :perfil OR u.perfil = :perfil2) AND u.ativo = :ativo AND u.idUsuario  IN
														(SELECT a.Usuario_idUsuario FROM atendente a WHERE a.Grupo_idGrupo  = :idgrupo AND a.ativo = :ativoat)");
						$stmt->execute(array("ativo" => 1, "ativoat" => 0 , "perfil" => "Atendente", "perfil2" => "Administrador", "idgrupo" => $this->idgrupo));
						echo "<option selected disabled hidden value=></option>";			
						while($linha = $stmt->fetch(PDO::FETCH_OBJ)){					
									echo "<option value=". $linha->idUsuario .">" . $linha->nome ."</option>" ;	
						}
						break;
					case 'repasse':
						$stmt = $this->conexao->prepare("SELECT u.login, u.nome
														FROM usuario u 
														WHERE (u.perfil = :perfil OR u.perfil = :perfil2) AND u.ativo = :ativo AND u.idUsuario  IN
														(SELECT a.Usuario_idUsuario FROM atendente a WHERE a.Grupo_idGrupo  = :idgrupo AND a.ativo = :ativoat)");
						$stmt->execute(array("ativo" => 1, "ativoat" => 1 , "perfil" => "Atendente", "perfil2" => "Administrador","idgrupo" => $this->idgrupo));
						echo "<option selected disabled hidden value=></option>";			
						while($linha = $stmt->fetch(PDO::FETCH_OBJ)){					
									echo "<option value=". $linha->login .">" . $linha->nome ."</option>" ;	
						}
						break;
						
					default:
						
						break;
				}					
				
				$this->conexao = null;
				}
				catch(PDOException $e){
					echo 'Erro: ' . $e->getMessage();
				}
		}
		
		// setters				
		public function set_idgrupo($g){ 
        	$this->idgrupo = $g;  
    	}
		
		//gettes
		
		public function get_idgrupo(){ 
        	return $this->idgrupo;  
    	}		
	}
?>