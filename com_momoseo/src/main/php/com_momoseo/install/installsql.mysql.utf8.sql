
/*

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
*/