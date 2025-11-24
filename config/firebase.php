<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Firebase Credentials
    |--------------------------------------------------------------------------
    |
    | Chemin vers le fichier JSON des credentials Firebase.
    | Le fichier doit être stocké dans storage/app/
    |
    */
    'credentials' => [
        'file' => storage_path('app/firebase.credentials.json'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Firebase Project ID
    |--------------------------------------------------------------------------
    |
    | L'ID du projet Firebase
    |
    */
    'project_id' => env('FIREBASE_PROJECT_ID', 'geopharma-b25da'),

    /*
    |--------------------------------------------------------------------------
    | Firebase Database URL
    |--------------------------------------------------------------------------
    |
    | URL de la base de données Firestore
    |
    */
    'database_url' => env('FIREBASE_DATABASE_URL', 'https://geopharma-b25da-default-rtdb.firebaseio.com'),

    /*
    |--------------------------------------------------------------------------
    | Firebase Storage Bucket
    |--------------------------------------------------------------------------
    |
    | Nom du bucket de stockage Firebase (optionnel)
    |
    */
    'storage_bucket' => env('FIREBASE_STORAGE_BUCKET', 'geopharma-b25da.appspot.com'),

    /*
    |--------------------------------------------------------------------------
    | Collections Firestore
    |--------------------------------------------------------------------------
    |
    | Noms des collections Firestore correspondant aux tables MySQL
    |
    */
    'collections' => [
        'users' => 'users',
        'pharmacies' => 'pharmacies',
        'authorization_numbers' => 'authorization_numbers',
    ],
];





