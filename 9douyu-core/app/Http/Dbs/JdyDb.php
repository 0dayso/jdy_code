<?php
/**
 * Created by PhpStorm.
 * User: hexing
 * Date: 16/4/13
 * Time: 下午6:33
 */
namespace App\Http\Dbs;

use Illuminate\Database\Eloquent\Model as BaseDb;
use Illuminate\Support\Str;

/**
 * Class JdyDb
 * @package App\Http\Dbs
 */
class JdyDb extends BaseDb{
    
    /**
     * @var \Illuminate\Database\Query\Builder
     */
    protected $_sql_builder = null;

    /**
     * 白名单控制
     * @param array $attributes
     * @param array $fillable
     */
    public function __construct(array $attributes = [], array $fillable = []){
        // 子模型白名单
        if($fillable !== null)
            $this->fillable($fillable);
        // sql日志打印
        app('db')->connection()->enableQueryLog();

        parent::__construct($attributes);
        
        $this->_initSqlBuilder(); //每次初始化对象，重置SQL Builder
    }
    
    /**
     * 初始化sql builder，首次得到当前db作为builder，以完成后续链式操作
     */
    protected function _initSqlBuilder() {
        $this->_sql_builder = $this;
    }

    /**
     * DB链式方法示例
     * @param $status
     *
     * @return $this
     */
    private function _dbLinkExample($status)
    {
        if( !empty($status) ){
            $this->_sql_builder = $this->_sql_builder->where('status', $status);
        }

        return $this;
    }

    /**
     * 获取DB链式查询的sql builder
     * @param bool|false $new 值为true则强制使用新的SqlBuilder
     *
     * @return $this|\Illuminate\Database\Query\Builder
     */
    public function getSqlBuilder($new = false) {
        if($new || empty($this->_sql_builder)) {
            $this->_initSqlBuilder();
        }
        return $this->_sql_builder;
    }
    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable()
    {
        if (isset($this->table)) {
            return $this->table;
        }

        return str_replace('\\', '', Str::snake(preg_replace('#DB$#i', '', class_basename($this))));
    }

    /**
     * 获取sql
     */
    public static function getSql(){
        echo "-------SQL-LOG# <br/>\n";
        echo print_r(app('db')->getQueryLog(), true);
        echo "\n<br/> -------END# <br/>\n";
    }

    /**
     * 获取最后执行的SQL
     * @return string
     */
    public static function getLastSql() {
        $sqlList = app('db')->getQueryLog();

        $lastSqlInfo = end($sqlList);
        
        if(empty($lastSqlInfo['query'])) {
            return '';
        }

        $params = $lastSqlInfo['bindings'];
        foreach ($params as $index => $param) {
            if ($param instanceof DateTime) {
                $params[$index] = $param->format('Y-m-d H:i:s');
            }
        }
        $sql = str_replace("?", "'%s'", $lastSqlInfo['query']);
        array_unshift($params, $sql);
        
        return call_user_func_array('sprintf', $params) . " [Time:{$lastSqlInfo['time']}]";
    }


    public function __destruct(){
        //self::getSql();
    }

    /**
     * @param $page
     * @param $size
     * @return mixed
     * @desc 返回查询开始值
     */
    public function getLimitStart($page,$size)
    {

        return ( max(0, $page -1) ) * $size;

    }

    /**
     * @param $result
     * @return array
     * @desc find-first不可直接跟toArray
     */
    public function dbToArray($result){

        if(is_object($result)){
            return $result -> toArray();
        }else{
            return [];
        }

    }

}
