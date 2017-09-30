CREATE TABLE `#__cidade` ( 
		`id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT, 
		`nome` VARCHAR(150) NOT NULL , 
		`uf` CHAR(2) NOT NULL
) ENGINE = InnoDB   DEFAULT CHARSET=utf8;

CREATE TABLE `#__uf` (
  `ds_uf_sigla` char(2) NOT NULL,
  `ds_uf_nome` varchar(255) NOT NULL,
  PRIMARY KEY (`ds_uf_sigla`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

ALTER TABLE `#__cidade` ADD INDEX `FK_CIDADE_UF_idx` (`uf` ASC);
ALTER TABLE `#__cidade` ADD CONSTRAINT `FK_CIDADE_UF`  FOREIGN KEY (`uf`) REFERENCES `#__uf` (`ds_uf_sigla`);


CREATE TABLE `#__angelgirls_agenda` ( 
		`id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT, 
		`titulo` VARCHAR(100) NOT NULL, 
		
		`tipo` VARCHAR(20) DEFAULT 'SESSAO', 
		`nome_foto` varchar(150)  NULL,
		`descricao` TEXT NULL , 
		`meta_descricao` VARCHAR(250) NOT NULL ,

		`audiencia_gostou` INT DEFAULT 0,
		`audiencia_ngostou` INT DEFAULT 0,
		`audiencia_view` INT DEFAULT 0,
		
		`data` DATE NOT NULL, 
		
		`id_tema` INT,
		`id_modelo` INT,
		`id_locacao` INT,
		`id_fotografo` INT,
		`host_ip_criador` varchar(20) NOT NULL,
		`host_ip_alterador` varchar(20) NULL,
		`publicar` DATE, 
		`status_dado` VARCHAR(25) DEFAULT 'NOVO',
		`id_usuario_criador` INT NOT NULL , 
		`id_usuario_alterador` INT NOT NULL , 
		`data_criado` DATETIME NOT NULL  , 
		`data_alterado` DATETIME NOT NULL 
		
) ENGINE = InnoDB   DEFAULT CHARSET=utf8;

CREATE TABLE `#__angelgirls_email` ( 
		`id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT, 
		`principal` ENUM('S','N') NOT NULL, 
		`email` VARCHAR(150) NOT NULL , 
		`id_usuario` INT NOT NULL,
		`ordem` int,
		`host_ip_criador` varchar(20) NOT NULL,
		`host_ip_alterador` varchar(20) NULL,
		`status_dado` VARCHAR(25) DEFAULT 'NOVO',
		`id_usuario_criador` INT NOT NULL , 
		`id_usuario_alterador` INT NOT NULL , 
		`data_criado` DATETIME NOT NULL  , 
		`data_alterado` DATETIME NOT NULL  
) ENGINE = InnoDB   DEFAULT CHARSET=utf8;


CREATE TABLE `#__angelgirls_redesocial` ( 
		`id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT, 
		`principal` ENUM('S','N') NOT NULL, 
		`publico` ENUM('S','N','A','E') NOT NULL,
		`rede_social` VARCHAR(150) NOT NULL , 
		`url_usuario` VARCHAR(150) NOT NULL , 
		`id_usuario` INT NOT NULL ,
		`ordem` int,
		`host_ip_criador` varchar(20) NOT NULL,
		`host_ip_alterador` varchar(20) NULL,
		`status_dado` VARCHAR(25) DEFAULT 'NOVO',
		`id_usuario_criador` INT NOT NULL , 
		`id_usuario_alterador` INT NOT NULL , 
		`data_criado` DATETIME NOT NULL  , 
		`data_alterado` DATETIME NOT NULL  
) ENGINE = InnoDB   DEFAULT CHARSET=utf8;

CREATE TABLE `#__angelgirls_telefone` ( 
		`id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT, 
		`tipo` ENUM('CELULAR','RESIDENCIAL','ESCRITORIO','RECADO','OUTRO'), 
		`principal` ENUM('S','N') NOT NULL, 
		`operadora` VARCHAR(15), 
		`ddi` VARCHAR(4) DEFAULT '+55', 
		`ddd` VARCHAR(3) NOT NULL , 
		`telefone` VARCHAR(15) NOT NULL , 
		`id_usuario` INT NOT NULL ,
		`ordem` int,
		`host_ip_criador` varchar(20) NOT NULL,
		`host_ip_alterador` varchar(20) NULL,
		`status_dado` VARCHAR(25) DEFAULT 'NOVO',
		`id_usuario_criador` INT NOT NULL , 
		`id_usuario_alterador` INT NOT NULL , 
		`data_criado` DATETIME NOT NULL  , 
		`data_alterado` DATETIME NOT NULL  
) ENGINE = InnoDB   DEFAULT CHARSET=utf8;

CREATE TABLE `#__angelgirls_endereco` ( 
		`id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT, 
		`tipo` ENUM('RESIDENCIAL','COMERCIAL','OUTRO'), 
		`principal` ENUM('S','N') NOT NULL,
		`endereco` VARCHAR(250) NOT NULL, 
		`numero` VARCHAR(10),
		`bairro` VARCHAR(100),
		`complemento` VARCHAR(100),
		`cep` VARCHAR(15) NOT NULL,
		`id_cidade` INT NOT NULL, 
		`id_usuario` INT NOT NULL ,
		`ordem` int,
		`host_ip_criador` varchar(20) NOT NULL,
		`host_ip_alterador` varchar(20) NULL,
		`status_dado` VARCHAR(25) DEFAULT 'NOVO',
		`id_usuario_criador` INT NOT NULL , 
		`id_usuario_alterador` INT NOT NULL , 
		`data_criado` DATETIME NOT NULL  , 
		`data_alterado` DATETIME NOT NULL  
) ENGINE = InnoDB   DEFAULT CHARSET=utf8;





CREATE TABLE `#__angelgirls_modelo` ( 
		`id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT, 
		`id_usuario` INT UNIQUE NOT NULL , 
		`nome_artistico` VARCHAR(150) NOT NULL, 
		`descricao` TEXT NULL, 
		`meta_descricao` VARCHAR(250) NOT NULL ,
		
		`pontos` bigint DEFAULT 0,
		
		`foto_documento` VARCHAR(100),
		`foto_comp_residencia` VARCHAR(100),
		`status_documento` VARCHAR(20),
		`status_comp_residencia` VARCHAR(20),
		`token` VARCHAR(250) UNIQUE NOT NULL,
		`nivel` INT NULL DEFAULT 0,
		`foto_perfil` VARCHAR(100), 
		`foto_inteira` VARCHAR(100),
		`foto_inteira_horizontal` VARCHAR(100), 
		`altura` NUMERIC(5,2) , 
		`peso` NUMERIC(3) , 
		`busto` NUMERIC(3) , 
		`calsa` NUMERIC(3) , 
		`calsado` NUMERIC(3) , 
		`olhos` ENUM('NEGROS','AZUIS','VERDES','CASTANHOS','MEL','OUTRO') , 
		`pele` ENUM('CALCASIANA','BRANCA','PARDA','MORENA','NEGRA','AMARELA','OUTRO') , 
		`etinia` ENUM('AZIATICA','AFRO','EURPEIA','ORIENTAL','LATINA','OUTRO'), 
		`cabelo` ENUM('LIZO','ENCARACOLADO','CACHIADO','ONDULADOS','CRESPO','OUTRO','SEM'), 
		`tamanho_cabelo` ENUM('SEM','MUITO CURTO','CURTO','MEDIO','LONGO','MUITO LONGO','OUTRO'), 
		`cor_cabelo` ENUM('BRANCO','LOIRA CLARA','LOIRA','LOIRO ESCURO','COLORIDO','RUIVA','SEM','CASTANHO','NEGRO','OUTRO'), 
		`outra_cor_cabelo` VARCHAR(25),
		`profissao` VARCHAR(25),
		`nascionalidade` VARCHAR(25),
		`id_cidade_nasceu` INT,
		`data_nascimento` DATE,
		`site` VARCHAR(250),
		`sexo` ENUM('M','F') DEFAULT 'F',
		
		`cpf` VARCHAR(14) NOT NULL UNIQUE,
		`banco` VARCHAR(14),
		`agencia` VARCHAR(14),
		`conta` VARCHAR(14),
		
		`custo_medio_diaria` NUMERIC(12,2) DEFAULT 0,
		
		
		
		
		`status_modelo` VARCHAR(14) DEFAULT 'NOVA',
		`qualificao_equipe` INT DEFAULT 0,
		`audiencia_gostou` INT DEFAULT 0,
		`audiencia_ngostou` INT DEFAULT 0,
		`audiencia_view` INT DEFAULT 0,
		`id_cidade` INT NOT NULL ,
		
		`status_dado` VARCHAR(25) DEFAULT 'NOVO',
		`id_usuario_criador` INT NOT NULL , 
		`id_usuario_alterador` INT NOT NULL , 
		`data_criado` DATETIME NOT NULL  , 
		`data_alterado` DATETIME NOT NULL,
		`host_ip_criador` varchar(20) NOT NULL,
		`host_ip_alterador` varchar(20) NULL,
		FOREIGN KEY (`id_usuario_criador`) REFERENCES `#__users` (`id`),
		FOREIGN KEY (`id_usuario_alterador`) REFERENCES `#__users` (`id`),
		FOREIGN KEY (`id_usuario`) REFERENCES `#__users` (`id`)
) ENGINE = InnoDB   DEFAULT CHARSET=utf8;


CREATE TABLE `#__angelgirls_fotografo` ( 
		`id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT, 
		`id_usuario` INT UNIQUE NOT NULL , 
		`nome_artistico` VARCHAR(150) NOT NULL, 
		`descricao` TEXT NULL , 
		`meta_descricao` VARCHAR(250) NOT NULL ,
		
		`nome_foto` varchar(150)  NULL,
		
		`pontos` bigint DEFAULT 0,
		
		`data_nascimento` DATE,
		`sexo` ENUM('M','F') NOT NULL,
		`nascionalidade` VARCHAR(25),
		`id_cidade_nasceu` INT,
		`site` VARCHAR(250),
		`profissao` VARCHAR(25),
		`id_cidade` INT NOT NULL,
		`token` VARCHAR(250) UNIQUE NOT NULL,
		`nivel` INT NULL DEFAULT 0,
		`foto_documento` VARCHAR(100),
		`foto_comp_residencia` VARCHAR(100),
		`status_documento` VARCHAR(20),
		`status_comp_residencia` VARCHAR(20),
		

		`status_fotografo` VARCHAR(14),
		`qualificao_equipe` INT DEFAULT 0,
		`audiencia_gostou` INT DEFAULT 0,
		`audiencia_ngostou` INT DEFAULT 0,
		`audiencia_view` INT DEFAULT 0,
		
		`custo_medio_diaria` NUMERIC(12,2) DEFAULT 0,
		
		`cpf` VARCHAR(14) NOT NULL UNIQUE,
		`banco` VARCHAR(14),
		`agencia` VARCHAR(14),
		`conta` VARCHAR(14),
		
		`status_dado` VARCHAR(25) DEFAULT 'NOVO',
		`id_usuario_criador` INT NOT NULL , 
		`id_usuario_alterador` INT NOT NULL , 
		`data_criado` DATETIME NOT NULL  , 
		`data_alterado` DATETIME NOT NULL,
		`host_ip_criador` varchar(20) NOT NULL,
		`host_ip_alterador` varchar(20) NULL,
		FOREIGN KEY (`id_usuario_criador`) REFERENCES `#__users` (`id`),
		FOREIGN KEY (`id_usuario_alterador`) REFERENCES `#__users` (`id`),
		FOREIGN KEY (`id_usuario`) REFERENCES `#__users` (`id`)  
) ENGINE = InnoDB   DEFAULT CHARSET=utf8;


CREATE TABLE `#__angelgirls_visitante` ( 
		`id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT, 
		`id_usuario` INT UNIQUE NOT NULL, 
		`sobre` TEXT NULL , 
		`apelido` VARCHAR(150) NOT NULL, 
		`meta_descricao` VARCHAR(250) NOT NULL ,
		`nome_foto` varchar(150)  NULL, 
		`data_nascimento` DATE NOT NULL,
		`pontos` bigint DEFAULT 0,
		`sexo` ENUM('M','F') NOT NULL,
		`site` VARCHAR(250),
		`profissao` VARCHAR(25),
		`token` VARCHAR(250) UNIQUE NOT NULL,
		`nivel` INT NULL DEFAULT 0,
		`nascionalidade` VARCHAR(25),
		`id_cidade_nasceu` INT,
		`id_cidade` INT NOT NULL,
		`qualificao_equipe` INT DEFAULT 0,
		`audiencia_gostou` INT DEFAULT 0,
		`audiencia_ngostou` INT DEFAULT 0,
		`audiencia_view` INT DEFAULT 0,
		`custo_medio_diaria` NUMERIC(12,2) DEFAULT 0,
		`cpf` VARCHAR(14) NOT NULL UNIQUE,
		`banco` VARCHAR(14),
		`agencia` VARCHAR(14),
		`conta` VARCHAR(14),
		`status_dado` VARCHAR(25) DEFAULT 'NOVO',
		`id_usuario_criador` INT NOT NULL , 
		`id_usuario_alterador` INT NOT NULL , 
		`data_criado` DATETIME NOT NULL  , 
		`data_alterado` DATETIME NOT NULL,
		`host_ip_criador` varchar(20) NOT NULL,
		`host_ip_alterador` varchar(20) NULL,
		FOREIGN KEY (`id_usuario_criador`) REFERENCES `#__users` (`id`),
		FOREIGN KEY (`id_usuario_alterador`) REFERENCES `#__users` (`id`),
		FOREIGN KEY (`id_usuario`) REFERENCES `#__users` (`id`)  
) ENGINE = InnoDB   DEFAULT CHARSET=utf8;


CREATE TABLE `#__angelgirls_equipe` ( 
		`id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT, 
		`id_usuario` INT NOT NULL , 
		`nome_artistico` VARCHAR(150) NOT NULL, 
		`descricao` TEXT NULL , 
		`meta_descricao` VARCHAR(250) NOT NULL , 
		`possui_foto_perfil` ENUM('S','N') NOT NULL , 
		`data_nascimento` DATE,
		`sexo` ENUM('M','F') NOT NULL,

		`site` VARCHAR(250),
		`profissao` VARCHAR(25),
		`id_cidade` INT NOT NULL,
		

		`status_fotografo` VARCHAR(14),
		`qualificao_equipe` INT DEFAULT 0,
		`audiencia_gostou` INT DEFAULT 0,
		`audiencia_ngostou` INT DEFAULT 0,
		`audiencia_view` INT DEFAULT 0,
		
		`custo_medio_diaria` NUMERIC(12,2) DEFAULT 0,
		
		`cpf` VARCHAR(14) NOT NULL UNIQUE,
		`banco` VARCHAR(14),
		`agencia` VARCHAR(14),
		`conta` VARCHAR(14),
		
		`status_dado` VARCHAR(25) DEFAULT 'NOVO',
		`id_usuario_criador` INT NOT NULL , 
		`id_usuario_alterador` INT NOT NULL , 
		`data_criado` DATETIME NOT NULL  , 
		`data_alterado` DATETIME NOT NULL,
		`host_ip_criador` varchar(20) NOT NULL,
		`host_ip_alterador` varchar(20) NULL
) ENGINE = InnoDB   DEFAULT CHARSET=utf8;


CREATE TABLE `#__angelgirls_locacao` ( 
		`id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT, 
		`nome` VARCHAR(250) NOT NULL UNIQUE, 
		`endereco` VARCHAR(250), 
		`numero` VARCHAR(10),
		`bairro` VARCHAR(100),
		`complemento` VARCHAR(100),
		`cep` VARCHAR(15),
		`id_cidade` INT NOT NULL, 
		
		`descricao` TEXT NULL , 
		`meta_descricao` VARCHAR(250) NOT NULL , 
		
		`nome_foto` varchar(150)  NULL, 
		
		`site` VARCHAR(250),
		`ddd_telefone` VARCHAR(3),
		`telefone` VARCHAR(15),
		`email` VARCHAR(100),
		
		`custo_medio_diaria` NUMERIC(12,2) DEFAULT 0,
		
		`audiencia_gostou` INT DEFAULT 0,
		`audiencia_ngostou` INT DEFAULT 0,
		`audiencia_view` INT DEFAULT 0,
		
		`status_dado` VARCHAR(25) DEFAULT 'NOVO',
		`id_usuario_criador` INT NOT NULL , 
		`id_usuario_alterador` INT NOT NULL , 
		`data_criado` DATETIME NOT NULL  , 
		`data_alterado` DATETIME NOT NULL ,
		`host_ip_criador` varchar(20) NOT NULL,
		`host_ip_alterador` varchar(20) NULL
) ENGINE = InnoDB   DEFAULT CHARSET=utf8;


CREATE TABLE `#__angelgirls_tema` ( 
		`id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT, 
		`nome` VARCHAR(250) NOT NULL UNIQUE, 
		`descricao` TEXT NULL , 
		`meta_descricao` VARCHAR(250) NOT NULL , 
		
		`nome_foto` varchar(150)  NULL,
		
		`audiencia_gostou` INT DEFAULT 0,
		`audiencia_ngostou` INT DEFAULT 0,
		`audiencia_view` INT DEFAULT 0,
		
		`status_dado` VARCHAR(25) DEFAULT 'NOVO',
		`id_usuario_criador` INT NOT NULL , 
		`id_usuario_alterador` INT NOT NULL , 
		`data_criado` DATETIME NOT NULL  , 
		`data_alterado` DATETIME NOT NULL,
		`host_ip_criador` varchar(20) NOT NULL,
		`host_ip_alterador` varchar(20) NULL
) ENGINE = InnoDB   DEFAULT CHARSET=utf8;

CREATE TABLE `#__angelgirls_figurino` ( 
	`id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT, 
	`nome` VARCHAR(250) NOT NULL,
	`descricao` TEXT NULL , 

	`meta_descricao` VARCHAR(250) NOT NULL , 
	
	`nome_foto` varchar(150)  NULL,
	
	`audiencia_gostou` INT DEFAULT 0,
	`audiencia_ngostou` INT DEFAULT 0,
	`audiencia_view` INT DEFAULT 0,
	
	`status_dado` VARCHAR(25) DEFAULT 'NOVO',
	`id_usuario_criador` INT NOT NULL , 
	`id_usuario_alterador` INT NOT NULL , 
	`data_criado` DATETIME NOT NULL, 
	`data_alterado` DATETIME NOT NULL,
	`host_ip_criador` varchar(20) NOT NULL,
	`host_ip_alterador` varchar(20) NULL,
	FOREIGN KEY (`id_usuario_criador`) REFERENCES `#__users` (`id`),
	FOREIGN KEY (`id_usuario_alterador`) REFERENCES `#__users` (`id`)
) ENGINE = InnoDB   DEFAULT CHARSET=utf8;





CREATE TABLE `#__angelgirls_sessao` ( 
		`id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT, 
		`titulo` VARCHAR(250) NOT NULL UNIQUE, 
		
		`nome_foto` varchar(150)  NULL,
		
		`tipo` enum('VENDA','PONTOS','PATROCINIO','LEILAO')  DEFAULT 'VENDA',
		
		`executada` DATE NOT NULL, 
		`descricao` TEXT NULL , 
		`historia` TEXT NULL , 
		`comentario_fotografo` TEXT NULL , 
		`comentario_modelos` TEXT NULL , 
		`comentario_equipe` TEXT NULL , 
		`meta_descricao` VARCHAR(250) NOT NULL , 
		`token` VARCHAR(120) NULL,
		
		`id_agenda` INT,
		`id_tema` INT NULL,
		`id_modelo_principal` INT  NOT NULL,
		`id_modelo_secundaria` INT,
		`id_locacao` INT NULL,
		`id_fotografo_principal` INT  NOT NULL,
		`id_fotografo_secundario` INT,
		
		`id_figurino_principal` INT,
		`id_figurino_secundario` INT,
		
		`status_modelo_principal` INT DEFAULT 0,
		`status_modelo_secundaria` INT DEFAULT 0,
		`status_fotografo_principal` INT DEFAULT 0,
		`status_fotografo_secundario` INT DEFAULT 0,
		
		
		`motivo_repro_modelo_principal` varchar(250),
		`motivo_repro_modelo_secundaria` varchar(250),
		`motivo_repro_fotografo_principal` varchar(250),
		`motivo_repro_fotografo_secundario` varchar(250),

		
		`audiencia_gostou` INT DEFAULT 0,
		`audiencia_ngostou` INT DEFAULT 0,
		`audiencia_view` INT DEFAULT 0,
		
		`publicar` DATE NOT NULL,
		`status_dado` VARCHAR(25) DEFAULT 'NOVO',
		`id_usuario_criador` INT NOT NULL , 
		`id_usuario_alterador` INT NOT NULL , 
		`data_criado` DATETIME NOT NULL, 
		`data_alterado` DATETIME NOT NULL,
		`host_ip_criador` varchar(20) NOT NULL,
		`host_ip_alterador` varchar(20) NULL,
		FOREIGN KEY (`id_agenda`) REFERENCES `#__angelgirls_agenda` (`id`),
		FOREIGN KEY (`id_tema`) REFERENCES `#__angelgirls_tema` (`id`),
		FOREIGN KEY (`id_modelo_principal`) REFERENCES `#__angelgirls_modelo` (`id`),
		FOREIGN KEY (`id_modelo_secundaria`) REFERENCES `#__angelgirls_modelo` (`id`),
		FOREIGN KEY (`id_locacao`) REFERENCES `#__angelgirls_locacao` (`id`),
		FOREIGN KEY (`id_fotografo_principal`) REFERENCES `#__angelgirls_fotografo` (`id`),
		FOREIGN KEY (`id_fotografo_secundario`) REFERENCES `#__angelgirls_fotografo` (`id`),
		FOREIGN KEY (`id_figurino_principal`) REFERENCES `#__angelgirls_figurino` (`id`),
		FOREIGN KEY (`id_figurino_secundario`) REFERENCES `#__angelgirls_figurino` (`id`),
		FOREIGN KEY (`id_usuario_criador`) REFERENCES `#__users` (`id`),
		FOREIGN KEY (`id_usuario_alterador`) REFERENCES `#__users` (`id`),
		FOREIGN KEY (`id_sessao`) REFERENCES `#__angelgirls_sessao` (`id`)
) ENGINE = InnoDB   DEFAULT CHARSET=utf8;



CREATE TABLE `#__angelgirls_video_sessao` (
	`id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	`titulo` VARCHAR(250) NOT NULL ,
	`id_sessao` INT NOT NULL, 
	`url_youtube` VARCHAR(250),
	`id_youtube` VARCHAR(250),
	`id_vimeo` VARCHAR(250),
	`url_vimeo` VARCHAR(250),
	`arquivo` VARCHAR(25),
	`token` VARCHAR(25) UNIQUE NOT NULL,
	`ordem` int,
	`descricao` TEXT NULL , 
	`meta_descricao` VARCHAR(250) NOT NULL , 
	`tipo` ENUM('MAKINGOF','AUTORIZACAOMODELO','OUTRO') DEFAULT 'MAKINGOF',
	`status_dado` VARCHAR(25) DEFAULT 'NOVO',
	`id_usuario_criador` INT NOT NULL , 
	`id_usuario_alterador` INT NOT NULL , 
	`data_criado` DATETIME NOT NULL, 
	`data_alterado` DATETIME NOT NULL,
	`host_ip_criador` varchar(20) NOT NULL,
	`host_ip_alterador` varchar(20) NULL,
	FOREIGN KEY (`id_usuario_criador`) REFERENCES `#__users` (`id`),
	FOREIGN KEY (`id_usuario_alterador`) REFERENCES `#__users` (`id`),
	FOREIGN KEY (`id_sessao`) REFERENCES `#__angelgirls_sessao` (`id`)
) ENGINE = InnoDB   DEFAULT CHARSET=utf8;


CREATE TABLE `#__angelgirls_extrato_pontos` ( 
		`id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT, 
		
		`chave` VARCHAR(25) DEFAULT 'SESSAO.CRIADA',
		`pontos` bigint,
		`motivo` text,
		
		`id_usuario` INT NOT NULL , 
		`data` DATETIME NOT NULL, 
		`host_ip` varchar(20) NOT NULL,

		FOREIGN KEY (`id_usuario`) REFERENCES `#__users` (`id`)
) ENGINE = InnoDB   DEFAULT CHARSET=utf8;



CREATE TABLE `#__angelgirls_mensagens` ( 
		`id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT, 
		`id_resposta` INT, 
		`titulo` VARCHAR(250) NOT NULL, 
		`id_usuario_destino` INT NOT NULL ,
		`mensagem` TEXT  NOT NULL ,
		`token` varchar(250) NULL ,
		`tipo` int DEFAULT 1,
		`status_dado` VARCHAR(25) DEFAULT 'NOVO',
		 `status_remetente` VARCHAR(25) NULL DEFAULT 'NOVO',
		 `status_destinatario` VARCHAR(25) NULL DEFAULT 'NOVO' AFTER `status_remetente`,
		 `flag_remetente` INT NULL DEFAULT 0 AFTER `status_destinatario`,
		 `flag_destinatario` INT NULL DEFAULT 0 AFTER `flag_remetente`,
		`lido_remetente` INT NULL DEFAULT 0,
		`lido_destinatario` INT NULL DEFAULT 0,
		`enviado` INT NULL DEFAULT 0,
		`id_usuario_remetente` INT NOT NULL , 
 		`data_criado` DATETIME NOT NULL,
 		`data_lida` DATETIME NULL,
		`host_ip_criador` varchar(20) NOT NULL,
		`host_ip_alterador` varchar(20) NULL,
 		FOREIGN KEY (`id_usuario_remetente`) REFERENCES `#__users` (`id`),
		FOREIGN KEY (`id_usuario_destino`) REFERENCES `#__users` (`id`),
		FOREIGN KEY (`id_resposta`) REFERENCES `#__angelgirls_mensagens` (`id`)
) ENGINE = InnoDB   DEFAULT CHARSET=utf8;



CREATE TABLE `#__angelgirls_foto_sessao` ( 
		`id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT, 
		`titulo` VARCHAR(250) NOT NULL, 
		
		`descricao` TEXT NULL , 
		`meta_descricao` VARCHAR(250) NOT NULL, 
		`token` VARCHAR(120) NOT NULL,
		`token_imagem` VARCHAR(120) NULL,
		
		`possui_nudes` CHAR(1) DEFAULT 'S',
		`area_vip` CHAR(1) DEFAULT 'S',
		
		`id_sessao` INT NOT NULL,
		`ordem` INT,
		
		`audiencia_gostou` INT DEFAULT 0,
		`audiencia_ngostou` INT DEFAULT 0,
		`audiencia_view` INT DEFAULT 0,
		
		
		
		
		`status_dado` VARCHAR(25) DEFAULT 'NOVO',
		`id_usuario_criador` INT NOT NULL , 
		`id_usuario_alterador` INT NOT NULL , 
		`data_criado` DATETIME NOT NULL  , 
		`data_alterado` DATETIME NOT NULL,
		`host_ip_criador` varchar(20) NOT NULL,
		`host_ip_alterador` varchar(20) NULL,
		
		FOREIGN KEY (`id_sessao`) REFERENCES `#__angelgirls_sessao` (`id`),
		FOREIGN KEY (`id_usuario_criador`) REFERENCES `#__users` (`id`),
		FOREIGN KEY (`id_usuario_alterador`) REFERENCES `#__users` (`id`)
) ENGINE = InnoDB   DEFAULT CHARSET=utf8;

CREATE TABLE `#__angelgirls_post` (
	`id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	`id_usuario` INT NOT NULL,
	`texto` TEXT NOT NULL , 
	
	`audiencia_gostou` INT DEFAULT 0,
	`audiencia_ngostou` INT DEFAULT 0,
	`audiencia_view` INT DEFAULT 0,

	`status_dado` VARCHAR(25) DEFAULT 'NOVO',
	`id_usuario_criador` INT NOT NULL , 
	`id_usuario_alterador` INT NOT NULL , 
	`data_criado` DATETIME NOT NULL  , 
	`data_alterado` DATETIME NOT NULL,
	`host_ip_criador` varchar(20) NOT NULL,
	`host_ip_alterador` varchar(20) NULL,
	FOREIGN KEY (`id_usuario_criador`) REFERENCES `#__users` (`id`),
	FOREIGN KEY (`id_usuario_alterador`) REFERENCES `#__users` (`id`)
)ENGINE = InnoDB   DEFAULT CHARSET=utf8;


CREATE TABLE `#__angelgirls_post_arquivo` (
	`id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	`tipo` varchar(10) NOT NULL,
	`id_post` INT NOT NULL , 
	
	`token` VARCHAR(120) NOT NULL,
	
	`arquivo` varchar(10) NOT NULL,
	
	`audiencia_gostou` INT DEFAULT 0,
	`audiencia_ngostou` INT DEFAULT 0,
	`audiencia_view` INT DEFAULT 0,

	`status_dado` VARCHAR(25) DEFAULT 'NOVO',
	`id_usuario_criador` INT NOT NULL , 
	`id_usuario_alterador` INT NOT NULL , 
	`data_criado` DATETIME NOT NULL  , 
	`data_alterado` DATETIME NOT NULL,
	`host_ip_criador` varchar(20) NOT NULL,
	`host_ip_alterador` varchar(20) NULL,
	FOREIGN KEY (`id_post`) REFERENCES `#__angelgirls_post` (`id`),
	FOREIGN KEY (`id_usuario_criador`) REFERENCES `#__users` (`id`),
	FOREIGN KEY (`id_usuario_alterador`) REFERENCES `#__users` (`id`)
)ENGINE = InnoDB   DEFAULT CHARSET=utf8;



CREATE TABLE `#__angelgirls_comentario_post` (
	`id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	`id_usuario` INT NOT NULL,
	`id_post` INT NOT NULL,
	`texto` TEXT NOT NULL , 
	
	`tipo` VARCHAR(20) NULL,
	`codigo` VARCHAR(20) NULL,
	
	`audiencia_gostou` INT DEFAULT 0,
	`audiencia_ngostou` INT DEFAULT 0,
	`audiencia_view` INT DEFAULT 0,
	
	`status_dado` VARCHAR(25) DEFAULT 'NOVO',
	`id_usuario_criador` INT NOT NULL , 
	`id_usuario_alterador` INT NOT NULL , 
	`data_criado` DATETIME NOT NULL  , 
	`data_alterado` DATETIME NOT NULL,
	`host_ip_criador` varchar(20) NOT NULL,
	`host_ip_alterador` varchar(20) NULL
)ENGINE = InnoDB   DEFAULT CHARSET=utf8;


CREATE TABLE `#__angelgirls_promocao` ( 
		`id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT, 
		`titulo` VARCHAR(250) NOT NULL UNIQUE, 
		
		`nome_foto` varchar(150)  NULL,
		
		`descricao` TEXT NULL , 
		`meta_descricao` VARCHAR(250) NOT NULL , 
		
		`inicio` DATE NOT NULL,
		`fim` DATE NOT NULL,
		
		
		`publicar` DATE NOT NULL,
		`status_dado` VARCHAR(25) DEFAULT 'NOVO',
		`id_usuario_criador` INT NOT NULL , 
		`id_usuario_alterador` INT NOT NULL , 
		`data_criado` DATETIME NOT NULL, 
		`data_alterado` DATETIME NOT NULL,
		`host_ip_criador` varchar(20) NOT NULL,
		`host_ip_alterador` varchar(20) NULL
) ENGINE = InnoDB   DEFAULT CHARSET=utf8;





CREATE TABLE `#__angelgirls_album` ( 
		`id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT, 
		`titulo` VARCHAR(250) NOT NULL UNIQUE, 
		
		`id_foto_capa` INT  NULL,
		
		`executada` DATE NOT NULL, 
		`descricao` TEXT NULL , 
		`meta_descricao` VARCHAR(250) NOT NULL , 
		`token` VARCHAR(120) NULL,
		`audiencia_gostou` INT DEFAULT 0,
		`audiencia_ngostou` INT DEFAULT 0,
		`audiencia_view` INT DEFAULT 0,
		
		`publicar` DATE NOT NULL,
		`status_dado` VARCHAR(25) DEFAULT 'NOVO',
		`id_usuario_criador` INT NOT NULL , 
		`id_usuario_alterador` INT NOT NULL , 
		`data_criado` DATETIME NOT NULL  , 
		`data_alterado` DATETIME NOT NULL ,
		`host_ip_criador` varchar(20) NOT NULL,
		`host_ip_alterador` varchar(20) NULL,
		FOREIGN KEY (`id_usuario_criador`) REFERENCES `#__users` (`id`),
		FOREIGN KEY (`id_usuario_alterador`) REFERENCES `#__users` (`id`)
) ENGINE = InnoDB   DEFAULT CHARSET=utf8;



CREATE TABLE `#__angelgirls_foto_album` ( 
		`id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT, 
		`titulo` VARCHAR(250) NOT NULL, 
		`nome_arquivo` VARCHAR(250) NOT NULL,
		`descricao` TEXT NULL , 
		`meta_descricao` VARCHAR(250) NOT NULL,
		`id_album` INT NOT NULL,
		`token` VARCHAR(120) NOT NULL,
		`token_imagem` VARCHAR(120) NULL,
		
		`possui_nudes` CHAR(1) DEFAULT 'S',
		`area_vip` CHAR(1) DEFAULT 'S',
		
		`ordem` INT,
		`audiencia_gostou` INT DEFAULT 0,
		`audiencia_ngostou` INT DEFAULT 0,
		`audiencia_view` INT DEFAULT 0,
		`status_dado` VARCHAR(25) DEFAULT 'NOVO',
		`id_usuario_criador` INT NOT NULL , 
		`id_usuario_alterador` INT NOT NULL , 
		`data_criado` DATETIME NOT NULL  , 
		`data_alterado` DATETIME NOT NULL,
		`host_ip_criador` varchar(20) NOT NULL,
		`host_ip_alterador` varchar(20) NULL,
		FOREIGN KEY (`id_usuario_criador`) REFERENCES `#__users` (`id`),
		FOREIGN KEY (`id_usuario_alterador`) REFERENCES `#__users` (`id`),
		FOREIGN KEY (`id_album`) REFERENCES `#__angelgirls_album` (`id`)
) ENGINE = InnoDB   DEFAULT CHARSET=utf8;

ALTER TABLE  `#__angelgirls_album` ADD CONSTRAINT FOREIGN KEY (`id_foto_capa`) REFERENCES `#__angelgirls_foto_album` (`id`);

CREATE TABLE `#__angelgirls_vt_foto_album` ( 
	`id_foto` INT NOT NULL, 
	`id_usuario` INT NOT NULL, 
	`data_criado` DATETIME NOT NULL,
	`host_ip` varchar(20) NOT NULL,
	FOREIGN KEY (`id_usuario`) REFERENCES `#__users` (`id`),
	FOREIGN KEY (`id_foto`) REFERENCES `#__angelgirls_foto_album` (`id`),
	PRIMARY KEY(`id_foto`,`id_usuario`)
) ENGINE = InnoDB   DEFAULT CHARSET=utf8;


CREATE TABLE `#__angelgirls_vt_album` ( 
	`id_album` INT NOT NULL, 
	`id_usuario` INT NOT NULL, 
	`data_criado` DATETIME NOT NULL,
	`host_ip` varchar(20) NOT NULL,
	FOREIGN KEY (`id_usuario`) REFERENCES `#__users` (`id`),
	FOREIGN KEY (`id_album`) REFERENCES `#__angelgirls_album` (`id`),
	PRIMARY KEY(`id_album`,`id_usuario`)
) ENGINE = InnoDB   DEFAULT CHARSET=utf8;

CREATE TABLE `#__angelgirls_vt_fotografo` ( 
	`id_fotografo` INT NOT NULL, 
	`id_usuario` INT NOT NULL, 
	`data_criado` DATETIME NOT NULL,
	`host_ip` varchar(20) NOT NULL,
	FOREIGN KEY (`id_usuario`) REFERENCES `#__users` (`id`),
	PRIMARY KEY(`id_fotografo`,`id_usuario`)
) ENGINE = InnoDB   DEFAULT CHARSET=utf8;


CREATE TABLE `#__angelgirls_vt_sessao` ( 
	`id_sessao` INT NOT NULL, 
	`id_usuario` INT NOT NULL, 
	`data_criado` DATETIME NOT NULL,
	`host_ip` varchar(20) NOT NULL,
	FOREIGN KEY (`id_usuario`) REFERENCES `#__users` (`id`),
	PRIMARY KEY(`id_sessao`,`id_usuario`)
) ENGINE = InnoDB   DEFAULT CHARSET=utf8;

CREATE TABLE `#__angelgirls_vt_modelo` ( 
	`id_modelo` INT NOT NULL, 
	`id_usuario` INT NOT NULL, 
	`data_criado` DATETIME NOT NULL,
	`host_ip` varchar(20) NOT NULL,
	FOREIGN KEY (`id_usuario`) REFERENCES `#__users` (`id`),
	PRIMARY KEY(`id_modelo`,`id_usuario`)
) ENGINE = InnoDB   DEFAULT CHARSET=utf8;

CREATE TABLE `#__angelgirls_vt_foto_sessao` ( 
	`id_foto` INT NOT NULL, 
	`id_usuario` INT NOT NULL, 
	`data_criado` DATETIME NOT NULL,
	`host_ip` varchar(20) NOT NULL,
	FOREIGN KEY (`id_usuario`) REFERENCES `#__users` (`id`),
	PRIMARY KEY(`id_foto`,`id_usuario`)
) ENGINE = InnoDB   DEFAULT CHARSET=utf8;

CREATE TABLE `#__angelgirls_vt_video_sessao` ( 
	`id_video` INT NOT NULL, 
	`id_usuario` INT NOT NULL, 
	`data_criado` DATETIME NOT NULL,
	`host_ip` varchar(20) NOT NULL,
	FOREIGN KEY (`id_usuario`) REFERENCES `#__users` (`id`),
	PRIMARY KEY(`id_video`,`id_usuario`)
) ENGINE = InnoDB   DEFAULT CHARSET=utf8;


CREATE TABLE `#__angelgirls_vt_equipe` ( 
	`id_equipe` INT NOT NULL, 
	`id_usuario` INT NOT NULL,
	`host_ip` varchar(20) NOT NULL,
	`data_criado` DATETIME NOT NULL,
	FOREIGN KEY (`id_usuario`) REFERENCES `#__users` (`id`),
	PRIMARY KEY(`id_equipe`,`id_usuario`)
) ENGINE = InnoDB   DEFAULT CHARSET=utf8;



CREATE TABLE `#__angelgirls_vt_post` ( 
	`id_post` INT NOT NULL, 
	`id_usuario` INT NOT NULL, 
	`data_criado` DATETIME NOT NULL,
	`host_ip` varchar(20) NOT NULL,
	FOREIGN KEY (`id_usuario`) REFERENCES `#__users` (`id`),
	PRIMARY KEY(`id_post`,`id_usuario`)
) ENGINE = InnoDB   DEFAULT CHARSET=utf8;


CREATE TABLE `#__angelgirls_vt_visitante` ( 
	`id_visitante` INT NOT NULL, 
	`id_usuario` INT NOT NULL, 
	`data_criado` DATETIME NOT NULL,
	`host_ip` varchar(20) NOT NULL,
	FOREIGN KEY (`id_usuario`) REFERENCES `#__users` (`id`),
	PRIMARY KEY(`id_visitante`,`id_usuario`)
) ENGINE = InnoDB   DEFAULT CHARSET=utf8;


CREATE TABLE `#__angelgirls_vt_comentario_post` ( 
	`id_comentario` INT NOT NULL, 
	`id_usuario` INT NOT NULL, 
	`data_criado` DATETIME NOT NULL,
	`host_ip` varchar(20) NOT NULL,
	FOREIGN KEY (`id_usuario`) REFERENCES `#__users` (`id`),
	PRIMARY KEY(`id_usuario`,`id_comentario`)
) ENGINE = InnoDB   DEFAULT CHARSET=utf8;



CREATE TABLE `#__query_logs` ( 
		`id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT, 
		`query` text NOT NULL,
		`host_ip` varchar(20) NOT NULL,
		`id_usuario`  NULL , 
		`data` DATETIME NOT NULL ,
		FOREIGN KEY (`id_usuario`) REFERENCES `#__users` (`id`)
) ENGINE = InnoDB   DEFAULT CHARSET=utf8;


CREATE TABLE `#__angelgirls_amizade` ( 
	`id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT, 
	`id_usuario_solicitante` INT NOT NULL , 
	`id_usuario_solicitado` INT NOT NULL , 
	`data_solicitada` DATETIME NOT NULL, 
	`data_aceita` DATETIME NULL,
	`host_ip_solicitante` varchar(20) NOT NULL,
	`host_ip_aceitou` varchar(20) NULL,
	FOREIGN KEY (`id_usuario_solicitante`) REFERENCES `#__users` (`id`),
	FOREIGN KEY (`id_usuario_solicitado`) REFERENCES `#__users` (`id`)
) ENGINE = InnoDB   DEFAULT CHARSET=utf8;

CREATE TABLE `#__angelgirls_amizade_lista_contato` (
	`id_lista` INT NOT NULL,
	`id_usuario` INT NOT NULL,
	`data_alterado` DATETIME NOT NULL,
	`host_ip_criador` varchar(20) NOT NULL,
	PRIMARY KEY(id_lista, id_usuario),
	FOREIGN KEY (`id_usuario`) REFERENCES `#__users` (`id`),
	FOREIGN KEY (`id_lista`) REFERENCES `#__angelgirls_amizade_lista` (`id`)
)

CREATE TABLE `#__angelgirls_amizade_lista` ( 
	`id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT, 
    `nome` varchar(255) NOT NULL DEFAULT 'AMIGOS', 
	`status_dado` VARCHAR(25) DEFAULT 'NOVO',
	`id_usuario_criador` INT NOT NULL , 
	`id_usuario_alterador` INT NOT NULL ,
    `sistema` enum('S','N') DEFAULT 'N',
	`data_criado` DATETIME NOT NULL, 
	`data_alterado` DATETIME NOT NULL,
	`host_ip_criador` varchar(20) NOT NULL,
	`host_ip_alterador` varchar(20) NULL,
	FOREIGN KEY (`id_usuario_criador`) REFERENCES `#__users` (`id`),
	FOREIGN KEY (`id_usuario_alterador`) REFERENCES `#__users` (`id`)
) ENGINE = InnoDB   DEFAULT CHARSET=utf8;

CREATE TABLE `#__angelgirls_seguindo` ( 
	`id` INT PRIMARY KEY NOT NULL AUTO_INCREMENT, 
	`id_usuario_seguidor` INT NOT NULL , 
	`id_usuario_seguido` INT NOT NULL , 
	`data` DATETIME NOT NULL, 
	`host_ip` varchar(20) NULL,
	FOREIGN KEY (`id_usuario_seguidor`) REFERENCES `#__users` (`id`),
	FOREIGN KEY (`id_usuario_seguido`) REFERENCES `#__users` (`id`),
) ENGINE = InnoDB   DEFAULT CHARSET=utf8;

ALTER TABLE `#__angelgirls_seguindo` ADD UNIQUE INDEX (`id_usuario_seguidor`, `id_usuario_seguido`);


CREATE  INDEX album_foto_usuario_criador_idx ON `#__angelgirls_foto_album`(`id_usuario_criador`,`id_album` );
CREATE  INDEX album_usuario_criador_idx ON `#__angelgirls_album`(`id_usuario_criador`);