<?php
header('Content-Type: text/html; charset=utf-8');
	class Avaliacao{
		
		//Conexão com o banco 
		private $conexao;
				
		//Propriedades do objeto
		private $idavaliacao;
		private $idchamado;
		private $observacao;
		private $temporesolucao;		
		private $solucao;
		private $feedback;
		
		public function __construct($db){
			$this->conexao = $db;
		}
		
		public function gerar()
		{			
			try{
				$stmt = $this->conexao->prepare("INSERT INTO avaliacao(Chamado_idChamado) VALUES(:idchamado)");
				$stmt->execute(array('idchamado'=>$_SESSION['idChamado']));
				$this->conexao = null;  
			}
			catch(PDOException $e){
				echo 'Erro: ' . $e->getMessage();
			}			
		}
		
		public function avaliar()
		{
			try{
				
				$stmt = $this->conexao->prepare("UPDATE avaliacao 
												SET Observacao = :observacao, avTempo = :tempo ,avSolucao = :solucao, 
												avFeedback = :feedback, pendente = :pendente 
												WHERE Chamado_idChamado = :idchamado");
				$stmt->execute(array('observacao' => $this->observacao, 
									'tempo' => $this->temporesolucao , 
									'solucao' => $this->solucao , 
									'feedback' => $this->feedback , 
									'idchamado'=>$_SESSION['idChamado'],
									'pendente'=> 0 
									));
				$this->conexao = null;  
			}
			catch(PDOException $e){
				echo 'Erro: ' . $e->getMessage();
			}	
		}
		
		public function selecionarTodos()
		{
			try{
				$stmt = $this->conexao->prepare("SELECT Observacao,avTempo,avSolucao,avFeedback,Chamado_idChamado  
												FROM  avaliacao 
												WHERE pendente = :pendente ");
				$stmt->execute(array('pendente'=> 0 ));
				while($linha = $stmt->fetch(PDO::FETCH_OBJ)){
					echo "<tr>";
					echo "<td>" . $linha->Chamado_idChamado . "</td>" ;					
					echo "<td>" . $linha->Observacao . "</td>" ;
					echo "<td>" . $linha->avTempo ."</td>" ;
					echo "<td>" . $linha->avSolucao ."</td>" ;
					echo "<td>" . $linha->avFeedback ."</td>" ;					
					echo "</tr>";
				}
				$this->conexao = null; 					
			}
			catch(PDOException $e){
				echo 'Erro: ' . $e->getMessage();
			}	
		}
		public function exibirUm(){
			try{
				$stmt = $this->conexao->prepare("SELECT Observacao,avTempo,avSolucao,avFeedback 
												FROM  avaliacao 
												WHERE pendente = :pendente AND Chamado_idChamado = :idchamado");
				$stmt->execute(array('pendente'=> 0 , 'idchamado'=> $_SESSION['idChamado'] ));
				$linha = $stmt->fetch(PDO::FETCH_OBJ);
				$avaliacao = array("Observacao" => $linha->Observacao,
								   "avTempo" => $linha->avTempo,
								   "avSolucao" => $linha->avSolucao ,
								   "avFeedback" => $linha->avFeedback );
				echo json_encode($avaliacao);
			}
			catch(PDOException $e){
				echo 'Erro: ' . $e->getMessage();
			}
		}
		
		public function exibirAvaliacaoGrupo(){
			try{
				$stmt = $this->conexao->prepare("SELECT gr.nome, AVG(av.avtempo) AS tempo, 
												AVG(av.avfeedback) AS feedback, 
												AVG(av.avsolucao) AS solucao,
												AVG(ch.tempoAtendimento) AS atendimento,  
												AVG(((av.avtempo)+(av.avfeedback)+(av.avsolucao))/3) AS media
												FROM chamado ch 
												INNER JOIN categoria ca ON ch.categoria_idcategoria=ca.idcategoria
												INNER JOIN plataforma pl ON ca.plataforma_idplataforma=pl.idplataforma
												INNER JOIN grupo gr ON pl.grupo_idgrupo=gr.idgrupo
												INNER JOIN avaliacao av ON ch.idchamado=av.chamado_idchamado
												WHERE gr.idgrupo IN (select idgrupo from grupo) 
												ORDER BY media DESC");
				$stmt->execute();
				while($linha = $stmt->fetch(PDO::FETCH_OBJ)){
					echo "<tr>";
					echo "<td>" . $linha->nome . "</td>" ;					
					echo "<td>" . number_format($linha->tempo , 2). "</td>" ;
					echo "<td>" . number_format( $linha->feedback, 2) ."</td>" ;
					echo "<td>" . number_format($linha->solucao, 2) ."</td>" ;
					echo "<td>" . number_format($linha->media, 2) ."</td>" ;
					echo "<td>" . number_format($linha->atendimento, 2) . " horas" ."</td>" ;					
					echo "</tr>";
				}
			}
			catch(PDOException $e){
				echo 'Erro: ' . $e->getMessage();
			}
		}
		
		public function exibirAvaliacaoAtendente($idgrupo){
			try{
				if($idgrupo == 0){
					$stmt = $this->conexao->prepare("SELECT 
														us.nome, 
														AVG(av.avtempo) AS tempo, 
														AVG(av.avfeedback) AS feedback, 
														AVG(av.avsolucao) AS solucao, 
														AVG(((av.avtempo)+(av.avfeedback)+(av.avsolucao))/3) AS media
														FROM chamado ch
														INNER JOIN avaliacao av ON ch.idChamado=av.Chamado_idChamado
														INNER JOIN usuario us ON ch.atendente=us.login
														WHERE us.perfil IN (:perfil1,:perfil2)
														ORDER BY media DESC");
					$stmt->execute(array("perfil1"=>"Atendente" , "perfil2"=>"Administrador"));
				}
				else{
					$stmt = $this->conexao->prepare("SELECT 
												us.nome, 
												AVG(av.avtempo) AS tempo, 
												AVG(av.avfeedback) AS feedback, 
												AVG(av.avsolucao) AS solucao, 
												AVG(((av.avtempo)+(av.avfeedback)+(av.avsolucao))/3) AS media
												FROM chamado ch
												INNER JOIN avaliacao av ON ch.idChamado=av.Chamado_idChamado
												INNER JOIN usuario us ON ch.atendente=us.login
												WHERE us.idUsuario IN 
												(SELECT Usuario_idUsuario FROM atendente WHERE Grupo_idGrupo = :idgrupo )
												ORDER BY media DESC");
					$stmt->execute(array("idgrupo"=>$idgrupo));
				}
				
				while($linha = $stmt->fetch(PDO::FETCH_OBJ)){
					if($linha->nome != null){
						echo "<tr>";
						echo "<td>" . $linha->nome . "</td>" ;					
						echo "<td>" . number_format($linha->tempo , 2). "</td>" ;
						echo "<td>" . number_format( $linha->feedback, 2) ."</td>" ;
						echo "<td>" . number_format($linha->solucao, 2) ."</td>" ;
						echo "<td>" . number_format($linha->media, 2) ."</td>" ;					
						echo "</tr>";
					}
					else{
						echo "Nenhum atendente deste grupo foi avaliado.";
					}
					
				}
			}
			catch(PDOException $e){
				echo 'Erro: ' . $e->getMessage();
			}
		}
		
		public function pesquisar($op, $p , $idgrupo = 0){
			try{
				$pesquisa = "%".$p."%";				
				switch ($op) {
					case 'gp':
							$stmt = $this->conexao->prepare("SELECT gr.nome, AVG(av.avtempo) AS tempo, 
															AVG(av.avfeedback) AS feedback, 
															AVG(av.avsolucao) AS solucao,
															AVG(ch.tempoAtendimento) AS atendimento, 
															AVG(((av.avtempo)+(av.avfeedback)+(av.avsolucao))/3) AS media
															FROM chamado ch 
															INNER JOIN categoria ca ON ch.categoria_idcategoria=ca.idcategoria
															INNER JOIN plataforma pl ON ca.plataforma_idplataforma=pl.idplataforma
															INNER JOIN grupo gr ON pl.grupo_idgrupo=gr.idgrupo
															INNER JOIN avaliacao av ON ch.idchamado=av.chamado_idchamado
															WHERE gr.idgrupo IN (select idgrupo from grupo) AND (gr.nome LIKE :pesquisa)
															ORDER BY media DESC");
							$stmt->execute(array("pesquisa"=>$pesquisa));
							while($linha = $stmt->fetch(PDO::FETCH_OBJ)){
								if($linha->nome != null){
									echo "<tr>";
									echo "<td>" . $linha->nome . "</td>" ;					
									echo "<td>" . number_format($linha->tempo , 2). "</td>" ;
									echo "<td>" . number_format( $linha->feedback, 2) ."</td>" ;
									echo "<td>" . number_format($linha->solucao, 2) ."</td>" ;
									echo "<td>" . number_format($linha->media, 2) ."</td>" ;
									echo "<td>" . number_format($linha->atendimento, 2) ."</td>" ;					
									echo "</tr>";
								}
								else{
									echo "Nenhum registro encontrado.";
								}
							}
						break;
					case 'at':
							if($idgrupo == 0){								
								$stmt = $this->conexao->prepare("SELECT 
																us.nome, 
																AVG(av.avtempo) AS tempo, 
																AVG(av.avfeedback) AS feedback, 
																AVG(av.avsolucao) AS solucao, 
																AVG(((av.avtempo)+(av.avfeedback)+(av.avsolucao))/3) AS media
																FROM chamado ch
																INNER JOIN avaliacao av ON ch.idChamado=av.Chamado_idChamado
																INNER JOIN usuario us ON ch.atendente=us.login
																WHERE us.perfil IN (:perfil1,:perfil2) AND (us.nome LIKE :pesquisa)
																ORDER BY media DESC");
								$stmt->execute(array("perfil1"=>"Atendente" , "perfil2"=>"Administrador" , ":pesquisa"=>$pesquisa));
							}
							else{
								$stmt = $this->conexao->prepare("SELECT 
																us.nome, 
																AVG(av.avtempo) AS tempo, 
																AVG(av.avfeedback) AS feedback, 
																AVG(av.avsolucao) AS solucao, 
																AVG(((av.avtempo)+(av.avfeedback)+(av.avsolucao))/3) AS media
																FROM chamado ch
																INNER JOIN avaliacao av ON ch.idChamado=av.Chamado_idChamado
																INNER JOIN usuario us ON ch.atendente=us.login
																WHERE us.idUsuario IN 
																(SELECT Usuario_idUsuario FROM atendente WHERE Grupo_idGrupo = :idgrupo )
																AND (us.nome LIKE :pesquisa)
																ORDER BY media DESC");
								$stmt->execute(array("idgrupo"=>$idgrupo, ":pesquisa"=>$pesquisa));
							}
							
							while($linha = $stmt->fetch(PDO::FETCH_OBJ)){
								if($linha->nome != null){
									echo "<tr>";
									echo "<td>" . $linha->nome . "</td>" ;					
									echo "<td>" . number_format($linha->tempo , 2). "</td>" ;
									echo "<td>" . number_format( $linha->feedback, 2) ."</td>" ;
									echo "<td>" . number_format($linha->solucao, 2) ."</td>" ;
									echo "<td>" . number_format($linha->media, 2) ."</td>" ;					
									echo "</tr>";
								}
								else{
									echo "Nenhum registro encontrado.";
								}
								
							}
						break;
					default:						
						break;
				}
			}
			catch(PDOException $e){
				echo 'Erro: ' . $e->getMessage();
			}
		}
				
		//setters
		public function set_idavaliacao($i){
			$this->id = $i;
		}
		
		public function set_idchamado($c){
			$this->chamadoid = $c;
		}
		
		public function set_observacao($o){
			$this->observacao = $o;
		}
		
		public function set_temporesolucao($t){
			$this->temporesolucao = $t;
		}
		
		public function set_atendente($a){
			$this->atendente = $a;
		}
		
		public function set_solucao($s){
			$this->solucao = $s;
		}
		
		public function set_feedback($f){
			$this->feedback = $f;
		}
		
		//getters
		public function get_idavaliacao(){
			return $this->id;
		}
		
		public function get_idchamado(){
			return $this->chamadoid;
		}
		
		public function get_observacao(){
			return $this->observacao;
		}
		
		public function get_temporesolucao(){
			return $this->temporesolucao;
		}
		
		public function get_atendente(){
			return $this->atendente;
		}
		
		public function get_solucao(){
			return $this->solucao;
		}
		
		public function get_feedback(){
			return $this->feedback;
		}
}
?>