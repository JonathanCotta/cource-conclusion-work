<?php
header('Content-Type: text/html; charset=utf-8');
	class LogChamado{
		
		//Conexão com o banco 
		private $conexao;
				
		//Propriedades do objeto
		private $idlog;		
		private $idchamado;
		private $agente;
		private $descricao;
		private $datalog;

		public function __construct($db){
			$this->conexao = $db;
		}
		
		public function visualizarLog()
		{
			try{
			
				$stmt = $this->conexao->prepare("SELECT agenteAlteracao, descricao, dataLog FROM logchamado WHERE Chamado_idChamado = :idchamado");			
				$stmt->execute(array("idchamado" => $_SESSION['idChamado']));
				while($linha = $stmt->fetch(PDO::FETCH_OBJ)){
					echo "<tr>";					
					echo "<td>" . $linha->agenteAlteracao . "</td>" ;
					echo "<td>" . $linha->descricao."</td>" ;
					echo "<td>" . $linha->dataLog ."</td>" ;				
					echo "</tr>";
				}
				$this->conexao = null;
			}
			catch(PDOException $e){
				echo 'Erro: ' . $e->getMessage();
			}
		}		
	
		public function iniciarLog($op,$id,$at)
		{
			try{
				$stmt2 = $this->conexao->prepare("SELECT u.nome FROM usuario u WHERE u.idUsuario = :idusuario");
				$stmt2->execute(array('idusuario'=>$_SESSION["idUsuario"]));
				$linha = $stmt2->fetch(PDO::FETCH_OBJ);
				$this->agente = $linha->nome;	
							
				switch ($op) {
					case 'inserir':
						$this->descricao =  "chamado aberto";
						break;
					case 'repassar':
						$this->descricao = "chamado repassado para ".$at;					
						$stmt = $this->conexao->prepare("INSERT INTO logchamado(agenteAlteracao, descricao, Chamado_idChamado) VALUES(:agente,:descricao,:idchamado)");
						$stmt->execute(array("agente" => $this->agente,
									"descricao" => $this->descricao,									
									"idchamado" => $id));
						$this->descricao = "Status alterado: Iniciado";
						break;
					default:						
						break;
				}
								
				$stmt = $this->conexao->prepare("INSERT INTO logchamado(agenteAlteracao, descricao, Chamado_idChamado) VALUES(:agente,:descricao,:idchamado)");
				$stmt->execute(array("agente" => $this->agente,
									"descricao" => $this->descricao,									
									"idchamado" => $id));
				$this->conexao = null;				 
			}
			catch(PDOException $e){
				echo 'Erro: ' . $e->getMessage();
			}
		}
		
		public function gerarLog($status , $interacao , $resposta)
		{
			try{
				$stmt = $this->conexao->prepare("SELECT c.status , c.interacao 
												FROM chamado c 
												WHERE idChamado = :idchamado ");
				$stmt->execute(array('idchamado'=>$_SESSION["idChamado"]));
				$linha = $stmt->fetch(PDO::FETCH_OBJ);
								
				$stmt2 = $this->conexao->prepare("SELECT u.nome FROM usuario u WHERE u.idUsuario = :idusuario");
				$stmt2->execute(array('idusuario'=>$_SESSION["idUsuario"]));
				$linha2 = $stmt2->fetch(PDO::FETCH_OBJ);
				$this->agente = $linha2->nome;
				
				if($status != $linha->status){				
				$descricao = "alterou o status para: ".$status;
				$stmt2 = $this->conexao->prepare("INSERT INTO logchamado(agenteAlteracao, descricao, Chamado_idChamado) VALUES(:agente,:descricao,:idchamado)");
				$stmt2->execute(array("agente" => $this->agente,
									"descricao" => $descricao,									
									"idchamado" =>$_SESSION["idChamado"]));
				}
				if($interacao != $linha->interacao){
				$descricao =  "disse: ".$resposta;
				$stmt3 = $this->conexao->prepare("INSERT INTO logchamado(agenteAlteracao, descricao, Chamado_idChamado) VALUES(:agente,:descricao,:idchamado)");
				$stmt3->execute(array("agente" => $this->agente,
									"descricao" => $descricao,									
									"idchamado" => $_SESSION["idChamado"]));
				}				
				$this->conexao = null;				 
			}
			catch(PDOException $e){
				echo 'Erro: ' . $e->getMessage();
			}
		}
		//setters
		public function set_idlog($i){ 
        	$this->id = $i;  
    	}
		
		public function set_idchamado($ci){ 
        	$this->idchamado = $ci;  
    	}
		
		public function set_agente($ai){ 
        	$this->agentealteracaoid = $ai;  
    	}
		
		public function set_descricao($d){ 
        	$this->descricao = $d;  
    	}
		
		//getters
		public function get_idlog(){ 
        	return $this->id;  
    	}	
		
		public function get_idchamado(){ 
        	return $this->chamadoid;  
    	}
		
		public function get_agente(){ 
        	return $this->agentealteracaoid;  
    	}
		
		public function get_descricao(){ 
        	return $this->descricao;  
    	}
		
		public function get_datahora(){ 
        	return $this->datahora;  
    	}
	}
?>