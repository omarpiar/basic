<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "peliculas".
 *
 * @property int $id
 * @property string $strNombre
 * @property string|null $strGenero
 * @property string|null $strSinopsis
 * @property string|null $strHorario
 * @property string|null $strSala
 * @property string|null $Imagen
 * @property string|null $strUrlVideo
 * @property string|null $strEstadoPelicula
 */
class Peliculas extends \yii\db\ActiveRecord
{
    const GENEROS = [
        'Acción' => 'Acción',
        'Comedia' => 'Comedia',
        'Drama' => 'Drama',
        'Ciencia Ficción' => 'Ciencia Ficción',
        'Terror' => 'Terror'
    ];
    
    const SALAS = [
        'Sala 1' => 'Sala 1',
        'Sala 2' => 'Sala 2',
        'Sala 3' => 'Sala 3',
        'Sala VIP' => 'Sala VIP'
    ];

    const HORARIOS = [
        '10:00' => '10:00 AM',
        '13:00' => '1:00 PM',
        '16:00' => '4:00 PM',
        '19:00' => '7:00 PM',
        '22:00' => '10:00 PM'
    ];
    
    const ESTADOS = [
        'Estreno' => 'Estreno',
        'Cartelera' => 'Cartelera',
        'Fuera de Cartelera' => 'Fuera de Cartelera'
    ];
    
    public $imagenFile; // Para manejar la subida de archivos


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'peliculas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['strNombre', 'strGenero', 'strSinopsis', 'strHorario', 'strSala', 'strEstadoPelicula'], 'required'],
            [['strSinopsis'], 'string'],
            [['strNombre', 'strGenero', 'strHorario', 'strSala', 'strEstadoPelicula', 'Imagen', 'strUrlVideo'], 'string', 'max' => 255],
            ['strGenero', 'in', 'range' => array_keys(self::GENEROS)],
            ['strSala', 'in', 'range' => array_keys(self::SALAS)],
            ['strEstadoPelicula', 'in', 'range' => array_keys(self::ESTADOS)],
            [['imagenFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg'],
            [['strUrlVideo'], 'url'],
            ['strHorario', 'match', 'pattern' => '/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'imagenFile' => 'Imagen de la Película',
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            if ($this->imagenFile) {
                // Configuración FTP
                $ftpConfig = Yii::$app->params['somee'];
                $remoteFileName = time() . '_' . $this->imagenFile->baseName . '.' . $this->imagenFile->extension;
                $remotePath = $ftpConfig['upload_path'] . $remoteFileName;
                
                // Conexión FTP
                $connId = ftp_connect($ftpConfig['ftp_host']);
                $login = ftp_login($connId, $ftpConfig['ftp_user'], $ftpConfig['ftp_pass']);
                ftp_pasv($connId, true); // Modo pasivo para Somee
                
                // Subir archivo
                $tempFile = tempnam(sys_get_temp_dir(), 'somee');
                $this->imagenFile->saveAs($tempFile);
                
                if (ftp_put($connId, $remotePath, $tempFile, FTP_BINARY)) {
                    $this->Imagen = $ftpConfig['web_root'] . ltrim($remotePath, '/');
                }
                
                ftp_close($connId);
                unlink($tempFile);
            }
            return true;
        }
        return false;
    }

    public function getHorarioFormateado()
{
    return date('h:i A', strtotime($this->strHorario));
}
    

}
