CREATE DATABASE  IF NOT EXISTS `sgc` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `sgc`;
-- MySQL dump 10.13  Distrib 5.6.24, for Win64 (x86_64)
--
-- Host: localhost    Database: sgc
-- ------------------------------------------------------
-- Server version	5.6.17

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `atendente`
--

DROP TABLE IF EXISTS `atendente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `atendente` (
  `Usuario_idUsuario` int(10) NOT NULL,
  `Grupo_idGrupo` int(10) NOT NULL,
  `ativo` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`Usuario_idUsuario`,`Grupo_idGrupo`),
  KEY `fk_Usuario_has_Grupo_Grupo1_idx` (`Grupo_idGrupo`),
  KEY `fk_Usuario_has_Grupo_Usuario1_idx` (`Usuario_idUsuario`),
  CONSTRAINT `fk_Atendente_Grupo` FOREIGN KEY (`Grupo_idGrupo`) REFERENCES `grupo` (`idGrupo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Atendente_Usuario` FOREIGN KEY (`Usuario_idUsuario`) REFERENCES `usuario` (`idUsuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `atendente`
--

LOCK TABLES `atendente` WRITE;
/*!40000 ALTER TABLE `atendente` DISABLE KEYS */;
INSERT INTO `atendente` VALUES (2,1,1),(2,2,1),(2,3,1),(3,1,1),(3,2,1),(3,3,1);
/*!40000 ALTER TABLE `atendente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `avaliacao`
--

DROP TABLE IF EXISTS `avaliacao`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `avaliacao` (
  `idAvaliacao` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Observacao` varchar(150) DEFAULT NULL,
  `avTempo` int(11) NOT NULL,
  `avSolucao` int(11) NOT NULL,
  `avFeedback` int(11) NOT NULL,
  `Chamado_idChamado` int(10) NOT NULL,
  `pendente` char(1) DEFAULT '1',
  PRIMARY KEY (`idAvaliacao`,`Chamado_idChamado`),
  KEY `fk_Avaliacao_Chamado1_idx` (`Chamado_idChamado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `avaliacao`
--

LOCK TABLES `avaliacao` WRITE;
/*!40000 ALTER TABLE `avaliacao` DISABLE KEYS */;
/*!40000 ALTER TABLE `avaliacao` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cargo`
--

DROP TABLE IF EXISTS `cargo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cargo` (
  `idCargo` int(10) NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) NOT NULL,
  `prioridadeCargo` int(11) NOT NULL DEFAULT '0',
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`idCargo`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cargo`
--

LOCK TABLES `cargo` WRITE;
/*!40000 ALTER TABLE `cargo` DISABLE KEYS */;
INSERT INTO `cargo` VALUES (1,'Analista',1,1),(2,'Técnico',2,1),(3,'Assistente',3,1);
/*!40000 ALTER TABLE `cargo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categoria`
--

DROP TABLE IF EXISTS `categoria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categoria` (
  `idCategoria` int(10) NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) NOT NULL,
  `prioridadeCat` int(11) NOT NULL DEFAULT '0',
  `Plataforma_idPlataforma` int(10) NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`idCategoria`,`Plataforma_idPlataforma`),
  KEY `fk_Categoria_Plataforma1_idx` (`Plataforma_idPlataforma`),
  CONSTRAINT `fk_Categoria_Plataforma` FOREIGN KEY (`Plataforma_idPlataforma`) REFERENCES `plataforma` (`idPlataforma`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categoria`
--

LOCK TABLES `categoria` WRITE;
/*!40000 ALTER TABLE `categoria` DISABLE KEYS */;
INSERT INTO `categoria` VALUES (1,'Teclas falhando',1,1,1),(2,'Usuário/Senha',3,2,1),(3,'Lentidão',2,3,1);
/*!40000 ALTER TABLE `categoria` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `chamado`
--

DROP TABLE IF EXISTS `chamado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `chamado` (
  `idChamado` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `assunto` varchar(45) NOT NULL,
  `descricao` longtext NOT NULL,
  `atendente` varchar(45) NOT NULL,
  `status` varchar(45) NOT NULL,
  `anexo` varchar(100) DEFAULT NULL,
  `dataGeracao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dataIniciado` datetime DEFAULT NULL,
  `dataFechado` datetime DEFAULT NULL,
  `tempoAtendimento` varchar(10) DEFAULT NULL,
  `prioridadeChamado` int(11) DEFAULT NULL,
  `Plataforma_idPlataforma` int(10) NOT NULL,
  `Usuario_idUsuario` int(10) NOT NULL,
  `Categoria_idCategoria` int(10) NOT NULL,
  `interacao` longtext,
  PRIMARY KEY (`idChamado`,`Usuario_idUsuario`,`Categoria_idCategoria`,`Plataforma_idPlataforma`),
  KEY `fk_Chamado_Categoria1_idx` (`Categoria_idCategoria`),
  KEY `fk_Chamado_Usuario1_idx` (`Usuario_idUsuario`),
  KEY `fk_Chamado_Plataforma_idx` (`Plataforma_idPlataforma`),
  CONSTRAINT `fk_Chamado_Categoria` FOREIGN KEY (`Categoria_idCategoria`) REFERENCES `categoria` (`idCategoria`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Chamado_Plataforma` FOREIGN KEY (`Plataforma_idPlataforma`) REFERENCES `plataforma` (`idPlataforma`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Chamado_Usuario` FOREIGN KEY (`Usuario_idUsuario`) REFERENCES `usuario` (`idUsuario`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chamado`
--

LOCK TABLES `chamado` WRITE;
/*!40000 ALTER TABLE `chamado` DISABLE KEYS */;
INSERT INTO `chamado` VALUES (1,'Teclado Teclas falhando','A tecla F do meu teclado não funciona direito.','atendente','Iniciado','../Model/anexos/11windowskey.png','2015-11-29 14:11:28',NULL,NULL,NULL,56,1,4,1,NULL),(2,'Link de Dados Lentidão','Minha internet está lenta','','Aberto',NULL,'2015-11-29 18:51:29',NULL,NULL,NULL,60,3,4,3,NULL);
/*!40000 ALTER TABLE `chamado` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `departamento`
--

DROP TABLE IF EXISTS `departamento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `departamento` (
  `idDepartamento` int(10) NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) NOT NULL,
  `prioridadeDep` int(11) NOT NULL DEFAULT '0',
  `ativo` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`idDepartamento`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `departamento`
--

LOCK TABLES `departamento` WRITE;
/*!40000 ALTER TABLE `departamento` DISABLE KEYS */;
INSERT INTO `departamento` VALUES (1,'Tecnologia da Informação',3,1),(2,'Recursos Humanos',3,1),(3,'Serviços Gerais',1,1),(4,'Obtenção',2,1);
/*!40000 ALTER TABLE `departamento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `grupo`
--

DROP TABLE IF EXISTS `grupo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `grupo` (
  `idGrupo` int(10) NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`idGrupo`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `grupo`
--

LOCK TABLES `grupo` WRITE;
/*!40000 ALTER TABLE `grupo` DISABLE KEYS */;
INSERT INTO `grupo` VALUES (1,'Suporte - Hardware',1),(2,'Suporte - Software',1),(3,'Redes',1);
/*!40000 ALTER TABLE `grupo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `logchamado`
--

DROP TABLE IF EXISTS `logchamado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `logchamado` (
  `idLog` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `agenteAlteracao` varchar(45) DEFAULT NULL,
  `descricao` varchar(45) NOT NULL,
  `dataLog` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Chamado_idChamado` int(10) unsigned NOT NULL,
  PRIMARY KEY (`idLog`,`Chamado_idChamado`),
  KEY `fk_LogChamado_Chamado1_idx` (`Chamado_idChamado`),
  CONSTRAINT `fk_LogChamado_Chamado1` FOREIGN KEY (`Chamado_idChamado`) REFERENCES `chamado` (`idChamado`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `logchamado`
--

LOCK TABLES `logchamado` WRITE;
/*!40000 ALTER TABLE `logchamado` DISABLE KEYS */;
INSERT INTO `logchamado` VALUES (1,'Cliente','chamado aberto','2015-11-29 14:11:28',1),(2,'Cliente','chamado aberto','2015-11-29 18:51:29',2),(3,'Adriano','chamado repassado para atendente','2015-11-29 18:52:31',1),(4,'Adriano','Status alterado: Iniciado','2015-11-29 18:52:31',1);
/*!40000 ALTER TABLE `logchamado` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `plataforma`
--

DROP TABLE IF EXISTS `plataforma`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `plataforma` (
  `idPlataforma` int(10) NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) NOT NULL,
  `Grupo_idGrupo` int(10) NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`idPlataforma`,`Grupo_idGrupo`),
  KEY `fk_Plataforma_Grupo1_idx` (`Grupo_idGrupo`),
  CONSTRAINT `fk_Plataforma_Grupo` FOREIGN KEY (`Grupo_idGrupo`) REFERENCES `grupo` (`idGrupo`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plataforma`
--

LOCK TABLES `plataforma` WRITE;
/*!40000 ALTER TABLE `plataforma` DISABLE KEYS */;
INSERT INTO `plataforma` VALUES (1,'Teclado',1,1),(2,'Login',2,1),(3,'Link de Dados',3,1);
/*!40000 ALTER TABLE `plataforma` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuario` (
  `idUsuario` int(10) NOT NULL AUTO_INCREMENT,
  `cpf` char(11) NOT NULL,
  `nome` varchar(45) NOT NULL,
  `email` varchar(45) NOT NULL,
  `ramal` varchar(10) DEFAULT NULL,
  `celular` varchar(20) DEFAULT NULL,
  `login` varchar(45) NOT NULL,
  `senha` varchar(45) NOT NULL,
  `perfil` varchar(45) NOT NULL,
  `Departamento_idDepartamento` int(10) NOT NULL,
  `Cargo_idCargo` int(10) NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `prioridadeUsuario` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idUsuario`,`Departamento_idDepartamento`),
  UNIQUE KEY `cpf_UNIQUE` (`cpf`),
  UNIQUE KEY `login_UNIQUE` (`login`),
  KEY `fk_Usuario_Departamento1_idx` (`Departamento_idDepartamento`),
  KEY `fk_Usuario_Cargo1_idx` (`Cargo_idCargo`),
  KEY `fk_Usuario_Atendente_idx` (`idUsuario`),
  CONSTRAINT `fk_Usuario_Cargo` FOREIGN KEY (`Cargo_idCargo`) REFERENCES `cargo` (`idCargo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_Usuario_Departamento` FOREIGN KEY (`Departamento_idDepartamento`) REFERENCES `departamento` (`idDepartamento`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES (1,'12345678900','Administrador','admin@empresa.com','1234','987654321','admin','202cb962ac59075b964b07152d234b70','Administrador',1,1,1,1),(2,'09876543211','Adriano','adriano@empresa.com','9138','981698877','adriano','202cb962ac59075b964b07152d234b70','Administrador',1,1,1,20),(3,'23456789011','Atendente','atendente@empresa.co','9157','987654455','atendente','202cb962ac59075b964b07152d234b70','Atendente',1,2,1,32),(4,'34524317896','Cliente','cliente@empresa.com','2233','988777788','cliente','202cb962ac59075b964b07152d234b70','Comum',4,3,1,52);
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-11-29 17:14:29
