<?php
	class ModelModuleApiMautic extends Model {

        public function ExeUpdate($apiId, $data) {

            $this->db->query("UPDATE " . DB_PREFIX . "apimautic SET
                              oauth_token = '" . (isset($data['oauth_token']) ? $this->db->escape($data['oauth_token']) : '') . "',
                              oauth_verifier = '" . (isset($data['oauth_verifier']) ? $this->db->escape($data['oauth_verifier']) : '') . "', 
                              access_token_secret = '" . (isset($data['access_token_secret']) ? $this->db->escape($data['access_token_secret']) : '') . "',
                              access_token = '" . (isset($data['access_token']) ? $this->db->escape($data['access_token']) : '') . "', 
                              expires = '" . (isset($data['expires']) ? $this->db->escape($data['expires']) : '') . "',
                              date_modified = NOW()
                              WHERE id = '" . (int)$apiId . "'");

        }
        public function getData() {
            $query = $this->db->query("select * from `" . DB_PREFIX . "apimautic`");

            return $query->rows;
        }
	}
?>