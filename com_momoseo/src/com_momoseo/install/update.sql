

CREATE TABLE `#__angelgirls_vt_post_arquivo` ( 
	`id_post_arquivo` INT NOT NULL, 
	`id_usuario` INT NOT NULL, 
	`data_criado` DATETIME NOT NULL,
	`host_ip` varchar(20) NOT NULL,
	FOREIGN KEY (`id_usuario`) REFERENCES `#__users` (`id`),
	FOREIGN KEY (`id_post_arquivo`) REFERENCES `#__angelgirls_post_arquivo` (`id`),
	PRIMARY KEY(`id_visitante`,`id_post_arquivo`)
) ENGINE = InnoDB   DEFAULT CHARSET=utf8;


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


