<?php
/**
 * @CreateTime:   2020/2/20 下午11:57
 * @Author:       huizhang  <tuzisir@163.com>
 * @Copyright:    copyright(2020) Easyswoole all rights reserved
 * @Description:
 */
class Result {
    private $total;

    public function setTotal($total)
    {
        $this->total = $total;
    }

    public function getTotal()
    {
        return $this->total;
    }
}

class AbstractModel
{
    protected $table=null;
    private $offset = 0;
    private $limit = 10;

    /** @var $result Result */
    public $result;

    public static function create()
    {
        return new AbstractModel();
    }

    public function limit($offset, $limit)
    {
        $this->offset = $offset;
        $this->limit = $limit;
        return $this;
    }

    public function withCountTotal()
    {
        $sql = "select count(*) as total from {$this->table} limit {$this->offset}, {$this->limit}";
        // 执行

        $total = 10;
        $result = new Result();
        $result->setTotal($total);
        $this->result = $result;
        return $this;
    }

    public function all()
    {
        $sql = "select * from {$this->table} limit {$this->offset}, {$this->limit}";
        //

        $result = [];

        return $result;
    }
}

class Admin extends AbstractModel
{

    protected $table='admin';

}

$model = Admin::create()->limit(0, 10)->withCountTotal();
$list = $model->all();

$total = $model->result->getTotal();
