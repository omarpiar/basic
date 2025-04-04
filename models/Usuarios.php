<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "usuarios".
 *
 * @property int $id
 * @property string $strNombre
 * @property string $strPassword
 * @property string $EstadoUsuario
 */
class Usuarios extends \yii\db\ActiveRecord
{
    const ESTADOS = [
        'Activo' => 'Activo',
        'Inactivo' => 'Inactivo',
        'Suspendido' => 'Suspendido',
        'Pendiente' => 'Pendiente'
    ];


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'usuarios';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['strNombre', 'strPassword', 'EstadoUsuario'], 'required'],
            [['strNombre'], 'string', 'max' => 100],
            [['strPassword'], 'string', 'max' => 255],
            [['EstadoUsuario'], 'string', 'max' => 50],
            ['EstadoUsuario', 'in', 'range' => array_keys(self::ESTADOS)],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'strNombre' => 'Str Nombre',
            'strPassword' => 'Str Password',
            'EstadoUsuario' => 'Estado Usuario',
        ];
    }

}
