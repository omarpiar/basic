<?php

namespace app\models;
use yii\db\ActiveRecord;

class User extends ActiveRecord  implements \yii\web\IdentityInterface
{
    // public $id;
    // public $username;
    // public $password;
    // public $authKey;
    // public $accessToken;

    public static function tableName()
    {
        return 'Usuarios';
    }

    // Implementación de IdentityInterface
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null; // No implementado para este ejemplo
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return null; // No usamos authKey en este ejemplo
    }

    public function validateAuthKey($authKey)
    {
        return false; // No usamos authKey en este ejemplo
    }

    // Método para validar la contraseña
    public function validatePassword($password)
    {
        return $this->strPassword === $password; // Comparación directa sin encriptación
    }
}
