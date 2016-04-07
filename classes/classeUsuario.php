<?php
header('Content-Type: text/html; charset=utf-8');
	class Usuario{
		
		//Conexão com o banco 
		protected $conexao;		
		
		//Propriedades do objeto
		protected $idusuario;
		protected $cpf;
		protected $nome;
		protected $login;
		protected $senha;
		protected $email;
		protected $ramal;
		protected $celular;
		protected $departamentoid;
		protected $cargoid;
		protected $nivelacesso;
		protected $ativo;
		protected $prioridadeUsuario;

		public function __construct($db){
			$this->conexao = $db;
		}
				
		public function selecionarTodos()
		{
			try{
				$stmt = $this->conexao->prepare("SELECT u.idUsuario, u.cpf , u.nome, u.email, u.ramal, u.celular, u.perfil,u.login ,u.Departamento_idDepartamento idDep, d.nome  nomeDep, u.Cargo_idCargo idCargo, c.nome  nomeCargo 
												FROM usuario  u 
												INNER JOIN cargo  c ON u.Cargo_idCargo = c.idCargo 
												INNER JOIN departamento  d ON u.Departamento_idDepartamento = d.idDepartamento 
												WHERE u.ativo = :ativo");
				$stmt->execute(array("ativo" => $this->ativo));							
				while($linha = $stmt->fetch(PDO::FETCH_OBJ)){
					echo "<tr>";
					echo "<td id=". $linha->idUsuario .">"  . $linha->cpf ."</td>";
					echo "<td>" . $linha->nome . "</td>";
					echo "<td class=invisivel>" . $linha->login . "</td>";
					echo "<td>" . $linha->email . "</td>";
					echo "<td class=invisivel>" . $linha->ramal . "</td>";
					echo "<td class=invisivel>" . $linha->celular . "</td>";					
					echo "<td id=". $linha->idDep .">" . $linha->nomeDep . "</td>";
					echo "<td id=". $linha->idCargo .">". $linha->nomeCargo . "</td>";
					echo "<td class=invisivel>" . $linha->perfil . "</td>";										
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
				$stmt = $this->conexao->prepare("SELECT (c.prioridadeCargo * 12) + (d.prioridadeDep * 8) AS prioridade 
												FROM cargo c , departamento d 
												WHERE c.idCargo = :cargo AND d.idDepartamento = :departamento");
				$stmt->execute(array( ':cargo' => $this->cargoid , ':departamento' => $this->departamentoid  ));				
				$linha = $stmt->fetch(PDO::FETCH_OBJ);
				$this->prioridadeUsuario = $linha->prioridade;
				$stmt = $this->conexao->prepare("UPDATE usuario SET cpf = :cpf, nome = :nome, email = :email, ramal = :ramal, 
												celular = :celular , login = :login, senha = MD5(:senha) , perfil = :perfil, 
												Departamento_idDepartamento = :departamento, Cargo_idCargo = :cargo ,
												prioridadeUsuario = :prioridadeusuario
												WHERE cpf = :cpf");		
				$stmt->execute(array(':cpf' => $this->cpf , 
									 ':nome' => $this->nome , 
									 ':email' => $this->email ,
									 ':ramal' => $this->ramal ,
									 ':celular' => $this->celular ,
									 ':login' => $this->login ,
									 ':senha' => $this->senha ,
									 ':perfil' => $this->nivelacesso ,
									 ':departamento' => $this->departamentoid ,
									 ':cargo' => $this->cargoid,
									 ':prioridadeusuario' => $this->prioridadeUsuario
									 )); 
				$this->conexao = null; 
			}
			catch(PDOException $e){
				echo 'Erro: ' . $e->getMessage();
			}
		}
		
		public function alterarSenha($op)
		{
			switch ($op) {
				case 'reset':
					$stmt = $this->conexao->prepare("UPDATE usuario SET senha = MD5(:senha ) WHERE idUsuario = :id");				
					$stmt->execute(array(":senha" => 1234, "id" => $this->idusuario));
					$this->conexao = null;
					break;
				case 'alter':
					session_start();
					$stmt = $this->conexao->prepare("UPDATE usuario SET senha = MD5(:senha ) WHERE idUsuario = :id");				
					$stmt->execute(array(":senha" => $this->senha, "id" =>$this->idusuario));
					$this->conexao = null;					
					break;
				default:					
					break;
			}			
		}
		
		public function excluir()
		{
			try{
				$stmt = $this->conexao->prepare("SELECT Usuario_idUsuario FROM  chamado WHERE Usuario_idUsuario = :id");
				$stmt->execute(array("id" => $this->idusuario));				
				$numlinhas = $stmt->rowCount();				
				$stmt2 = $this->conexao->prepare("SELECT Usuario_idUsuario FROM  atendente WHERE Usuario_idUsuario = :id");
				$stmt2->execute(array("id" => $this->idusuario));
				$numlinhas2 = $stmt2->rowCount();
									
				if ($numlinhas == 0 && $numlinhas2 == 0){
					$stmt = $this->conexao->prepare("DELETE FROM usuario  WHERE idUsuario = :id");				
					$stmt->execute(array("id" => $this->idusuario));
				}else{
					$stmt = $this->conexao->prepare("UPDATE usuario SET ativo = :ativo WHERE idUsuario = :id");				
					$stmt->execute(array(":ativo" => 0, "id" => $this->idusuario));
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
				$stmt = $this->conexao->prepare("SELECT (c.prioridadeCargo * 12) + (d.prioridadeDep * 8) AS prioridade 
												FROM cargo c , departamento d 
												WHERE c.idCargo = :cargo AND d.idDepartamento = :departamento");
				$stmt->execute(array( ':cargo' => $this->cargoid , ':departamento' => $this->departamentoid  ));				
				$linha = $stmt->fetch(PDO::FETCH_OBJ);
				$this->prioridadeUsuario = $linha->prioridade;				
				$stmt = $this->conexao->prepare("INSERT INTO usuario(cpf , nome, email, ramal, celular , login, senha, perfil , Departamento_idDepartamento, Cargo_idCargo, prioridadeUsuario) 
												 VALUES(:cpf,:nome,:email,:ramal,:celular,:login,MD5(:senha),:perfil,:departamento,:cargo, :prioridadeusuario)");		
				$stmt->execute(array(':cpf' => $this->cpf , 
									 ':nome' => $this->nome , 
									 ':email' => $this->email ,
									 ':ramal' => $this->ramal ,
									 ':celular' => $this->celular ,
									 ':login' => $this->login ,
									 ':senha' => $this->senha ,
									 ':perfil' => $this->nivelacesso ,
									 ':departamento' => $this->departamentoid ,
									 ':cargo' => $this->cargoid,
									 ':prioridadeusuario' => $this->prioridadeUsuario
									 ));				
				$this->conexao = null; 
			}
			catch(PDOException $e){
				echo 'Erro: ' . $e->getMessage();
			}
		}
		
		public function logar()
		{
			try{
				$stmt = $this->conexao->prepare("SELECT idUsuario, login , senha, perfil, nome FROM  usuario WHERE login = :login AND senha = MD5(:senha)");
				$stmt->execute(array("login" => $this->login , "senha" => $this->senha));
				$numlinhas = $stmt->rowCount();				
				if ($numlinhas != 0) {
					$linha = $stmt->fetch(PDO::FETCH_OBJ);
					session_start();
					$_SESSION["idUsuario"] = $linha->idUsuario;
					$_SESSION["login"] = $linha->login;
					$_SESSION["senha"] = $linha->senha;
					$_SESSION["perfil"] = $linha->perfil;
					$_SESSION["nome"] = $linha->nome;
					if( $this->senha == 1234){
						$dados = array("pass" => true , "pwdreset" => true );
					}
					else{
						$dados = array("pass" => true , "pwdreset" => false );
					}									   		
				}
				else{
					$dados = array("pass" => false , "pwdreset" => false );
				}
				$this->conexao = null; 
				echo json_encode($dados);				
			}
			catch(PDOException $e){
				echo 'Erro: ' . $e->getMessage();
			}
		}
			
		public function deslogar()
		{
			session_start();
			session_unset();
			session_destroy();	
		}
		
		public function verificarSessao(){
			session_start();
			try{
				$stmt = $this->conexao->prepare("SELECT a.idAvaliacao, c.idChamado FROM avaliacao a 
												INNER JOIN chamado c ON a.Chamado_idChamado = c.idChamado
												WHERE c.Usuario_idUsuario = :idUsuario AND a.pendente = :pendente");
				$stmt->execute(array("idUsuario" => $_SESSION["idUsuario"], "pendente" => 1));
				$numlinhas = $stmt->rowCount();	
				if($_SESSION["senha"] != 1234){
					$pwdpass = true;
				}
				else{
					$pwdpass = false;
				}				
				if($numlinhas != 0 ){
					$linha = $stmt->fetch(PDO::FETCH_OBJ);
					$_SESSION["idChamado"] = $linha->idChamado;
														
					$usuario = array("pwdpass"=> $pwdpass,"perfil" => $_SESSION["perfil"] , "id" => $_SESSION["idUsuario"], "login" => $_SESSION["login"],"avalPendente" => true , "idChamado" => $_SESSION["idChamado"] );
				}
				else{
					$usuario = array("pwdpass"=> $pwdpass,"perfil" => $_SESSION["perfil"] , "id" => $_SESSION["idUsuario"],"login" => $_SESSION["login"], "avalPendente" => false);
				}
				$this->conexao = null; 
				echo json_encode($usuario) ;	
			}
			catch(PDOException $e){
				echo 'Erro: ' . $e->getMessage();
			}			
		}
		
		public function reativar()
		{			
			try{
				$stmt = $this->conexao->prepare("UPDATE usuario SET ativo = :ativo WHERE idUsuario = :id");				
				$stmt->execute(array(":ativo" => 1 , "id" => $this->idusuario));
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
				$stmt = $this->conexao->prepare("SELECT u.idUsuario, u.cpf , u.nome, u.email, u.ramal, u.celular, u.perfil,u.login ,u.Departamento_idDepartamento idDep, d.nome  nomeDep, u.Cargo_idCargo idCargo, c.nome  nomeCargo 
												FROM usuario  u 
												INNER JOIN cargo  c ON u.Cargo_idCargo = c.idCargo 
												INNER JOIN departamento  d ON u.Departamento_idDepartamento = d.idDepartamento 
												WHERE u.ativo = :ativo
												AND (u.nome LIKE :pesquisa OR u.cpf LIKE :pesquisa OR d.nome LIKE :pesquisa)");
				$stmt->execute(array("ativo" => $this->ativo , "pesquisa" => $pesquisa));				
				while($linha = $stmt->fetch(PDO::FETCH_OBJ)){
					echo "<tr>";
					echo "<td id=". $linha->idUsuario .">"  . $linha->cpf ."</td>";
					echo "<td>" . $linha->nome . "</td>";
					echo "<td class=invisivel>" . $linha->login . "</td>";
					echo "<td>" . $linha->email . "</td>";
					echo "<td class=invisivel>" . $linha->ramal . "</td>";
					echo "<td class=invisivel>" . $linha->celular . "</td>";					
					echo "<td id=". $linha->idDep .">" . $linha->nomeDep . "</td>";
					echo "<td id=". $linha->idCargo .">". $linha->nomeCargo . "</td>";
					echo "<td class=invisivel>" . $linha->perfil . "</td>";										
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
		
		// validações
		public function validaCPF(){
			try{
				$stmt = $this->conexao->prepare("SELECT cpf FROM usuarios WHERE cpf = :cpf");
				$stmt->execute(array("cpf"=> $this->cpf));
				if($stmt->rowCount() != 0){
					$valCpf = array("valido" => false);
				}
				else{
					$valCpf = array("valido" => true);
				}
				echo json_encode($valLogin);
			}
			catch(PDOException $e){
				echo 'Erro: ' . $e->getMessage();
			}	
		}
		
		public function validaLogin(){
			try{
				$stmt = $this->conexao->prepare("SELECT login FROM usuarios WHERE login = :login");
				$stmt->execute(array("login"=> $this->login));
				if($stmt->rowCount() != 0){
					$valLogin = array("valido" => false);
				}
				else{
					$valLogin = array("valido" => true);
				}
				echo json_encode($valLogin);
			}
			catch(PDOException $e){
				echo 'Erro: ' . $e->getMessage();
			}	
		}	
				
		//setters
		public function set_idusuario($i){
			$this->idusuario = $i;
		}
		
		public function set_cpf($c){
			$this->cpf = $c;
		}
		
		public function set_nome($n){
			$this->nome = $n;
		}	
		
		public function set_login($l){
			$this->login = $l;
		}
		
		public function set_senha($s){
			$this->senha = $s;
		}
		
		public function set_email($e){
			$this->email = $e;
		}
		
		public function set_ramal($r){
			$this->ramal = $r;
		}
		
		public function set_celular($c){
			$this->celular = $c;
		}
		
		public function set_departamentoid($d){
			$this->departamentoid = $d;
		}
		
		public function set_cargoid($c){
			$this->cargoid = $c;
		}
		
		public function set_nivelacesso($n){ 
        	$this->nivelacesso = $n;  
    	}
		
		public function set_ativo($a){ 
        	$this->ativo = $a;  
    	}
		
		//getters
		public function get_idusuario(){
			return $this->idusuario;
		}
		
		public function get_cpf(){
			return $this->cpf;
		}
		
		public function get_nome(){
			return $this->nome;
		}	
		
		public function get_login(){
			return $this->login;
		}
		
		public function get_senha(){
			return $this->senha;
		}
		
		public function get_email(){
			return $this->email;
		}
		
		public function get_ramal(){
			return $this->ramal;
		}
		
		public function get_celular(){
			return $this->celular;
		}
		
		public function get_departamentoid(){
			return $this->departamentoid;
		}
		
		public function get_cargoid(){
			return $this->cargoid;
		}
		
		public function get_nivelacesso(){ 
        	return $this->nivelacesso;  
    	}
		
		public function get_ativo(){ 
        	return $this->ativo;  
    	}					
	}
?>