<?php
namespace App\Service;

use App\Entity\Client;
use Psr\Container\ContainerInterface;

class FtpService
{

    private $host;
    private $login;
    private $pass;
    private $conn_id;
    private $container;

    public function __construct (
        ContainerInterface $container
    )
    {
        $this->container = $container;
    }

    public function getConfig($client){
        $config = $this->container->getParameter($client->getName ());
        return $config['ftp'];

    }
    public function connect(Client $client){
        $ftp = self::getConfig($client);


        $this->setHost ('192.168.0.24');
        $this->setLogin ('flottin');
        $this->setPass ('bb');
        $this->conn_id = ftp_connect($this->host);
        $login_result = ftp_login($this->conn_id, $this->login, $this->pass);
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

    public function putRecursive($src_dir, $dst_dir) {
        $d = dir($src_dir);
        while($file = $d->read()) {
            if ($file != "." && $file != "..") {
                if (is_dir($src_dir."/".$file)) {
                    if (!@ftp_chdir($this->conn_id, $dst_dir."/".$file)) {
                        ftp_mkdir($this->conn_id, $dst_dir."/".$file);
                    }
                    self::putRecursive($src_dir."/".$file, $dst_dir."/".$file);
                } else {
                    ftp_put(
                        $this->conn_id,
                        $dst_dir."/".$file,
                        $src_dir."/".$file,
                        FTP_BINARY
                    );
                }
            }
        }
        $d->close();
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