<?php
namespace App\Service;

class FtpService
{

    private $host;
    private $login;
    private $pass;
    private $conn_id;

    public function connect(){
        $dir = 'rapports_mpe';
        $this->conn_id = ftp_connect($this->host);
        $login_result = ftp_login($this->conn_id, $this->login, $this->pass);

        if(in_array("/home/flottin/".$dir, ftp_nlist($this->conn_id, "/home/flottin/"))){
            //echo "Le dossier $dir existe<br>";

        } else {

            if (ftp_mkdir($this->conn_id, "/home/flottin/".$dir)) {
                //echo "Le dossier $dir a été créé avec succès<br>";
            } else {
                //echo "Il y a eu un problème lors de la création du dossier $dir<br>";
            }
        }
    }

    public function put($remote_file, $file){
        if (ftp_put($this->conn_id, $remote_file, $file, FTP_BINARY)) {
            //echo "Le fichier $file a été chargé avec succès<br>";
        } else {
            //echo "Il y a eu un problème lors du chargement du fichier $file<br>";
        }
    }

    public function get($remote_file, $file){
        $tmppath = explode('/', $file);
        array_pop($tmppath);
        $tmp = implode('/', $tmppath);
        if (is_dir($tmp)){

        } else {
            mkdir($tmp);
        }

        $remotePath = explode('/', $remote_file);
        array_pop($remotePath);
        $rpath = implode('/', $remotePath);

        if(in_array($remote_file, ftp_nlist($this->conn_id, $rpath))){
            if (ftp_get($this->conn_id, $file, $remote_file,  FTP_BINARY)) {
                //echo "Le fichier $file a été chargé avec succès\n";
                return $file;
            } else {
                //echo "Il y a eu un problème lors du chargement du fichier $file\n";
                return null;
            }
        } else {
            echo "Le fichier $file n'existe pas! <br>";
            return null;
        }
    }

    /**
     * @return mixed
     */
    public function getHost ()
    {
        return $this->host;
    }

    /**
     * @param mixed $host
     */
    public function setHost ( $host ): void
    {
        $this->host = $host;
    }

    /**
     * @return mixed
     */
    public function getLogin ()
    {
        return $this->login;
    }

    /**
     * @param mixed $login
     */
    public function setLogin ( $login ): void
    {
        $this->login = $login;
    }

    /**
     * @return mixed
     */
    public function getPass ()
    {
        return $this->pass;
    }

    /**
     * @param mixed $pass
     */
    public function setPass ( $pass ): void
    {
        $this->pass = $pass;
    }
}