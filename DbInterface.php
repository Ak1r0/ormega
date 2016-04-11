<?php

namespace Ormega;


interface DbInterface
{
    /**
     * Execute a mysql request
     *
     * @param string $sSql
     *
     * @return mixed Return the result from the driver's query method
     *
     * @author Ormega\Generator
     */
    public function query( $sSql );

    /**
     * Escape a value for a sql query
     *
     * @param mixed  $value The value to escape
     * @param string $sType Litteral php type : 'int', 'string', 'bool', 'flaot', etc
     *
     * @return mixed
     *
     * @author Ormega\Generator
     */
    public function escape( $value, $sType );

    /**
     * Get the last inserted id
     *
     * @return int
     *
     * @author Ormega\Generator
     */
    public function getLastId();

    /**
     * Lit une ligne de résultat SQL dans un tableau associatif
     *
     * @param ressource $ressource      Le resultat de la fonction query
     *                                      dont il faut extraire les données
     *
     * @return mixed Retourne un tableau associatif de chaînes qui contient la
     *                                      ligne lue dans le résultat result,
     *                                      ou bien FALSE s'il ne reste plus de
     *                                      lignes à lire.
     * @author Ormega\Generator
     */
    public function fetch_assoc( $ressource );

    /**
     * Get a result row as an enumerated array
     *
     * @param ressource $in_oLastResult Le resultat de la fonction query
     *                                      dont il faut extraire les données
     * @return mixed Returns an numerical array of strings that corresponds to
     *                                      the fetched row, or FALSE if there
     *                                      are no more rows.
     * @author Ormega\Generator
     */
    public function fetch_row( $ressource );
}