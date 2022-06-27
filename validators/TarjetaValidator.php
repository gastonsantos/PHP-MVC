<?php

class TarjetaValidator {

    public static function validateToPay($data) {
        if (sizeof($data) === 0) {
            throw new ValidationException('Hubo un problema al traer el formulario');
        }


        self::validateCreditCardNumber($data["numero"]);
        self::validateUserName($data["nombre"]);
        self::validateCVV($data["ccv"]);
        self::validateExpiracyDate($data["mes"], $data["year"]);
    }

    private static function validateCreditCardNumber($number) {
        if (strlen(trim($number)) > 19)
            throw new ValidationException('El campo numero de tarjeta es invalido.');

        if (strlen(trim($number)) < 1)
            throw new ValidationException('El campo numero de tarjeta esta vacio.');

    }

    private static function validateUserName($name) {
        if (strlen(trim($name)) < 1)
            throw new ValidationException('El campo nombre esta vacio.');
    }


    private static function validateCVV($number) {
        if (strlen(trim($number)) < 1)
            throw new ValidationException('El campo CVV esta vacio.');


        if ($number > 999)
            throw new ValidationException('El campo CVV es invalido.');
    }

    private static function validateExpiracyDate($month, $year) {
        if (strlen(trim($month)) < 1 || strlen(trim($year)) < 1)
            throw new ValidationException('Alguno de los campos mes o anio esta vacio.');

        if ($month > 12)
            throw new ValidationException('Se aceptan meses entre 1 y 12.');

        if ($year > 2030)
            throw new ValidationException('El anio maximo de vencimiento es 2030.');

    }


}
