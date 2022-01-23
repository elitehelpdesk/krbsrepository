<?php
class CrewDocument extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function findById($crewDocumentId) {
        $crewDocument = $this->db->select()
            ->from('document_upload_history dh')
            ->where('dh.id', $crewDocumentId)
            ->get()->row();

        return $crewDocument;
    }

    public function crewDocumentHistory($documentId, $crewipn) {
        $histories = $this->db->select()
            ->from('document_upload_history dh')
            ->where('document_code', $documentId)
            ->where('crewipn', $crewipn)
            ->limit(3)
            ->order_by('dh.id', 'desc')

            ->get()->result();

        return $histories;
    }

}
