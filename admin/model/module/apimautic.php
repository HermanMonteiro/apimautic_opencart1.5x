<?php
	class ModelModuleApiMautic extends Model {

		public function install() {
			$this->db->query("CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "apimautic` (
							  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
							  `callback` varchar(255) DEFAULT NULL,
							  `base_url` varchar(255) DEFAULT NULL,
							  `public_key` varchar(255) DEFAULT NULL,
							  `secret_key` varchar(255) DEFAULT NULL,
							  `oauth_token` varchar(255) DEFAULT NULL,
							  `oauth_verifier` varchar(255) DEFAULT NULL,
							  `access_token_secret` varchar(255) DEFAULT NULL,
							  `access_token` varchar(255) DEFAULT NULL,
							  `expires` varchar(255) DEFAULT NULL,
							  `status` int(11) DEFAULT NULL,
							  `date_added` DATETIME DEFAULT NULL,
							  `date_modified` DATETIME DEFAULT NULL,
							  PRIMARY KEY (`id`)
							) ");

            $this->db->query("INSERT INTO " . DB_PREFIX . "apimautic SET status = '0', date_added = NOW(),
                              date_modified = NOW()");

		}

        public function uninstall() {
            $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "apimautic`;");
        }

        public function ExeUpdate($apiId, $data) {

            $this->db->query("UPDATE " . DB_PREFIX . "apimautic SET
                              callback = '" . (isset($data['callback']) ? $this->db->escape($data['callback']) : '') . "', 
                              base_url = '" . (isset($data['base_url']) ? $this->db->escape($data['base_url']) : '') . "', 
                              public_key = '" . (isset($data['public_key']) ? $this->db->escape($data['public_key']) : '') . "',
                              secret_key = '" . (isset($data['secret_key']) ? $this->db->escape($data['secret_key']) : '') . "',
                              status = '" . (isset($data['status']) ? $this->db->escape($data['status']) : ''). "',
                              date_modified = NOW()
                              WHERE id = '" . (int)$apiId . "'");

            $query = $this->db->query("select * from `" . DB_PREFIX . "apimautic`");
            return $query->rows;
        }

        public function getData() {
			$query = $this->db->query("select * from `" . DB_PREFIX . "apimautic`");

			return $query->rows;
		}
	}
?>