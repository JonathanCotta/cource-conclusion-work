<?php
header('Content-Type: text/html; charset=utf-8');
	class Chamado{
		
		//Conexão com o banco 
		private $conexao;		
		
		//Propriedades do objeto
		private $idchamado;
		private $idusuario;
		private $departamento;
		private $plataforma;
		private $categoria;
		private $assunto;
		private $descricao;
		private $anexo;
		private $atendente;
		private $grupo;
		private $status;
		private $interacao;
		private $resposta;		
		private $datageracao;
		private $datainiciado;
		private $datafechado;
		private $tempoatendimento;
		private $prioridade;

		public function __construct($db){
			$this->conexao = $db;
		}
		
		public function inserir()
		{
			try{
				$stmt = $this->conexao->prepare("SELECT (u.prioridadeUsuario) + ( c.prioridadeCat * 4) AS prioridade
												FROM usuario u , categoria c
												WHERE u.idUsuario = :idusuario AND c.idCategoria = :categoria");
				$stmt->execute(array("idusuario" => $_SESSION["idUsuario"] ,"categoria" => $this->categoria ));
				$linha = $stmt->fetch(PDO::FETCH_OBJ);
				$this->prioridade = $linha->prioridade;
				if($this->assunto == ""){
					$stmt = $this->conexao->prepare("SELECT CONCAT(p.nome,' ',c.nome) AS assunto
												FROM categoria c , plataforma p 
												WHERE p.idPlataforma = :plataforma AND c.idCategoria = :categoria ");
					$stmt->execute(array("plataforma" => $this->plataforma ,"categoria" => $this->categoria ));
					$linha = $stmt->fetch(PDO::FETCH_OBJ);
					$this->assunto = $linha->assunto;	
				}
				$stmt = $this->conexao->prepare("SELECT d.nome AS departamento , u.ramal 
												FROM usuario u 
												INNER JOIN departamento d ON u.Departamento_idDepartamento = d.idDepartamento 
												WHERE u.idUsuario = :idusuario");
				$stmt->execute(array("idusuario" => $_SESSION["idUsuario"]));
				$linha = $stmt->fetch(PDO::FETCH_OBJ);
				$dep = $linha->departamento;
				$rm = $linha->ramal;
				$txt = "Departamento: ". $dep . " Ramal: ". $rm . "\n" . $this->descricao;
			
				$stmt = $this->conexao->prepare("INSERT INTO chamado(assunto, descricao, anexo, Usuario_idUsuario ,Plataforma_idPlataforma, Categoria_idCategoria, prioridadeChamado, status ) 
												VALUES(:assunto, :descricao, :anexo, :usuario, :idplataforma, :idcategoria, :prioridade , :status)");
				$stmt->execute(array(":assunto" => $this->assunto,
									":descricao" => $txt ,
									":anexo" => $this->anexo ,
									":usuario" => $_SESSION["idUsuario"] ,
									":idplataforma" => $this->plataforma ,
									":idcategoria" => $this->categoria ,
									":prioridade" => $this->prioridade ,
									":status" => "Aberto"));
				return $this->conexao->lastInsertId();	
				
				$this->conexao = null;
			}
			catch(PDOException $e){
					echo 'Erro: ' . $e->getMessage();
			}			
		}
		
		public function repassar()
		{
			try{
				$stmt = $this->conexao->prepare("UPDATE chamado 
												SET atendente = :atendente, status = :status , dataIniciado = :datainiciado
												WHERE idChamado = :idchamado");
				$stmt->execute(array(":atendente" => $this->atendente, 
									":idchamado" => $this->idchamado , 
									":status" => "Iniciado", 
									"datainiciado" => date("Y-m-d G:i:s")));
				$this->conexao = null;
				
				
			}
			catch(PDOException $e){
					echo 'Erro: ' . $e->getMessage();
			}			
		}
		
		public function alterar()
		{
			try{
				if($this->status == "Homologado"){
					// buscando quando foi gerado o chamado
					$stmt = $this->conexao->prepare("SELECT dataGerado FROM chamado WHERE idChamado = :idchamado");
					$stmt->execute(array("idchamado"=>$_SESSION['idChamado']));
					$linha = $stmt->fetch(PDO::FETCH_OBJ);
					// calculo do tempo de chamado
					$dataFechado = date("Y-m-d G:i:s");
					$dataGerado = new DateTime($linha->dataGerado);
					$dataAgora = new DateTime($dataFechado);
					$intervalo =  $dataGerado->diff($dataAgora);
					$tempoAtendimento = $intervalo->format("%a")*24 + $intervalo->format("%H") .":". $intervalo->format("%i") .":". $intervalo->format("%S") ;
					// query de alteração
					$stmt = $this->conexao->prepare("UPDATE chamado
													SET interacao = :interacao, status = :status , dataFechado = :datafechado, tempoAtendimento = :tempoatendimento 
													WHERE idChamado = :idchamado");												
					$stmt->execute(array(":interacao" => $this->interacao,
					":status" => $this->status,
					":idchamado" =>$_SESSION['idChamado'] ,
					":datafechado" => $dataFechado, ":tempoatendimento" => $tempoAtendimento ));	
				}
				else{
					$stmt = $this->conexao->prepare("UPDATE chamado
													SET interacao = :interacao, status =:status  
													WHERE idChamado = :idchamado");												
				$stmt->execute(array(":interacao" => $this->interacao,":status" => $this->status, ":idchamado" =>$_SESSION['idChamado']  ));
				}
				
				$this->conexao = null;
				
			}
			catch(PDOException $e){
					echo 'Erro: ' . $e->getMessage();
			}	
		
		}
		public function selecionarUm(){				
			$_SESSION['idChamado'] = $this->idchamado;
			echo "true";
			
		}
		
		public function exibirUm()
		{
			try{
				
				$stmt = $this->conexao->prepare("SELECT c.idChamado, u.email, c.descricao, c.status, c.interacao , c.assunto, c.anexo  FROM  chamado c
												INNER JOIN usuario u ON  u.idUsuario = c.Usuario_idUsuario 
												WHERE idChamado = :idchamado");
				$stmt->execute(array("idchamado" => $_SESSION['idChamado']));	
				$numlinhas = $stmt->rowCount();				
				if ($numlinhas != 0) {
					$linha = $stmt->fetch(PDO::FETCH_OBJ);
					$chamado = array("email"=> $linha->email , 
								"descricao" => $linha->descricao , 
								"status" => $linha->status ,
								"interacao" => $linha->interacao ,
								"assunto" => $linha->assunto ,
								"anexo" => $linha->anexo ,
								"idChamado" => $linha->idChamado
								);
				}
				else{
					$chamado = array("email"=> null , 
								"descricao" => null , 
								"status" => null ,
								"interacao" => null ,
								"assunto" => null ,
								"anexo" => null ,
								"idChamado" => null
								);
				}			
				
				$this->conexao = null; 
				echo json_encode($chamado);			
			}
			catch(PDOException $e){
					echo 'Erro: ' . $e->getMessage();
			}	
			
		}
		
		public function exibirTodos($statusPesquisa)
		{
			try{				
				switch ($_SESSION['perfil']) {
					case 'Administrador':
						if($statusPesquisa == "ativo"){
							$stmt = $this->conexao->prepare("SELECT c.idChamado, c.assunto , c.status, c.dataGeracao , u.nome AS nomeUsuario , p.Grupo_idGrupo AS grupo
														FROM chamado c
														INNER JOIN usuario u ON c.Usuario_idUsuario = u.idUsuario
														INNER JOIN plataforma p ON Plataforma_idPlataforma = p.idPlataforma
														WHERE p.Grupo_idGrupo IN 
														(SELECT  a.Grupo_idGrupo AS idGrupo FROM atendente a WHERE a.Usuario_idUsuario = :idusuario
														UNION 
														SELECT g.idGrupo FROM grupo g WHERE g.nome = :generico)
														AND c.status != :status
														ORDER BY c.prioridadeChamado ASC , c.dataGeracao DESC");
							$stmt->execute(array(":idusuario" =>$_SESSION['idUsuario'], ":generico"=>"Genérico",":status"=>"Homologado"));
							while($linha = $stmt->fetch(PDO::FETCH_OBJ)){
								echo "<tr>";
								echo "<td>"  . $linha->idChamado ."</td>";
								echo "<td>" . $linha->assunto . "</td>";							
								echo "<td>" . $linha->status . "</td>";																			
								echo "<td>" . $linha->nomeUsuario . "</td>";
								echo "<td>" . $linha->dataGeracao . "</td>";
								echo "<td class=invisivel>" . $linha->grupo . "</td>";										
								echo "</tr>";
							}
						}
						else if($statusPesquisa == "inativo" ){
							$stmt = $this->conexao->prepare("SELECT c.idChamado, c.assunto , c.status, c.dataFechado , u.nome AS nomeUsuario , p.Grupo_idGrupo AS grupo
														FROM chamado c
														INNER JOIN usuario u ON c.Usuario_idUsuario = u.idUsuario
														INNER JOIN plataforma p ON Plataforma_idPlataforma = p.idPlataforma
														WHERE p.Grupo_idGrupo IN 
														(SELECT  a.Grupo_idGrupo AS idGrupo FROM atendente a WHERE a.Usuario_idUsuario = :idusuario
														UNION 
														SELECT g.idGrupo FROM grupo g WHERE g.nome = :generico)
														AND c.status = :status
														ORDER BY c.prioridadeChamado ASC , c.dataGeracao DESC");
							$stmt->execute(array(":idusuario" =>$_SESSION['idUsuario'], ":generico"=>"Genérico",":status"=>"Homologado"));
							while($linha = $stmt->fetch(PDO::FETCH_OBJ)){
								echo "<tr>";
								echo "<td>"  . $linha->idChamado ."</td>";
								echo "<td>" . $linha->assunto . "</td>";							
								echo "<td>" . $linha->status . "</td>";																			
								echo "<td>" . $linha->nomeUsuario . "</td>";
								echo "<td>" . $linha->dataFechado . "</td>";
								echo "<td class=invisivel>" . $linha->grupo . "</td>";										
								echo "</tr>";
							}
						}			
						
						break;
					case 'Atendente':
						if($statusPesquisa == "ativo"){
							$stmt = $this->conexao->prepare("SELECT c.idChamado, c.assunto , c.status,c.dataGeracao , u.nome AS nomeUsuario
														FROM chamado c
														INNER JOIN usuario u ON c.Usuario_idUsuario = u.idUsuario
														WHERE c.atendente IN
														(SELECT u.login FROM usuario u WHERE idUsuario = :idusuario )
														AND (c.status != :status1 AND c.status != :status2)
														ORDER BY c.prioridadeChamado ASC , c.dataGeracao DESC");
							$stmt->execute(array("status1"=>"Homologado","status2"=>"Aberto", "idusuario"=> $_SESSION['idUsuario']));
							while($linha = $stmt->fetch(PDO::FETCH_OBJ)){
								echo "<tr>";
								echo "<td>"  . $linha->idChamado ."</td>";
								echo "<td>" . $linha->assunto . "</td>";							
								echo "<td>" . $linha->status . "</td>";																			
								echo "<td>" . $linha->nomeUsuario . "</td>";
								echo "<td>" . $linha->dataGeracao . "</td>";										
								echo "</tr>";
							}
						}
						else if($statusPesquisa == "inativo"){
							$stmt = $this->conexao->prepare("SELECT c.idChamado, c.assunto , c.status,c.dataFechado , u.nome AS nomeUsuario
														FROM chamado c
														INNER JOIN usuario u ON c.Usuario_idUsuario = u.idUsuario
														WHERE c.atendente IN
														(SELECT u.login FROM usuario u WHERE idUsuario = :idusuario )
														AND c.status = :status1 
														ORDER BY c.prioridadeChamado ASC , c.dataGeracao DESC");
							$stmt->execute(array("status1"=>"Homologado", "idusuario"=> $_SESSION['idUsuario']));
							while($linha = $stmt->fetch(PDO::FETCH_OBJ)){
								echo "<tr>";
								echo "<td>"  . $linha->idChamado ."</td>";
								echo "<td>" . $linha->assunto . "</td>";							
								echo "<td>" . $linha->status . "</td>";																			
								echo "<td>" . $linha->nomeUsuario . "</td>";
								echo "<td>" . $linha->dataFechado . "</td>";										
								echo "</tr>";
							}
						}		
											
						
						break;
					case 'Comum':
						if($statusPesquisa == "ativo"){
							$stmt = $this->conexao->prepare("SELECT c.idChamado, c.assunto , c.status,c.dataGeracao, c.atendente , u.nome AS nomeUsuario
														FROM chamado c
														INNER JOIN usuario u ON c.Usuario_idUsuario = u.idUsuario
														WHERE c.Usuario_idUsuario = :idusuario AND c.status != :status1
														ORDER BY c.prioridadeChamado ASC , c.dataGeracao DESC");
							$stmt->execute(array("status1"=>"Homologado","idusuario"=>$_SESSION['idUsuario']));
							while($linha = $stmt->fetch(PDO::FETCH_OBJ)){
								echo "<tr>";
								echo "<td>"  . $linha->idChamado ."</td>";
								echo "<td>" . $linha->assunto . "</td>";							
								echo "<td>" . $linha->status . "</td>";	
								echo "<td>" . $linha->dataGeracao . "</td>";
								echo "<td>" . $linha->atendente . "</td>";										
								echo "</tr>";
							}
						}
						else if($statusPesquisa == "inativo"){
							$stmt = $this->conexao->prepare("SELECT c.idChamado, c.assunto , c.status,c.dataFechado, c.atendente , u.nome AS nomeUsuario
														FROM chamado c
														INNER JOIN usuario u ON c.Usuario_idUsuario = u.idUsuario
														WHERE c.Usuario_idUsuario = :idusuario AND c.status = :status1
														ORDER BY c.prioridadeChamado ASC , c.dataGeracao DESC");
							$stmt->execute(array("status1"=>"Homologado","idusuario"=>$_SESSION['idUsuario']));
							while($linha = $stmt->fetch(PDO::FETCH_OBJ)){
								echo "<tr>";
								echo "<td>"  . $linha->idChamado ."</td>";
								echo "<td>" . $linha->assunto . "</td>";							
								echo "<td>" . $linha->status . "</td>";	
								echo "<td>" . $linha->dataFechado . "</td>";
								echo "<td>" . $linha->atendente . "</td>";										
								echo "</tr>";
							}
							
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
		
		public function pesquisar($p , $statusPesquisa , $intervalo = ""){
			try{
				$pesquisa = "%".$p."%";				
				switch ($_SESSION['perfil']) {
					case 'Administrador':
						if($statusPesquisa == "ativo"){
							$stmt = $this->conexao->prepare("SELECT c.idChamado, c.assunto , c.status, c.dataGeracao , u.nome AS nomeUsuario , p.Grupo_idGrupo AS grupo
														FROM chamado c
														INNER JOIN usuario u ON c.Usuario_idUsuario = u.idUsuario
														INNER JOIN plataforma p ON Plataforma_idPlataforma = p.idPlataforma
														WHERE p.Grupo_idGrupo IN 
														(SELECT  a.Grupo_idGrupo AS idGrupo FROM atendente a WHERE a.Usuario_idUsuario = :idusuario
														UNION 
														SELECT g.idGrupo FROM grupo g WHERE g.nome = :generico)
														AND c.status != :status
														AND (c.idChamado LIKE :pesquisa OR u.nome LIKE :pesquisa OR u.login LIKE :pesquisa OR c.assunto LIKE :pesquisa)
														ORDER BY c.prioridadeChamado ASC , c.dataGeracao DESC");
							$stmt->execute(array(":idusuario" =>$_SESSION['idUsuario'], 
											":generico"=>"Genérico",
											":status"=>"Homologado", 
											":pesquisa"=> $pesquisa));
							while($linha = $stmt->fetch(PDO::FETCH_OBJ)){
								echo "<tr>";
								echo "<td>"  . $linha->idChamado ."</td>";
								echo "<td>" . $linha->assunto . "</td>";							
								echo "<td>" . $linha->status . "</td>";																			
								echo "<td>" . $linha->nomeUsuario . "</td>";
								echo "<td>" . $linha->dataGerado . "</td>";
								echo "<td class=invisivel>" . $linha->grupo . "</td>";									
								echo "</tr>";
							}
						}
						else if($statusPesquisa == "inativo" ){
							$stmt = $this->conexao->prepare("SELECT c.idChamado, c.assunto , c.status, c.dataFechado , u.nome AS nomeUsuario , p.Grupo_idGrupo AS grupo
														FROM chamado c
														INNER JOIN usuario u ON c.Usuario_idUsuario = u.idUsuario
														INNER JOIN plataforma p ON Plataforma_idPlataforma = p.idPlataforma
														WHERE p.Grupo_idGrupo IN 
														(SELECT  a.Grupo_idGrupo AS idGrupo FROM atendente a WHERE a.Usuario_idUsuario = :idusuario
														UNION 
														SELECT g.idGrupo FROM grupo g WHERE g.nome = :generico)
														AND c.status = :status
														AND (c.idChamado LIKE :pesquisa OR u.nome LIKE :pesquisa OR u.login LIKE :pesquisa OR c.assunto LIKE :pesquisa)
														AND c.dataFechado > DATE_ADD(CURDATE(), INTERVAL - :num day)
														ORDER BY c.prioridadeChamado ASC , c.dataGeracao DESC");
							$stmt->execute(array(":idusuario" =>$_SESSION['idUsuario'], 
											":generico"=>"Genérico",
											":status"=>"Homologado", 
											":pesquisa"=> $pesquisa,
											":num"=>$intervalo));
							while($linha = $stmt->fetch(PDO::FETCH_OBJ)){
								echo "<tr>";
								echo "<td>"  . $linha->idChamado ."</td>";
								echo "<td>" . $linha->assunto . "</td>";							
								echo "<td>" . $linha->status . "</td>";																			
								echo "<td>" . $linha->nomeUsuario . "</td>";
								echo "<td>" . $linha->dataFechado . "</td>";
								echo "<td class=invisivel>" . $linha->grupo . "</td>";									
								echo "</tr>";
							}
						}						
						
				
						break;
					case 'Atendente':
						if($statusPesquisa == "ativo"){
							$stmt = $this->conexao->prepare("SELECT c.idChamado, c.assunto , c.status,c.dataGeracao , u.nome AS nomeUsuario
														FROM chamado c
														INNER JOIN usuario u ON c.Usuario_idUsuario = u.idUsuario
														WHERE c.atendente IN
														(SELECT u.login FROM usuario u WHERE idUsuario = :idusuario )
														AND (c.status != :status1 AND c.status != :status2)
														AND (c.idChamado LIKE :pesquisa OR u.nome LIKE :pesquisa OR u.login LIKE :pesquisa OR c.assunto LIKE :pesquisa)
														ORDER BY c.prioridadeChamado ASC , c.dataGeracao DESC");
							$stmt->execute(array("status1"=>"Homologado",
												"status2"=>"Aberto", 
												"idusuario"=> $_SESSION['idUsuario'],
												":pesquisa"=> $pesquisa));
							while($linha = $stmt->fetch(PDO::FETCH_OBJ)){
								echo "<tr>";
								echo "<td>"  . $linha->idChamado ."</td>";
								echo "<td>" . $linha->assunto . "</td>";							
								echo "<td>" . $linha->status . "</td>";																			
								echo "<td>" . $linha->nomeUsuario . "</td>";
								echo "<td>" . $linha->dataGeracao . "</td>";										
								echo "</tr>";
							}
						}
						else if($statusPesquisa == "inativo"){
							$stmt = $this->conexao->prepare("SELECT c.idChamado, c.assunto , c.status,c.dataFechado , u.nome AS nomeUsuario
														FROM chamado c
														INNER JOIN usuario u ON c.Usuario_idUsuario = u.idUsuario
														WHERE c.atendente IN
														(SELECT u.login FROM usuario u WHERE idUsuario = :idusuario )
														AND c.status = :status1 
														AND (c.idChamado LIKE :pesquisa OR u.nome LIKE :pesquisa OR u.login LIKE :pesquisa OR c.assunto LIKE :pesquisa)
														AND c.dataFechado > DATE_ADD(CURDATE(), INTERVAL - :num day)
														ORDER BY c.prioridadeChamado ASC , c.dataGeracao DESC");
							$stmt->execute(array("status1"=>"Homologado", 
												"idusuario"=> $_SESSION['idUsuario'],
												":pesquisa"=> $pesquisa,
												":num"=>$intervalo));
							while($linha = $stmt->fetch(PDO::FETCH_OBJ)){
								echo "<tr>";
								echo "<td>"  . $linha->idChamado ."</td>";
								echo "<td>" . $linha->assunto . "</td>";							
								echo "<td>" . $linha->status . "</td>";																			
								echo "<td>" . $linha->nomeUsuario . "</td>";
								echo "<td>" . $linha->dataFechado . "</td>";										
								echo "</tr>";
							}
						}		
											
						
						break;
					case 'Comum':
						if($statusPesquisa == "ativo"){
							$stmt = $this->conexao->prepare("SELECT c.idChamado, c.assunto , c.status,c.dataGeracao, c.atendente , u.nome AS nomeUsuario
														FROM chamado c
														INNER JOIN usuario u ON c.Usuario_idUsuario = u.idUsuario
														WHERE c.Usuario_idUsuario = :idusuario AND c.status != :status1
														AND (c.idChamado LIKE :pesquisa OR u.nome LIKE :pesquisa OR u.login LIKE :pesquisa OR c.assunto LIKE :pesquisa)
														ORDER BY c.prioridadeChamado ASC , c.dataGeracao DESC");
							$stmt->execute(array("status1"=>"Homologado",
											"idusuario"=>$_SESSION['idUsuario'] ,
											":pesquisa"=> $pesquisa));
							while($linha = $stmt->fetch(PDO::FETCH_OBJ)){
								echo "<tr>";
								echo "<td>"  . $linha->idChamado ."</td>";
								echo "<td>" . $linha->assunto . "</td>";							
								echo "<td>" . $linha->status . "</td>";	
								echo "<td>" . $linha->dataGeracao . "</td>";
								echo "<td>" . $linha->atendente . "</td>";										
								echo "</tr>";
							}
						}
						else if($statusPesquisa == "inativo"){
							$stmt = $this->conexao->prepare("SELECT c.idChamado, c.assunto , c.status,c.dataFechado, c.atendente , u.nome AS nomeUsuario
														FROM chamado c
														INNER JOIN usuario u ON c.Usuario_idUsuario = u.idUsuario
														WHERE c.Usuario_idUsuario = :idusuario AND c.status = :status1
														AND (c.idChamado LIKE :pesquisa OR u.nome LIKE :pesquisa OR u.login LIKE :pesquisa OR c.assunto LIKE :pesquisa)
														AND c.dataFechado > DATE_ADD(CURDATE(), INTERVAL - :num day)
														ORDER BY c.prioridadeChamado ASC , c.dataGeracao DESC");
							$stmt->execute(array("status1"=>"Homologado",
											"idusuario"=>$_SESSION['idUsuario'] ,
											":pesquisa"=> $pesquisa,
											":num"=>$intervalo));
							while($linha = $stmt->fetch(PDO::FETCH_OBJ)){
								echo "<tr>";
								echo "<td>"  . $linha->idChamado ."</td>";
								echo "<td>" . $linha->assunto . "</td>";							
								echo "<td>" . $linha->status . "</td>";	
								echo "<td>" . $linha->dataFechado . "</td>";
								echo "<td>" . $linha->atendente . "</td>";										
								echo "</tr>";
							}
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
		public function set_idchamado($i){
			$this->idchamado = $i;
		}
		
		public function set_idusuario($u){
			$this->idusuario = $u;
		} 
		
		public function set_departamento($d){
			$this->departamento = $d;
		}
		
		public function set_plataforma($p){
			$this->plataforma = $p;
		} 
		
		public function set_categoria($c){
			$this->categoria = $c;
		}
		
		public function set_assunto($a){
			$this->assunto = $a;
		} 
		
		public function set_descricao($d){
			$this->descricao = $d;
		}
		
		public function set_anexo($a){
			$this->anexo = $a;
		}
		
		public function set_atendente($a){
			$this->atendente = $a;
		}
		
		public function set_grupo($g){
			$this->grupo = $g;
		}
		
		public function set_status($s){
			$this->status = $s;
		}
		
		public function set_tempodevida($t){
			$this->tempodevida = $t;
		}
		
		public function set_datageracao($d){
			$this->datageracao = $d;
		}
		
		public function set_datainiciado($d){
			$this->datainiciado = $d;
		}
		
		public function set_datafechado($d){
			$this->datafechado = $d;
		}
		
		public function set_tempoatendimento($t){
			$this->tempoatendimento = $t;
		}  
		
		public function set_prioridade($p){
			$this->prioridade = $p;
		}
		
		public function set_interacao($i){
			$this->interacao = $i;
		} 
		
		public function set_resposta($r){
			$this->resposta = $r;
		} 
		 
		//getters
		public function get_idchamado(){
			return $this->idchamado;
		}
		
		public function get_idusuario(){
			return $this->idusuario;
		} 
		
		public function get_departamento(){
			return $this->departamento;
		}
		
		public function get_plataforma(){
			return $this->plataforma;
		} 
		
		public function get_categoria(){
			return $this->categoria;
		}
		
		public function get_assunto(){
			return $this->assunto ;
		} 
		
		public function get_descricao(){
			return $this->descricao;
		}
		
		public function get_anexo(){
			return $this->anexo;
		}
		
		public function get_atendente(){
			return $this->atendente;
		}
		
		public function get_grupo(){
			return $this->grupo;
		}
		
		public function get_status(){
			return $this->status;
		}
		
		public function get_tempodevida(){
			return $this->tempodevida;
		}
		
		public function get_datageracao(){
			return $this->datageracao;
		}
		
		public function get_datainiciado(){
			return $this->datainiciado;
		}
		
		public function get_datafechado(){
			return $this->datafechado;
		}
		
		public function get_tempoatendimento(){
			return $this->tempoatendimento;
		} 
		 
		public function get_prioridade(){
			return $this->prioridade;
		}
		
		public function get_interacao(){
			return $this->interacao;
		}
		
		public function get_resposta(){
			return $this->resposta;
		}                 
	}
?>