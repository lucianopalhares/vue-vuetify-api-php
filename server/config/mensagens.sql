
SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for mensagens
-- ----------------------------
DROP TABLE IF EXISTS `mensagens`;
CREATE TABLE `mensagens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `chave` varchar(255) DEFAULT NULL,
  `path` varchar(400) DEFAULT NULL,
  `mensagem` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of mensagens
-- ----------------------------
INSERT INTO `mensagens` VALUES ('1', 'saude', null, 'Voce Chegou aqui porque viu falar sobre saude.');
