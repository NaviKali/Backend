<?php

namespace app\common\trait;


trait DictionaryMap
{
    /**
     * Static DictionaryMap Push Value
     * @access public
     * @author liulei
     * @static
     * @param string $dictionaryMapName must
     * @param array  $dictionaryMap must
     * @return void
     */
    public static function setDictionaryMap(string $dictionaryMapName, array $dictionaryMap): void
    {
        self::$dictionaryMap[$dictionaryMapName] = array_merge(self::$dictionaryMap[$dictionaryMapName], $dictionaryMap);
    }
    /**
     * Get Other's DictionaryMap
     * @access public
     * @static
     * @author liulei
     * @param string $dictionaryMapName must
     * @return array
     */
    public static function getOthersDictionaryMap(string $dictionaryMapName): array
    {
        return self::$dictionaryMap[$dictionaryMapName] ?? [];
    }
    /**
     * Get Admin's DictionaryMap 
     * @author liulei
     * @access public
     * @static
     * @return array
     */
    public static function getAdminDictionaryMap(): array
    {
        return self::$dictionaryMap["admin"];
    }
    /**
     * Get Api's DictionaryMap
     * @author liulei
     * @access public
     * @static
     * @return array
     */
    public static function getApiDictionaryMap(): array
    {
        return self::$dictionaryMap["api"];
    }
}