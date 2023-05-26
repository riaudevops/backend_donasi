<?php
    function getConnection(): PDO {
        return new PDO("mysql:host=localhost;dbname=lutproje_donasi", "lutproje_user1", "donasiweb", [PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION]);
    }
